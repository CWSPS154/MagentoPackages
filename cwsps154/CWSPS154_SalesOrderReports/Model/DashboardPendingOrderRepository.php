<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Model;

use CWSPS154\SalesOrderReports\Api\DashboardPendingOrderRepositoryInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardPendingOrderInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardPendingOrderSearchResultInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardPendingOrderSearchResultInterfaceFactory;
use CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardPendingOrder;
use CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardPendingOrder\CollectionFactory;
use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

class DashboardPendingOrderRepository implements DashboardPendingOrderRepositoryInterface
{
    /**
     * @param DashboardPendingOrder $dashboardPendingOrder
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $collectionFactory
     * @param DashboardPendingOrderSearchResultInterfaceFactory $dashboardPendingOrderSearchResultInterfaceFactory
     */
    public function __construct(
        public readonly DashboardPendingOrder              $dashboardPendingOrder,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly CollectionFactory           $collectionFactory,
        private readonly DashboardPendingOrderSearchResultInterfaceFactory $dashboardPendingOrderSearchResultInterfaceFactory,
    ) {
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return DashboardPendingOrderSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): DashboardPendingOrderSearchResultInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult = $this->dashboardPendingOrderSearchResultInterfaceFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * Save a Report Order Pending Status Based Data.
     *
     * @param DashboardPendingOrderInterface $dashboardPendingOrder
     * @return DashboardPendingOrderInterface
     * @throws CouldNotSaveException
     * @throws LocalizedException
     * @throws AlreadyExistsException
     */
    public function save(DashboardPendingOrderInterface $dashboardPendingOrder): DashboardPendingOrderInterface
    {
        try {
            $this->dashboardPendingOrder->save($dashboardPendingOrder);
        } catch (LocalizedException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new CouldNotSaveException(
                __('The custom module data was unable to be saved. Please try again. ' . $e->getMessage()),
                $e
            );
        }
        return $dashboardPendingOrder;
    }

    /**
     * Save multiple Report Order Pending Status Based Data.
     *
     * @param array $dashboardPendingOrders
     * @return array
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function bulkSave(array $dashboardPendingOrders): array
    {
        try {
            $this->dashboardPendingOrder->bulkSave($dashboardPendingOrders);
        } catch (LocalizedException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new CouldNotSaveException(
                __('The custom module data was unable to be saved. Please try again. ' . $e->getMessage()),
                $e
            );
        }
        return $dashboardPendingOrders;
    }

    /**
     * Truncate the Report Order Pending Status Based table.
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function truncateTable(): bool
    {
        try {
            return $this->dashboardPendingOrder->truncateTable();
        } catch (Exception $e) {
            throw new CouldNotDeleteException(
                __('The custom module data was not able to be truncated. '.$e->getMessage()),
                $e
            );
        }
    }
}
