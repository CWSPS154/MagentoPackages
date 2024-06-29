<?php

namespace CWSPS154\SalesOrderReports\Model\ResourceModel;

class DashboardOrderStatus extends AbstractResource
{
    /**
     * @var string
     */
    protected $_mainTable = 'sales_order_report_status_wise_orders';

    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
}
