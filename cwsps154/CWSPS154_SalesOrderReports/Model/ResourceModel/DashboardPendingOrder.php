<?php

namespace CWSPS154\SalesOrderReports\Model\ResourceModel;

class DashboardPendingOrder extends AbstractResource
{
    /**
     * @var string
     */
    protected $_mainTable = 'sales_order_report_pending_orders';

    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
}
