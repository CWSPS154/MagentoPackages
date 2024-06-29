<?php

namespace CWSPS154\SalesOrderReports\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface DashboardOrderRegionSearchResultInterface extends SearchResultsInterface
{
    /**
     * Get list of order reports
     *
     * @return \CWSPS154\SalesOrderReports\Api\Data\DashboardOrderRegionInterface[]
     */
    public function getItems();

    /**
     * Set list of order reports
     *
     * @param \CWSPS154\SalesOrderReports\Api\Data\DashboardOrderRegionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
