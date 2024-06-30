<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Model;

use CWSPS154\SalesOrderReports\Api\Data\DashboardPendingOrderSearchResultInterface;
use Magento\Framework\Api\SearchResults;

class DashboardPendingOrderSearchResult extends SearchResults implements DashboardPendingOrderSearchResultInterface
{

}
