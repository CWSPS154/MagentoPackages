<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Model;

use CWSPS154\SalesOrderReports\Api\DashboardOrderStatusRepositoryInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderStatusInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderStatusSearchResultInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderStatusSearchResultInterfaceFactory;
use CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardOrderStatus;
use CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardOrderStatus\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

class DashboardOrderStatusRepository implements DashboardOrderStatusRepositoryInterface
{
    /**
     * @param DashboardOrderStatus $dashboardOrderStatus
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $collectionFactory
     * @param DashboardOrderStatusSearchResultInterfaceFactory $dashboardOrderStatusSearchResultInterfaceFactory
     */
    public function __construct(
        public readonly ResourceModel\DashboardOrderStatus                $dashboardOrderStatus,
        private readonly CollectionProcessorInterface                     $collectionProcessor,
        private readonly CollectionFactory                                $collectionFactory,
        private readonly DashboardOrderStatusSearchResultInterfaceFactory $dashboardOrderStatusSearchResultInterfaceFactory
    ) {
    }

    /**
     * Retrieve a list of Report Order Status based on given search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return DashboardOrderStatusSearchResultInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): DashboardOrderStatusSearchResultInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult = $this->dashboardOrderStatusSearchResultInterfaceFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * Save a Report Order Status Based Data.
     *
     * @param DashboardOrderStatusInterface $dashboardOrderStatus
     * @return DashboardOrderStatusInterface
     * @throws CouldNotSaveException
     * @throws LocalizedException
     * @throws AlreadyExistsException
     */
    public function save(DashboardOrderStatusInterface $dashboardOrderStatus): DashboardOrderStatusInterface
    {
        try {
            $this->dashboardOrderStatus->save($dashboardOrderStatus);
        } catch (LocalizedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('The dashboard order report data could not be saved. Please try again. ' . $e->getMessage()),
                $e
            );
        }
        return $dashboardOrderStatus;
    }

    /**
     * Save multiple Report Order Status Based Data.
     *
     * @param array $dashboardOrderStatus
     * @return array
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function bulkSave(array $dashboardOrderStatus): array
    {
        try {
            $this->dashboardOrderStatus->bulkSave($dashboardOrderStatus);
        } catch (LocalizedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('The dashboard order report data could not be saved. Please try again. ' . $e->getMessage()),
                $e
            );
        }
        return $dashboardOrderStatus;
    }

    /**
     * Truncate the Report Order Status Based table.
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function truncateTable(): bool
    {
        try {
            return $this->dashboardOrderStatus->truncateTable();
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(
                __('The dashboard order report data could not be truncated. ' . $e->getMessage()),
                $e
            );
        }
    }
}
