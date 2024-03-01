<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Controller\Adminhtml\Index;

use CWSPS154\CustomModule\Api\CustomModuleRepositoryInterface;
use CWSPS154\CustomModule\Api\Data\CustomModuleInterface;
use CWSPS154\CustomModule\Api\Data\CustomModuleInterfaceFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Psr\Log\LoggerInterface;

class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'CWSPS154_CustomModule::custom_module_save';

    /**
     * @param Context $context
     * @param CustomModuleInterfaceFactory $customModuleInterfaceFactory
     * @param CustomModuleRepositoryInterface $customModuleRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context                                          $context,
        private readonly CustomModuleInterfaceFactory    $customModuleInterfaceFactory,
        private readonly CustomModuleRepositoryInterface $customModuleRepository,
        private readonly LoggerInterface                 $logger
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
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(__("Form key is Invalidate"));
            return $this->_redirect('*/*');
        }
        $data = $this->getRequest()->getPostValue();
        $model = $this->customModuleInterfaceFactory->create();
        if(!empty($data['entity_id'])){
            $model->setId($data['entity_id']);
        }
        $model->setFirstName($data['first_name']);
        $model->setLastName($data['last_name']);
        try {
            $this->customModuleRepository->save($model);
            $this->messageManager->addSuccessMessage(__('Custom Module Data is Saved Successfully.'));
            return $this->processResultRedirect($model);
        } catch (InputException|LocalizedException|CouldNotSaveException|Exception $e) {
            $this->handleException($e);
        }
        if ($model->getId()) {
            return $this->_redirect('*/*/edit', [
                'entity_id' => $model->getId(),
                '_current' => true,
            ]);
        }
        return $this->_redirect('*/*/new');
    }

    /**
     * Process result redirect based on request parameters
     *
     * @param CustomModuleInterface $model
     * @return ResponseInterface
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    private function processResultRedirect(CustomModuleInterface $model)
    {
        $backParam = $this->getRequest()->getParam('back');
        if ($backParam === 'duplicate') {
            $model->setId(null);
            $this->customModuleRepository->save($model);
            $this->messageManager->addSuccessMessage(__('You duplicated the custom module data.'));
        }
        if (($backParam === 'edit' || $backParam === 'duplicate') && $model->getId()) {
            return $this->_redirect('*/*/edit', [
                'entity_id' => $model->getId(),
                '_current' => true,
            ]);
        }

        return $this->_redirect('*/*');
    }

    /**
     * Handle exceptions during data saving
     *
     * @param Exception $exception
     * @return void
     */
    private function handleException(Exception $exception)
    {
        $messages = array_map(function($error) {
            return $error->getMessage();
        }, $exception->getErrors() ?: [$exception]);

        $errorMessage = implode(',', $messages);
        $this->logger->error($errorMessage, $exception->getTrace());
        $this->messageManager->addErrorMessage(__('Error(s) occurred: %1', $errorMessage));
    }
}
