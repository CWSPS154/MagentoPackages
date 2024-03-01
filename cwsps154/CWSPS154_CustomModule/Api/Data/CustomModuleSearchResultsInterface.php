<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface CustomModuleSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \CWSPS154\CustomModule\Api\Data\CustomModuleInterface[]
     */
    public function getItems();

    /**
     * @param \CWSPS154\CustomModule\Api\Data\CustomModuleInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
