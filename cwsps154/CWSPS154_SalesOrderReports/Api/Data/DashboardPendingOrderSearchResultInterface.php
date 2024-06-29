<?php

namespace CWSPS154\SalesOrderReports\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface DashboardPendingOrderSearchResultInterface extends SearchResultsInterface
{
    /**
     * Get list
     *
     * @return \CWSPS154\SalesOrderReports\Api\Data\DashboardPendingOrderInterface[]
     */
    public function getItems();

    /**
     * Set list
     *
     * @param \CWSPS154\SalesOrderReports\Api\Data\DashboardPendingOrderInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
