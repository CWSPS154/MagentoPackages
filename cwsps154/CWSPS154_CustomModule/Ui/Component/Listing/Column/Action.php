<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Ui\Component\Listing\Column;

use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Action extends Column
{
    public const CUSTOM_MODULE_EDIT_PATH = 'custom_module/index/edit';
    public const CUSTOM_MODULE_DELETE_PATH = 'custom_module/index/delete';

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Escaper $escaper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface         $context,
        UiComponentFactory       $uiComponentFactory,
        private readonly Escaper $escaper,
        array                    $components = [],
        array                    $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item['entity_id'])) {
                    $item[$name]['view'] = [
                        'href' => $this->context->getUrl(self::CUSTOM_MODULE_EDIT_PATH, ['entity_id' => $item['entity_id']]),
                        'label' => __('Edit')
                    ];
                    $firstName = $this->escaper->escapeHtml($item['first_name']);
                    $item[$name]['delete'] = [
                        'href' => $this->context->getUrl(self::CUSTOM_MODULE_DELETE_PATH, ['entity_id' => $item['entity_id']]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete "%1"', $firstName),
                            'message' => __('Are you sure you want to delete the "%1" record?', $firstName),
                        ],
                        'post' => true,
                    ];
                }
            }
        }
        return $dataSource;
    }
}
