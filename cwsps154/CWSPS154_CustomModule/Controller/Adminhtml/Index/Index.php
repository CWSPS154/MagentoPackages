<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'CWSPS154_CustomModule::custom_module';

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|Page
     */
    public function execute(): ResultInterface|Page
    {
        /** @var ResultInterface $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('CWSPS154_CustomModule::cwsps_menu');
        $resultPage->addBreadcrumb(__('Custom'), __('Custom'));
        $resultPage->addBreadcrumb(__('Custom Grid'), __('Custom Grid'));
        $resultPage->getConfig()->getTitle()->prepend(__('Custom Grid'));

        return $resultPage;
    }
}
