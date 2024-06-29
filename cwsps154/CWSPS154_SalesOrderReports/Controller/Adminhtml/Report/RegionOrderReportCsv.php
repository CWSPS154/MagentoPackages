<?php

namespace CWSPS154\SalesOrderReports\Controller\Adminhtml\Report;

use CWSPS154\SalesOrderReports\Model\Config\Data;
use CWSPS154\SalesOrderReports\Model\Report\OrderRegionReport;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Psr\Log\LoggerInterface;

class RegionOrderReportCsv extends GenerateCSV
{
    /**
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param LoggerInterface $logger
     * @param OrderRegionReport $orderRegionReport
     * @param Data $data
     */
    public function __construct(
        protected readonly Action\Context    $context,
        protected readonly FileFactory       $fileFactory,
        protected readonly LoggerInterface   $logger,
        protected readonly OrderRegionReport $orderRegionReport,
        protected readonly Data              $data
    ) {
        parent::__construct($context);
    }

    /**
     * @return ResultInterface|ResponseInterface
     * @throws Exception
     */
    public function execute()
    {
        $storeId = $this->getRequest()->getParam('store', 0);
        $fileName = 'region_orders_' . date('d-m-Y_H-i-s') . '.csv';
        try {
            if ($this->data->isEnable($storeId)) {
                $result = $this->orderRegionReport->getOrderDetails($storeId);
                $this->headers = [
                    __('Region'),
                    __('Click & Collect Store No of orders'),
                    __('Click & Collect Store Value'),
                    __('Ware House No of orders'),
                    __('Ware House Value'),
                    __('Accessories No of orders'),
                    __('Accessories Value'),
                    __('Furniture Or Mixed No of orders'),
                    __('Furniture Or Mixed Value')
                ];
                $content = $this->generateCsvContent($result, 'region');
                $this->messageManager->addSuccessMessage(__('CSV Downloaded successfully.'));
            } else {
                $content = __('This feature is disabled for the selected store');
                $this->messageManager->addErrorMessage($content);
            }
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
            $content = $e->getMessage();
            $this->messageManager->addErrorMessage($content);
        }
        return $this->fileFactory->create(
            $fileName,
            $content,
            DirectoryList::VAR_DIR,
            'text/csv'
        );
    }
}
