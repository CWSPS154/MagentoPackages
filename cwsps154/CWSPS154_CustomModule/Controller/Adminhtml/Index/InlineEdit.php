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
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class InlineEdit extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'CWSPS154_CustomModule::custom_module_save_edit';

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
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        /** @var ResultInterface $resultPage */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData(
                [
                    'messages' => [__('Please correct the data sent.')],
                    'error' => true,
                ]
            );
        }
        foreach (array_keys($postItems) as $customModuleId) {
            try {
                $customModule = $this->customModuleRepository->getById($customModuleId);
                $customModule->setFirstName($postItems[$customModuleId]['first_name']);
                $customModule->setLastName($postItems[$customModuleId]['last_name']);
                $this->customModuleRepository->save($customModule);
                $messages[] = __(sprintf('%s is saved successfully', $customModule->getFirstName()));
            } catch (CouldNotSaveException|NoSuchEntityException|LocalizedException $e) {
                $error = true;
                $messages[] = $e->getMessage();
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        }
        return $resultJson->setData(
            [
                'messages' => $messages,
                'error' => $error
            ]
        );
    }
}
