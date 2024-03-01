<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Api;

use CWSPS154\CustomModule\Api\Data\CustomModuleInterface;
use CWSPS154\CustomModule\Api\Data\CustomModuleSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface CustomModuleRepositoryInterface
{
    /**
     * @param CustomModuleInterface $customModule
     * @return CustomModuleInterface
     * @throws LocalizedException
     * @throws CouldNotSaveException
     */
    public function save(CustomModuleInterface $customModule);

    /**
     * @param int $entityId
     * @return CustomModuleInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getById(int $entityId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return CustomModuleSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param CustomModuleInterface $customModule
     * @return bool
     * @throws LocalizedException
     * @throws CouldNotDeleteException
     */
    public function delete(CustomModuleInterface $customModule);

    /**
     * @param int $entityId
     * @return bool
     * @throws LocalizedException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $entityId);
}
