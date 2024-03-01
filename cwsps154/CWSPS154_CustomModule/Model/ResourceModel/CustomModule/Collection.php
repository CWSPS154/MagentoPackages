<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Model\ResourceModel\CustomModule;

use CWSPS154\CustomModule\Model\CustomModule;
use CWSPS154\CustomModule\Model\ResourceModel\CustomModule as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct(): void
    {
        $this->_init(
            CustomModule::class,
            ResourceModel::class
        );
    }
}
