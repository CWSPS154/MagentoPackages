<?php
namespace CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardPendingOrder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use CWSPS154\SalesOrderReports\Model\DashboardPendingOrder as Model;
use CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardPendingOrder as ResourceModel;

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
