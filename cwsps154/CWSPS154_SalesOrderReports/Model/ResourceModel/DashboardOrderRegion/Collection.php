<?php

namespace CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardOrderRegion;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use CWSPS154\SalesOrderReports\Model\DashboardOrderRegion as Model;
use CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardOrderRegion as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
