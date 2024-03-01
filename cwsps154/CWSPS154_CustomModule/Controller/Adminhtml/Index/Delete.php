<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Controller\Adminhtml\Index;

use CWSPS154\CustomModule\Api\CustomModuleRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class Delete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'CWSPS154_CustomModule::custom_module_delete';

    /**
     * @param Context $context
     * @param CustomModuleRepositoryInterface $customModuleRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context                                          $context,
        private readonly CustomModuleRepositoryInterface $customModuleRepository,
        private readonly LoggerInterface                 $logger
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResponseInterface
     */
    public function execute()
    {
        $entity_id = $this->getRequest()->getParam('entity_id');
        if ($entity_id) {
            try {
                $this->customModuleRepository->deleteById($entity_id);
                $this->messageManager->addSuccessMessage(__('Custom Module Data is Deleted Successfully.'));
            } catch (CouldNotDeleteException|LocalizedException $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        } else {
            $this->messageManager->addErrorMessage(__('We can\'t find the custom data to delete.'));
        }

        return $this->_redirect('*/*');
    }
}
