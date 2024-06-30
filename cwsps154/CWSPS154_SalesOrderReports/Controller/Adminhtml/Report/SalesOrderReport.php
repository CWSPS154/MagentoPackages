<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Controller\Adminhtml\Report;

use CWSPS154\SalesOrderReports\Model\Config\Data;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;

class SalesOrderReport extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'CWSPS154_SalesOrderReports::sales_order_reports';

    /**
     * @var string
     */
    protected string $pageTitle = 'Sales Order Report';

    /**
     * @param Context $context
     * @param Data $data
     */
    public function __construct(
        protected readonly Context $context,
        protected readonly Data    $data
    ) {
        parent::__construct($context);
    }

    /**
     * @return ResultInterface|Page
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $storeId = (int)$this->getRequest()->getParam('store');
        if ($this->data->isEnable($storeId)) {
            /** @var Page $resultPage */
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $resultPage->getConfig()->getTitle()->prepend(__($this->pageTitle));
            $resultPage->setActiveMenu('CWSPS154_CustomModule::cwsps_menu');
            $resultPage->addBreadcrumb(__('CWSPS154'), __('CWSPS154'));
            $resultPage->addBreadcrumb(__('Report'), __('Report'));
            return $resultPage;
        }
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $this->messageManager->addErrorMessage(__('This feature is disabled for the current store'));
        return $resultRedirect->setPath('admin/*/*');
    }
}
