<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Controller\Adminhtml\Report;

use CWSPS154\SalesOrderReports\Model\Report\AbstractDashboardOrder;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;

abstract class GenerateCSV extends Action implements HttpGetActionInterface
{
    /**
     * @var array
     */
    protected array $headers = [];

    /**
     * @var array|string[]
     */
    protected array $types = AbstractDashboardOrder::TYPES;

    /**
     * @param $result
     * @param $field
     * @return string
     */
    public function generateCSVContent($result, $field): string
    {
        $csv = implode(',', $this->headers) . "\n";

        $ageingData = [];
        $grandTotals = array_fill_keys($this->types, ['no_of_orders' => 0, 'values' => 0]);
        foreach ($result as $order) {
            $ageing = $order->getData($field);
            $type = $order->getType();
            $noOfOrders = $order->getNoOfOrders();
            $values = $order->getValue();
            if (!isset($ageingData[$ageing])) {
                $ageingData[$ageing] = array_fill_keys($this->types, ['no_of_orders' => 0, 'values' => 0]);
            }
            $ageingData[$ageing][$type]['no_of_orders'] += $noOfOrders;
            $ageingData[$ageing][$type]['values'] += $values;
            $grandTotals[$type]['no_of_orders'] += $noOfOrders;
            $grandTotals[$type]['values'] += $values;
        }
        foreach ($ageingData as $key => $Data) {
            $data = isset($ageingData[$key]) ? $Data : array_fill_keys($this->types, ['no_of_orders' => 0, 'values' => 0]);
            $row = [
                $key,
                $data['click_n_collect_click_n_collect']['no_of_orders'],
                $data['click_n_collect_click_n_collect']['values'],
                $data['warehouse_pickup_warehouse_pickup']['no_of_orders'],
                $data['warehouse_pickup_warehouse_pickup']['values'],
                $data['accessories']['no_of_orders'],
                $data['accessories']['values'],
                $data['furniture_or_mixed']['no_of_orders'],
                $data['furniture_or_mixed']['values'],
            ];
            $csv .= implode(',', $row) . "\n";
        }
        $grandTotalRow = [
            __('Grand Total'),
            $grandTotals['click_n_collect_click_n_collect']['no_of_orders'],
            $grandTotals['click_n_collect_click_n_collect']['values'],
            $grandTotals['warehouse_pickup_warehouse_pickup']['no_of_orders'],
            $grandTotals['warehouse_pickup_warehouse_pickup']['values'],
            $grandTotals['accessories']['no_of_orders'],
            $grandTotals['accessories']['values'],
            $grandTotals['furniture_or_mixed']['no_of_orders'],
            $grandTotals['furniture_or_mixed']['values'],
        ];
        $csv .= implode(',', $grandTotalRow) . "\n";

        return $csv;
    }
}
