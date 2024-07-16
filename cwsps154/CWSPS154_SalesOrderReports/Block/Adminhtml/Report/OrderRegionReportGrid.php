<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Block\Adminhtml\Report;

use CWSPS154\SalesOrderReports\Model\Report\OrderRegionReport;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Framework\View\Element\Template;

class OrderRegionReportGrid extends Template
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = "CWSPS154_SalesOrderReports::dashboard/region_order_report.phtml";

    /**
     * @param Context $context
     * @param OrderRegionReport $orderRegionReport
     * @param PricingHelper $pricingHelper
     * @param array $data
     */
    public function __construct(
        public readonly Context           $context,
        public readonly OrderRegionReport $orderRegionReport,
        protected readonly PricingHelper  $pricingHelper,
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

    /**
     * Convert and format price value for specified store
     *
     * @param float $price
     * @return float|string
     */
    public function getFormattedPriceByStore(float $price)
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        return $this->pricingHelper->currencyByStore($price, $storeId, true, false);
    }
}
