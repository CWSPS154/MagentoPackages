<?php

namespace CWSPS154\SalesOrderReports\Api;

use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderStatusInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderStatusSearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

interface DashboardOrderStatusRepositoryInterface
{
    /**
     * Retrieve a list of Report Order Status based on given search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return DashboardOrderStatusSearchResultInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): DashboardOrderStatusSearchResultInterface;

    /**
     * Save a Report Order Status Based Data.
     *
     * @param DashboardOrderStatusInterface $dashboardOrderStatus
     * @return DashboardOrderStatusInterface
     * @throws CouldNotSaveException
     * @throws LocalizedException
     * @throws AlreadyExistsException
     */
    public function save(DashboardOrderStatusInterface $dashboardOrderStatus): DashboardOrderStatusInterface;

    /**
     * Save multiple Report Order Status Based Data.
     *
     * @param array $dashboardOrderStatus
     * @return array
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function bulkSave(array $dashboardOrderStatus): array;

    /**
     * Truncate the Report Order Status Based table.
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function truncateTable(): bool;
}
