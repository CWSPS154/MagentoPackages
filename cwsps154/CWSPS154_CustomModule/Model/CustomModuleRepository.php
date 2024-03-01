<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Model;

use CWSPS154\CustomModule\Api\CustomModuleRepositoryInterface;
use CWSPS154\CustomModule\Api\Data\CustomModuleInterface;
use CWSPS154\CustomModule\Api\Data\CustomModuleInterfaceFactory;
use CWSPS154\CustomModule\Api\Data\CustomModuleSearchResultsInterface;
use CWSPS154\CustomModule\Api\Data\CustomModuleSearchResultsInterfaceFactory;
use CWSPS154\CustomModule\Model\ResourceModel\CustomModule as ResourceModel;
use CWSPS154\CustomModule\Model\ResourceModel\CustomModule\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class CustomModuleRepository implements CustomModuleRepositoryInterface
{
    /**
     * @param CustomModuleInterfaceFactory $customModuleInterfaceFactory
     * @param CustomModuleSearchResultsInterfaceFactory $customModuleSearchResultsInterfaceFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ResourceModel $resourceModel
     */
    public function __construct(
        private readonly CustomModuleInterfaceFactory              $customModuleInterfaceFactory,
        private readonly CustomModuleSearchResultsInterfaceFactory $customModuleSearchResultsInterfaceFactory,
        private readonly ResourceModel\CollectionFactory           $collectionFactory,
        private readonly CollectionProcessorInterface              $collectionProcessor,
        private readonly ResourceModel                             $resourceModel
    ) {
    }

    /**
     * @param CustomModuleInterface $customModule
     * @return CustomModuleInterface
     * @throws LocalizedException
     * @throws CouldNotSaveException
     */
    public function save(CustomModuleInterface $customModule)
    {
        try {
            $this->resourceModel->save($customModule);
        } catch (LocalizedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('The custom module data was unable to be saved. Please try again.' . $e->getMessage()),
                $e
            );
        }
        return $customModule;
    }

    /**
     * @param int $entityId
     * @return CustomModuleInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getById(int $entityId)
    {
        $customModule = $this->customModuleInterfaceFactory->create();
        $this->resourceModel->load($customModule, $entityId);
        if (!$customModule->getId()) {
            throw new NoSuchEntityException(
                __(
                    sprintf("The custom module data with '%s' ID doesn't exist.", $entityId)
                )
            );
        }
        return $customModule;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return CustomModuleSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult = $this->customModuleSearchResultsInterfaceFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * @param CustomModuleInterface $customModule
     * @return bool
     * @throws LocalizedException
     * @throws CouldNotDeleteException
     */
    public function delete(CustomModuleInterface $customModule)
    {
        try {
            $this->resourceModel->delete($customModule);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the custom module data: %1', $exception->getMessage())
            );
        }
        return true;
    }

    /**
     * @param int $entityId
     * @return bool
     * @throws LocalizedException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $entityId)
    {
        return $this->delete($this->getById($entityId));
    }
}
