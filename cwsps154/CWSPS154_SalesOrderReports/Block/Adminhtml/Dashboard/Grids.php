<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Block\Adminhtml\Dashboard;

use CWSPS154\SalesOrderReports\Block\Adminhtml\Report\OrderRegionReportGrid;
use CWSPS154\SalesOrderReports\Block\Adminhtml\Report\OrderStatusReportGrid;
use CWSPS154\SalesOrderReports\Block\Adminhtml\Report\PendingOrderReportGrid;
use CWSPS154\SalesOrderReports\Model\Config\Data;
use Exception;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\EncoderInterface;

class Grids extends \Magento\Backend\Block\Dashboard\Grids
{
    /**
     * @param Context $context
     * @param EncoderInterface $jsonEncoder
     * @param Session $authSession
     * @param Data $configData
     * @param array $data
     */
    public function __construct(
        protected readonly Context          $context,
        protected readonly EncoderInterface $jsonEncoder,
        protected readonly Session          $authSession,
        protected readonly Data             $configData,
        array                               $data = []
    ) {
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    /**
     * Prepare layout for dashboard bottom tabs
     *
     * @return $this
     * @throws LocalizedException
     * @throws Exception
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $storeId = (int)$this->getRequest()->getParam('store');
        if ($this->configData->isEnable($storeId)) {
            if ($this->configData->showInDashboard($storeId)) {
                $content = $this->getLayout()->createBlock(PendingOrderReportGrid::class)->toHtml();
                $content .= $this->getLayout()->createBlock(OrderStatusReportGrid::class)->toHtml();
                $content .= $this->getLayout()->createBlock(OrderRegionReportGrid::class)->toHtml();
                $this->addTab(
                    'sales_order_reports',
                    [
                        'label' => __('Order Reports'),
                        'content' => $content,
                        'class' => 'ajax'
                    ]
                );
            }
        }
        return $this;
    }
}
