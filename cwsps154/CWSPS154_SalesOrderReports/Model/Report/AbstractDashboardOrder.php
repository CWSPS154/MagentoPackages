<?php

namespace CWSPS154\SalesOrderReports\Model\Report;

use DateInterval;
use Exception;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;
use Magento\Sales\Model\ResourceModel\Order\Collection;

abstract class AbstractDashboardOrder
{
    public const FLATRATE_FLATRATE = 'flatrate_flatrate';
    public const FREESHIPPING_FREESHIPPING = 'freeshipping_freeshipping';
    public const CATEGORIES = 'categories';
    public const OTHERS = 'others';

    public const TYPES = [
        'Flat Rate' => self::FLATRATE_FLATRATE,
        'Free Shipping' => self::FREESHIPPING_FREESHIPPING,
        'Selected Categories' =>self::CATEGORIES,
        'Others' => self::OTHERS,
    ];

    /**
     * Get the order collection based on some criteria
     *
     * @param string $fromDate
     * @param string $toDate
     * @param array|string $status
     * @param int $storeId
     * @return Collection
     */
    abstract public function getCollection(
        string $fromDate,
        string $toDate,
        array|string $status = "pending",
        int $storeId = 0
    ): Collection;

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
        try {
            $orderCollection = $this->getCollection($fromDate, $toDate, $status, $storeId);
            /** @var Order $order */
            foreach ($orderCollection->getItems() as $order) {
                $shippingMethod = $order->getShippingMethod();
                $grand_total = $order->getGrandTotal();

                if (in_array($shippingMethod, array_keys($orderData))) {
                    $orderData[$shippingMethod]['order_count']++;
                    $orderData[$shippingMethod]['grand_total'] += $grand_total;
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
                    $orderData[self::CATEGORIES]['order_count']++;
                    $orderData[self::CATEGORIES]['grand_total'] += $grand_total;
                } else {
                    $orderData[self::OTHERS]['order_count']++;
                    $orderData[self::OTHERS]['grand_total'] += $grand_total;
                }
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
        return $orderData;
    }

    /**
     * @param array $period
     * @param TimezoneInterface $localDate
     * @return array
     * @throws Exception
     */
    public function getTimePeriod(array $period, TimezoneInterface $localDate): array
    {
        $fromDate = $localDate->date();
        $toDate = $localDate->date();

        $fromInterval = new DateInterval($period['from']);
        $toInterval = new DateInterval($period['to']);

        if ($period['from_inverted']) {
            $fromDate->sub($fromInterval);
        } else {
            $fromDate->add($fromInterval);
        }

        if ($period['to_inverted']) {
            $toDate->sub($toInterval);
        } else {
            $toDate->add($toInterval);
        }

        $fromDateFormatted = $fromDate->format('Y-m-d H:i:s');
        $toDateFormatted = $toDate->format('Y-m-d H:i:s');
        return [$fromDateFormatted, $toDateFormatted];
    }

    /**
     *Set the order details to the table
     *
     * @return array
     * @throws NoSuchEntityException
     */
    abstract public function setOrderDetails(): array;

    /**
     * Get the pending order data based on store id
     *
     * @param int $storeId
     * @return array
     */
    abstract public function getOrderDetails(int $storeId): array;
}
