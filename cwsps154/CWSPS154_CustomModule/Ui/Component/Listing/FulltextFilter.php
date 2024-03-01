<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Ui\Component\Listing;

use CWSPS154\CustomModule\Api\Data\CustomModuleInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;

readonly class FulltextFilter implements AddFilterInterface
{
    /**
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        private FilterBuilder $filterBuilder
    ) {

    }

    /**
     * Adds custom filter to search criteria builder based on received filter.
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Filter $filter
     * @return void
     */
    public function addFilter(SearchCriteriaBuilder $searchCriteriaBuilder, Filter $filter)
    {
        $fieldFilter = $this->filterBuilder->setField(CustomModuleInterface::FIRST_NAME)
            ->setValue(sprintf('%%%s%%', $filter->getValue()))
            ->setConditionType('like')
            ->create();
        $searchCriteriaBuilder->addFilter($fieldFilter);
    }
}
