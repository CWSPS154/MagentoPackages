<?php

namespace CWSPS154\SalesOrderReports\Model\Report;

use CWSPS154\SalesOrderReports\Api\DashboardOrderStatusRepositoryInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderStatusInterfaceFactory;
use CWSPS154\SalesOrderReports\Model\Config\Data;
use Exception;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Sales\Model\ResourceModel\Order\Collection;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class OrderStatusReport extends AbstractDashboardOrder
{
    public const DATE_RANGE = ['from' => 'P1D', 'to' => 'P0D', 'from_inverted' => true, 'to_inverted' => false];

    /**
     * @param CollectionFactory $orderCollectionFactory
     * @param DashboardOrderStatusInterfaceFactory $dashboardOrderStatusInterfaceFactory
     * @param DashboardOrderStatusRepositoryInterface $dashboardOrderStatusRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param StoreManagerInterface $storeManager
     * @param TimezoneInterface $localeDate
     * @param LoggerInterface $logger
     * @param Data $configData
     * @param \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $collectionFactory
     */
    public function __construct(
        public readonly CollectionFactory                                                 $orderCollectionFactory,
        public readonly DashboardOrderStatusInterfaceFactory                              $dashboardOrderStatusInterfaceFactory,
        public readonly DashboardOrderStatusRepositoryInterface                           $dashboardOrderStatusRepository,
        public readonly SearchCriteriaBuilder                                             $searchCriteriaBuilder,
        public readonly StoreManagerInterface                                             $storeManager,
        public readonly TimezoneInterface                                                 $localeDate,
        public readonly LoggerInterface                                                   $logger,
        public readonly Data                                                              $configData,
        public readonly \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $collectionFactory
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
        string       $fromDate,
        string       $toDate,
        array|string $status = "pending",
        int          $storeId = 0
    ): Collection {
        return $this->orderCollectionFactory->create()
            ->addFieldToFilter('status', $status)
            ->addFieldToFilter('created_at', ['from' => $fromDate, 'to' => $toDate])
            ->addFieldToFilter('store_id', ['eq' => $storeId]);
    }

    /**
     *
     *Set the order details to the table
     *
     * @return array
     */
    public function setOrderDetails(): array
    {
        $storeIds = $this->storeManager->getStores();
        $orderDetails = [];

        try {
            list($fromDateFormatted, $toDateFormatted) = $this->getTimePeriod(self::DATE_RANGE, $this->localeDate);

            foreach ($storeIds as $storeId => $storeName) {
                $configOrderStatus = $this->configData->getOrderStatus($storeId);
                $allStatus = $this->getAllStatuses();
                foreach ($configOrderStatus as $orderStatus) {
                    $orderDetails[$storeId][$allStatus[$orderStatus]] = $this->getGridDataByPeriod(
                        $fromDateFormatted,
                        $toDateFormatted,
                        $storeId,
                        $orderStatus
                    );
                }
            }

            if ($this->dashboardOrderStatusRepository->truncateTable()) {
                $ordersStatusData = [];
                foreach ($orderDetails as $storeId => $orderStatus) {
                    foreach ($orderStatus as $status => $details) {
                        foreach ($details as $type => $detail) {
                            $ordersStatusData[] = [
                                'store_id' => $storeId,
                                'type' => $type,
                                'order_status' => $status,
                                'no_of_orders' => $detail['order_count'],
                                'value' => $detail['grand_total']
                            ];
                        }
                    }
                }
                $this->dashboardOrderStatusRepository->bulkSave($ordersStatusData);
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
     * @throws LocalizedException
     */
    public function getOrderDetails(int $storeId): array
    {
        $this->searchCriteriaBuilder->addFilter('store_id', $storeId);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        return $this->dashboardOrderStatusRepository->getList($searchCriteria)->getItems();
    }

    /**
     * Get all order status
     *
     * @return array
     */
    private function getAllStatuses(): array
    {
        $orderStatus = $this->collectionFactory->create()->toOptionArray();
        $convertedArray = [];
        foreach ($orderStatus as $status) {
            $convertedArray[$status['value']] = $status['label'];
        }
        return $convertedArray;
    }
}
