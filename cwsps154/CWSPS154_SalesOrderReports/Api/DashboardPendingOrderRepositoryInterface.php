<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Api;

use CWSPS154\SalesOrderReports\Api\Data\DashboardPendingOrderInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardPendingOrderSearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

interface DashboardPendingOrderRepositoryInterface
{
    /**
     * Retrieve a list of Report Order Pending Status based on given search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return DashboardPendingOrderSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): DashboardPendingOrderSearchResultInterface;

    /**
     * Save a Report Order Pending Status Based Data.
     *
     * @param DashboardPendingOrderInterface $dashboardPendingOrder
     * @return DashboardPendingOrderInterface
     * @throws CouldNotSaveException
     * @throws LocalizedException
     * @throws AlreadyExistsException
     */
    public function save(DashboardPendingOrderInterface $dashboardPendingOrder): DashboardPendingOrderInterface;

    /**
     * Save multiple Report Order Pending Status Based Data.
     *
     * @param array $dashboardPendingOrders
     * @return array
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function bulkSave(array $dashboardPendingOrders): array;

    /**
     * Truncate the Report Order Pending Status Based table.
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function truncateTable(): bool;
}
