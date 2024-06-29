<?php

namespace CWSPS154\SalesOrderReports\Model;

use CWSPS154\SalesOrderReports\Api\DashboardOrderRegionRepositoryInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderRegionInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderRegionSearchResultInterface;
use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderRegionSearchResultInterfaceFactory;
use CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardOrderRegion;
use CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardOrderRegion\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

class DashboardOrderRegionRepository implements DashboardOrderRegionRepositoryInterface
{
    /**
     * @param DashboardOrderRegion $dashboardOrderRegionResourceModel
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $collectionFactory
     * @param DashboardOrderRegionSearchResultInterfaceFactory $dashboardOrderRegionSearchResultInterfaceFactory
     */
    public function __construct(
        public readonly ResourceModel\DashboardOrderRegion                $dashboardOrderRegionResourceModel,
        private readonly CollectionProcessorInterface                     $collectionProcessor,
        private readonly CollectionFactory                                $collectionFactory,
        private readonly DashboardOrderRegionSearchResultInterfaceFactory $dashboardOrderRegionSearchResultInterfaceFactory
    ) {
    }

    /**
     * Retrieve a list of Report Order Region based on given search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return DashboardOrderRegionSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): DashboardOrderRegionSearchResultInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult = $this->dashboardOrderRegionSearchResultInterfaceFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * Save a Report Order Region Based Data.
     *
     * @param DashboardOrderRegionInterface $dashboardOrderRegion
     * @return DashboardOrderRegionInterface
     * @throws CouldNotSaveException
     * @throws LocalizedException
     * @throws AlreadyExistsException
     */
    public function save(DashboardOrderRegionInterface $dashboardOrderRegion): DashboardOrderRegionInterface
    {
        try {
            $this->dashboardOrderRegionResourceModel->save($dashboardOrderRegion);
        } catch (LocalizedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('The dashboard order report data could not be saved. Please try again. ' . $e->getMessage()),
                $e
            );
        }
        return $dashboardOrderRegion;
    }

    /**
     * Save multiple Report Order Region Based Data.
     *
     * @param array $dashboardOrderRegion
     * @return array
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function bulkSave(array $dashboardOrderRegion): array
    {
        try {
            $this->dashboardOrderRegionResourceModel->bulkSave($dashboardOrderRegion);
        } catch (LocalizedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('The dashboard order report data could not be saved. Please try again. ' . $e->getMessage()),
                $e
            );
        }
        return $dashboardOrderRegion;
    }

    /**
     * Truncate the Report Order Region Based table.
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function truncateTable(): bool
    {
        try {
            return $this->dashboardOrderRegionResourceModel->truncateTable();
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(
                __('The dashboard order report data could not be truncated. ' . $e->getMessage()),
                $e
            );
        }
    }
}
