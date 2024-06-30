<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Block\Adminhtml\Report;

use CWSPS154\SalesOrderReports\Model\Report\OrderRegionReport;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;

class OrderRegionReportGrid extends Template
{
    /**
     * @param Context $context
     * @param OrderRegionReport $orderRegionReport
     * @param array $data
     */
    public function __construct(
        public readonly Context           $context,
        public readonly OrderRegionReport $orderRegionReport,
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
            $result = $this->orderRegionReport->getOrderDetails($storeId);
            foreach ($result as $item) {
                $data[$item->getRegion()][$item->getType()] = $item;
            }
        } catch (LocalizedException $e) {
            $this->_logger->critical($e->getMessage());
        }
        return $data;
    }
}
