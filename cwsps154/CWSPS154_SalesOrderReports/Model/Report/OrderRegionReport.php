<?php

namespace CWSPS154\SalesOrderReports\Model\Report;

use CWSPS154\SalesOrderReports\Api\DashboardOrderRegionRepositoryInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderRegionInterfaceFactory;
use CWSPS154\SalesOrderReports\Model\Config\Data;
use Exception;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;
use Magento\Sales\Model\ResourceModel\Order\Collection;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class OrderRegionReport extends AbstractDashboardOrder
{
    public const DATE_RANGE = ['from' => 'P1D', 'to' => 'P0D', 'from_inverted' => true, 'to_inverted' => false];

    /**
     * @param CollectionFactory $orderCollectionFactory
     * @param DashboardOrderRegionInterfaceFactory $dashboardOrderRegionInterfaceFactory
     * @param DashboardOrderRegionRepositoryInterface $dashboardOrderRegionRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param StoreManagerInterface $storeManager
     * @param TimezoneInterface $localeDate
     * @param LoggerInterface $logger
     * @param Data $configData
     */
    public function __construct(
        public readonly CollectionFactory                       $orderCollectionFactory,
        public readonly DashboardOrderRegionInterfaceFactory    $dashboardOrderRegionInterfaceFactory,
        public readonly DashboardOrderRegionRepositoryInterface $dashboardOrderRegionRepository,
        public readonly SearchCriteriaBuilder                   $searchCriteriaBuilder,
        public readonly StoreManagerInterface                   $storeManager,
        public readonly TimezoneInterface                       $localeDate,
        public readonly LoggerInterface                         $logger,
        public readonly Data                                    $configData
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
            ->addFieldToFilter('status', ['in' => $status])
            ->addFieldToFilter('created_at', ['from' => $fromDate, 'to' => $toDate])
            ->addFieldToFilter('store_id', ['eq' => $storeId]);
    }

    /**
     * get  the pending order details based on some interval
     *
     * @param string $fromDate
     * @param string $toDate
     * @param int $storeId
     * @param array|string $status
     * @return array|array[]
     */
    protected function getGridDataByPeriod(
        string $fromDate,
        string $toDate,
        int $storeId,
        array|string $status = "pending"
    ): array {
        $orderData = [
            self::FLATRATE_FLATRATE => ['order_count' => 0, 'grand_total' => 0],
            self::FREESHIPPING_FREESHIPPING => ['order_count' => 0, 'grand_total' => 0],
            self::CATEGORIES => ['order_count' => 0, 'grand_total' => 0],
            self::OTHERS => ['order_count' => 0, 'grand_total' => 0],
        ];
        $data = [];
        try {
            $orderCollection = $this->getCollection($fromDate, $toDate, $status, $storeId);

            /** @var Order $order */
            foreach ($orderCollection->getItems() as $order) {
                $shippingMethod = $order->getShippingMethod();
                $grand_total = $order->getGrandTotal();

                $region = $order->getShippingAddress()->getRegion();
                $data[$order->getRealOrderId()] = [$region => $orderData];
                if (in_array($shippingMethod, array_keys($orderData))) {
                    $data[$order->getRealOrderId()][$region][$shippingMethod]['order_count']++;
                    $data[$order->getRealOrderId()][$region][$shippingMethod]['grand_total'] += $grand_total;
                    continue;
                }

                $flag = true;
                /** @var Item $item */
                foreach ($order->getItems() as $item) {
                    if ($item->getProduct() && is_array($item->getProduct()->getCategoryIds())) {
                        $productCategories = $item->getProduct()->getCategoryIds();
                        $configCategories = $this->configData->getCategories($storeId);
                        if (!empty(array_diff($productCategories, $configCategories))) {
                            $flag = false;
                            break;
                        }
                    }
                }

                if ($flag) {
                    $data[$order->getRealOrderId()][$region][self::CATEGORIES]['order_count']++;
                    $data[$order->getRealOrderId()][$region][self::CATEGORIES]['grand_total'] += $grand_total;
                } else {
                    $data[$order->getRealOrderId()][$region][self::OTHERS]['order_count']++;
                    $data[$order->getRealOrderId()][$region][self::OTHERS]['grand_total'] += $grand_total;
                }
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
        return $data;
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
                $configStatus = $this->configData->getOrderStatus($storeId);
                $orderDetails[$storeId] = $this->getGridDataByPeriod(
                    $fromDateFormatted,
                    $toDateFormatted,
                    $storeId,
                    $configStatus
                );
            }
            $ordersRegionData = [];
            foreach ($orderDetails as $storeId => $orderDetail) {
                foreach ($orderDetail as $details) {
                    foreach ($details as $region => $detail) {
                        if (!isset($ordersRegionData[$region])) {
                            $ordersRegionData[$region] = [];
                        }
                        foreach ($detail as $type => $data) {
                            if (!isset($ordersRegionData[$region][$type])) {
                                $ordersRegionData[$region][$type] = [
                                    "order_count" => 0,
                                    "grand_total" => 0,
                                    'store_id' => '',
                                    'type' => '',
                                    'region' => ''
                                ];
                            }
                            $ordersRegionData[$region][$type]["order_count"] += $data["order_count"];
                            $ordersRegionData[$region][$type]["grand_total"] += $data["grand_total"];
                            $ordersRegionData[$region][$type]["store_id"] = $storeId;
                            $ordersRegionData[$region][$type]["type"] = $type;
                            $ordersRegionData[$region][$type]["region"] = $region;
                        }
                    }
                }
            }
            if ($this->dashboardOrderRegionRepository->truncateTable()) {
                $resultData = [];
                foreach ($ordersRegionData as $ORData) {
                    foreach ($ORData as $data) {
                        $resultData[] = [
                            'store_id' => $data['store_id'],
                            'type' => $data['type'],
                            'region' => $data['region'],
                            'no_of_orders' => $data['order_count'],
                            'value' => $data['grand_total']
                        ];
                    }
                }
                $this->dashboardOrderRegionRepository->bulkSave($resultData);
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
        return $this->dashboardOrderRegionRepository->getList($searchCriteria)->getItems();
    }
}
