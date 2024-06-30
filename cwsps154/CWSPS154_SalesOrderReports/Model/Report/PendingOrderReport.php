<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Model\Report;

use CWSPS154\SalesOrderReports\Api\DashboardPendingOrderRepositoryInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardPendingOrderInterfaceFactory;
use CWSPS154\SalesOrderReports\Model\Config\Data;
use Exception;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Sales\Model\ResourceModel\Order\Collection;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class PendingOrderReport extends AbstractDashboardOrder
{
    public const AGEING_GROUP = [
        '0-3D' => '0-3 Days',
        '3-10D' => '4-10 Days',
        '10-20D' => '10-20 Days',
        '20-30D' => '20-30 Days',
        '1Y-30D' => 'Last 1 Year',
    ];

    public const DATE_RANGES = [
        '0-3D' => ['from' => 'P3D', 'to' => 'P0D', 'from_inverted' => true, 'to_inverted' => false],
        '3-10D' => ['from' => 'P10D', 'to' => 'P3D', 'from_inverted' => true, 'to_inverted' => true],
        '10-20D' => ['from' => 'P20D', 'to' => 'P10D', 'from_inverted' => true, 'to_inverted' => true],
        '20-30D' => ['from' => 'P30D', 'to' => 'P20D', 'from_inverted' => true, 'to_inverted' => true],
        '1Y-30D' => ['from' => 'P1Y', 'to' => 'P30D', 'from_inverted' => true, 'to_inverted' => true],
    ];

    /**
     * @param CollectionFactory $orderCollectionFactory
     * @param DashboardPendingOrderInterfaceFactory $dashboardPendingOrderInterfaceFactory
     * @param DashboardPendingOrderRepositoryInterface $dashboardPendingOrderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param StoreManagerInterface $storeManager
     * @param TimezoneInterface $localeDate
     * @param LoggerInterface $logger
     * @param Data $configData
     */
    public function __construct(
        public readonly CollectionFactory                        $orderCollectionFactory,
        public readonly DashboardPendingOrderInterfaceFactory    $dashboardPendingOrderInterfaceFactory,
        public readonly DashboardPendingOrderRepositoryInterface $dashboardPendingOrderRepository,
        public readonly SearchCriteriaBuilder                    $searchCriteriaBuilder,
        public readonly StoreManagerInterface                    $storeManager,
        public readonly TimezoneInterface                        $localeDate,
        public readonly LoggerInterface                          $logger,
        public readonly Data                                     $configData
    ) {
    }

    /**
     * Get the order collection based on some criteria
     *
     * @param string $fromDate
     * @param string $toDate
     * @param array|string $status
     * @param int $storeId
     * @return Collection
     */
    public function getCollection(
        string $fromDate,
        string $toDate,
        array|string $status = "pending",
        int $storeId = 0
    ): Collection {
        return $this->orderCollectionFactory->create()
            ->addFieldToFilter('status', $status)
            ->addFieldToFilter('created_at', ['from' => $fromDate, 'to' => $toDate])
            ->addFieldToFilter('store_id', ['eq' => $storeId]);
    }

    /**
     *Set the order details to the table
     *
     * @return array
     * @throws Exception
     */
    public function setOrderDetails(): array
    {
        $storeIds = $this->storeManager->getStores();
        $orderDetails = [];

        try {
            foreach (self::DATE_RANGES as $ageing => $range) {
                list($fromDateFormatted, $toDateFormatted) = $this->getTimePeriod($range, $this->localeDate);

                foreach ($storeIds as $storeId => $storeName) {
                    $orderDetails[$storeId][$ageing] = $this->getGridDataByPeriod(
                        $fromDateFormatted,
                        $toDateFormatted,
                        $storeId
                    );
                }
            }

            if ($this->dashboardPendingOrderRepository->truncateTable()) {
                $pendingOrdersData = [];
                foreach ($orderDetails as $storeId => $ageings) {
                    foreach ($ageings as $ageing => $details) {
                        foreach ($details as $type => $detail) {
                            $pendingOrdersData[] = [
                                'store_id' => $storeId,
                                'type' => $type,
                                'ageing' => $ageing,
                                'no_of_orders' => (int)$detail['order_count'],
                                'value' => (float)$detail['grand_total']
                            ];
                        }
                    }
                }
                $this->dashboardPendingOrderRepository->bulkSave($pendingOrdersData);
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
        return $orderDetails;
    }

    /**
     * Get the pending order data based on store id
     *
     * @param int $storeId
     * @return array
     */
    public function getOrderDetails(int $storeId): array
    {
        $this->searchCriteriaBuilder->addFilter('store_id', $storeId);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        return $this->dashboardPendingOrderRepository->getList($searchCriteria)->getItems();
    }
}
