<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardOrderStatus;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use CWSPS154\SalesOrderReports\Model\DashboardOrderStatus as Model;
use CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardOrderStatus as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
