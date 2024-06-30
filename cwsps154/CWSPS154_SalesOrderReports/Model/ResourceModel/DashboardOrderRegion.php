<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Model\ResourceModel;

class DashboardOrderRegion extends AbstractResource
{
    /**
     * @var string
     */
    protected $_mainTable = 'sales_order_report_region_wise_orders';

    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
}
