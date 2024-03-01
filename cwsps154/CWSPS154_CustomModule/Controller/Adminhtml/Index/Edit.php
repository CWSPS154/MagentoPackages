<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Controller\Adminhtml\Index;

use CWSPS154\CustomModule\Api\CustomModuleRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;

class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'CWSPS154_CustomModule::custom_module_save';
    public const ADMIN_RESOURCE_EDIT = 'CWSPS154_CustomModule::custom_module_save_edit';

    /**
     * @param Context $context
     * @param CustomModuleRepositoryInterface $customModuleRepository
     */
    public function __construct(
        Context                                          $context,
        private readonly CustomModuleRepositoryInterface $customModuleRepository,
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $entity_id = $this->getRequest()->getParam('entity_id');
        if ($entity_id) {
            try {
                $entity_id = $this->customModuleRepository->getById($entity_id)->getId();
            } catch (NoSuchEntityException|LocalizedException $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
                return $this->_redirect('*/*');
            }
        }

        /** @var ResultInterface $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('CWSPS154_CustomModule::cwsps_menu');
        $resultPage->addBreadcrumb(__('Custom'), __('Custom'));
        $resultPage->addBreadcrumb(__('Custom Grid'), __('Custom Grid'));
        $resultPage->addBreadcrumb(
            $entity_id ? __('Edit') : __('New'),
            $entity_id ? __('Edit') : __('New')
        );
        $resultPage->getConfig()->getTitle()->prepend(
            $entity_id ? __('Edit') : __('New')
        );

        return $resultPage;
    }

    /**
     * Determines whether current user is allowed to access Action
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        if ($this->getRequest()->getParam('entity_id')) {
            return $this->_authorization->isAllowed(self::ADMIN_RESOURCE_EDIT);
        }
        return parent::_isAllowed();
    }
}
