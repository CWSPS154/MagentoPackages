<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Controller\Adminhtml\Report;

use CWSPS154\SalesOrderReports\Block\Adminhtml\Report\OrderRegionReportGrid;
use CWSPS154\SalesOrderReports\Block\Adminhtml\Report\OrderStatusReportGrid;
use CWSPS154\SalesOrderReports\Block\Adminhtml\Report\PendingOrderReportGrid;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Dompdf\Dompdf;

class GeneratePdfFile extends Action implements HttpGetActionInterface
{
    /**
     * @param Context $context
     * @param FileFactory $factory
     */
    public function __construct(
        protected readonly Context     $context,
        protected readonly FileFactory $factory
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResponseInterface|string
     */
    public function execute()
    {
        $content = $this->_view->getLayout()
            ->createBlock(PendingOrderReportGrid::class)
            ->setTemplate('CWSPS154_SalesOrderReports::dashboard/pending_order_report.phtml')
            ->toHtml();
        $content .= $this->_view->getLayout()
            ->createBlock(OrderStatusReportGrid::class)
            ->setTemplate('CWSPS154_SalesOrderReports::dashboard/status_order_report.phtml')
            ->toHtml();
        $content .= $this->_view->getLayout()
            ->createBlock(OrderRegionReportGrid::class)
            ->setTemplate('CWSPS154_SalesOrderReports::dashboard/region_order_report.phtml')
            ->toHtml();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $content = $dompdf->output();

        $result = '';
        try {
            $result = $this->factory->create(
                'example.pdf',
                $content,
                DirectoryList::VAR_DIR,
                'application/pdf'
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $result;
    }
}
