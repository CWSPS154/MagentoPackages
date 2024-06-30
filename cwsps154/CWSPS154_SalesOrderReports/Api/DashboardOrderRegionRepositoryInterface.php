<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Api;

use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderRegionInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderRegionSearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

interface DashboardOrderRegionRepositoryInterface
{
    /**
     * Retrieve a list of Report Order Region based on given search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return DashboardOrderRegionSearchResultInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): DashboardOrderRegionSearchResultInterface;

    /**
     * Save a Report Order Region Based Data.
     *
     * @param DashboardOrderRegionInterface $dashboardOrderRegion
     * @return DashboardOrderRegionInterface
     * @throws CouldNotSaveException
     * @throws LocalizedException
     * @throws AlreadyExistsException
     */
    public function save(DashboardOrderRegionInterface $dashboardOrderRegion): DashboardOrderRegionInterface;

    /**
     * Save multiple Report Order Region Based Data.
     *
     * @param array $dashboardOrderRegion
     * @return array
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function bulkSave(array $dashboardOrderRegion): array;

    /**
     * Truncate the Report Order Region Based table.
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function truncateTable(): bool;
}
