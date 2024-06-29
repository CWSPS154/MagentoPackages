<?php

namespace CWSPS154\SalesOrderReports\Block\Adminhtml\Report;

use CWSPS154\SalesOrderReports\Model\Report\PendingOrderReport;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;

class PendingOrderReportGrid extends Template
{
    /**
     * @param Context $context
     * @param PendingOrderReport $pendingOrderReport
     * @param array $data
     */
    public function __construct(
        public readonly Context            $context,
        public readonly PendingOrderReport $pendingOrderReport,
        array                              $data = []
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
        $storeId = $this->getRequest()->getParam('store', 0);
        $result = $this->pendingOrderReport->getOrderDetails($storeId);
        $data = [];
        foreach ($result as $item) {
            $data[PendingOrderReport::AGEING_GROUP[$item->getAgeing()]][] = $item;
        }
        return $data;
    }
}
