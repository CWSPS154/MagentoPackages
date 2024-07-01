<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Block\Adminhtml\Report;

use CWSPS154\SalesOrderReports\Model\Report\OrderStatusReport;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;

class OrderStatusReportGrid extends Template
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = "CWSPS154_SalesOrderReports::dashboard/status_order_report.phtml";

    /**
     * @param Context $context
     * @param OrderStatusReport $orderStatusReport
     * @param array $data
     */
    public function __construct(
        public readonly Context           $context,
        public readonly OrderStatusReport $orderStatusReport,
        array                             $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get the pending order details
     *
     * @return array
     */
    public function getOrderDetails(): array
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $data = [];
        try {
            $result = $this->orderStatusReport->getOrderDetails($storeId);
            foreach ($result as $item) {
                $data[$item->getOrderStatus()][] = $item;
            }
        } catch (LocalizedException $e) {
            $this->_logger->critical($e->getMessage());
        }
        return $data;
    }
}
