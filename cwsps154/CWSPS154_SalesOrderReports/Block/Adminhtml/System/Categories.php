<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Block\Adminhtml\System;

use CWSPS154\SalesOrderReports\Model\Config\Data;
use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class CategoryRating
 */
class Categories extends Field implements BlockInterface
{
    /**
     * @var array
     */
    protected $categoriesTree;

    /**
     * Category constructor.
     *
     * @param Context $context
     * @param CategoryCollectionFactory $collectionFactory
     * @param Data $configData
     * @param Json $json
     * @param array $data
     */
    public function __construct(
        protected readonly Context                   $context,
        protected readonly CategoryCollectionFactory $collectionFactory,
        protected readonly Data                      $configData,
        protected readonly Json                      $json,
        array                                        $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return bool|string
     * @throws LocalizedException
     */
    protected function getOptions(): bool|string
    {
        return $this->json->serialize($this->getCategoriesTree());
    }

    /**
     * get Active Category
     * @return array|mixed
     * @throws LocalizedException
     */
    protected function getCategoriesTree()
    {
        if ($this->categoriesTree === null) {
            $storeId = $this->context->getRequest()->getParam('store');
            $matchingNamesCollection = $this->collectionFactory->create();

            $matchingNamesCollection->addAttributeToSelect('path')
                ->addAttributeToFilter('entity_id', ['neq' => CategoryModel::TREE_ROOT_ID])
                ->setStoreId($storeId);

            $shownCategoriesIds = [];

            /** @var CategoryModel $category */
            foreach ($matchingNamesCollection as $category) {
                foreach (explode('/', $category->getPath()) as $parentId) {
                    $shownCategoriesIds[$parentId] = 1;
                }
            }

            $collection = $this->collectionFactory->create();

            $collection->addAttributeToFilter('entity_id', ['in' => array_keys($shownCategoriesIds)])
                ->addAttributeToSelect(['name', 'is_active', 'parent_id'])
                ->setStoreId($storeId);

            $categoryById = [
                CategoryModel::TREE_ROOT_ID => [
                    'value' => CategoryModel::TREE_ROOT_ID
                ],
            ];

            foreach ($collection as $category) {
                foreach ([$category->getId(), $category->getParentId()] as $categoryId) {
                    if (!isset($categoryById[$categoryId])) {
                        $categoryById[$categoryId] = ['value' => $categoryId];
                    }
                }
                if ($category->getIsActive()) {
                    $categoryById[$category->getId()]['is_active'] = $category->getIsActive();
                    $categoryById[$category->getId()]['label'] = $category->getName();
                    $categoryById[$category->getParentId()]['optgroup'][] = &$categoryById[$category->getId()];
                }

            }

            $this->categoriesTree = $categoryById[CategoryModel::TREE_ROOT_ID]['optgroup'];
        }
        return $this->categoriesTree;
    }

    /**
     * @return bool|string
     * @throws NoSuchEntityException
     */
    public function getValues(): bool|string
    {
        $storeId = $this->context->getRequest()->getParam('store');
        return $this->json->serialize($this->configData->getCategories($storeId));
    }

    /**
     * @param AbstractElement $element
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function _getElementHtml(AbstractElement $element): string
    {
        $html = '<div class="admin__field-control">';

        $html .= '<div id="cwsps_salesorder_reports_general_categories"  class="admin__field" data-bind="scope:\'general_categories\'" data-index="index">';
        $html .= '<!-- ko foreach: elems() -->';
        $html .= '<input class="input-text admin__control-text" type="text" name="groups[general][fields][categories][value][]" data-bind="value: value" style="display: none;"/>';
        $html .= '<!-- ko template: elementTmpl --><!-- /ko -->';
        $html .= '<!-- /ko -->';
        $html .= '</div>';
        $html .= $this->getScriptHtml().$this->getScripHtmlAddDisable();

        return $html;
    }

    /**
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getScriptHtml(): string
    {
        return '<script type="text/x-magento-init">
            {
                "#cwsps_salesorder_reports_general_categories": {
                    "Magento_Ui/js/core/app": {
                        "components": {
                            "general_categories": {
                                "component": "uiComponent",
                                "children": {
                                    "select_category": {
                                        "component": "Magento_Catalog/js/components/new-category",
                                        "config": {
                                            "filterOptions": true,
                                            "disableLabel": true,
                                            "chipsEnabled": true,
                                            "levelsVisibility": "1",
                                            "elementTmpl": "ui/grid/filters/elements/ui-select",
                                             "options": ' . $this->getOptions() . ',
                                            "value": ' . $this->getValues() . ',
                                            "listens": {
                                                "index=create_category:responseData": "setParsed",
                                                "newOption": "toggleOptionSelected"
                                            },
                                            "config": {
                                                "dataScope": "select_category",
                                                "sortOrder": 10
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        </script>';
    }

    /**
     * @return string
     */
    public function getScripHtmlAddDisable(): string
    {
        $script = <<<SCRIPT
        <script type="text/javascript">
            require([
                'jquery'
            ], function ($) {
               if ($('#cwsps_salesorder_reports_general_categories_inherit').attr('checked') === 'checked'){
                   let elState =  $('#cwsps_salesorder_reports_general_categories');
                    elState.parent().addClass('mp_custom_disabled_cursor');
                    elState.addClass('mp_custom_disabled');
               }
                $('#row_cwsps_salesorder_reports_general_categories .use-default').each(function() {
                  $(this).click(function() {
                      let el = $(this).parent();
                      if (!el.find(".admin__control-text.admin__action-multiselect-search").hasClass('disabled')){
                         el.find('.value .mp_custom_disabled_cursor').removeClass('mp_custom_disabled_cursor');
                         el.find('.value .mp_custom_disabled').removeClass('mp_custom_disabled');
                      }else {
                         el.find('.value .admin__field-control').addClass('mp_custom_disabled_cursor');
                         el.find('.value .admin__field').addClass('mp_custom_disabled');
                      }
                  })
                })

            });
        </script>
SCRIPT;

        return $script . $this->getHtmlStyle();
    }

    /**
     * @return string
     */
    public function getHtmlStyle(): string
    {
        return <<<EOF
        <style>
             #row_cwsps_salesorder_reports_general_categories .mp_custom_disabled{
                    opacity: 0.5;
                    pointer-events: none;
             }
              #row_cwsps_salesorder_reports_general_categories .mp_custom_disabled_cursor{
                    cursor: not-allowed;
             }
        </style>
EOF;
    }
}
