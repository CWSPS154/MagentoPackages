<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\CustomModule\Model\Export;

use CWSPS154\CustomModule\Api\Data\CustomModuleInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\ImportExport\Model\Export\AbstractEntity;
use CWSPS154\CustomModule\Model\ResourceModel\CustomModule\Collection;
use CWSPS154\CustomModule\Model\ResourceModel\CustomModule\CollectionFactory;
use Magento\ImportExport\Model\Export\Factory;
use Magento\ImportExport\Model\ResourceModel\CollectionByPagesIteratorFactory;
use Magento\Store\Model\StoreManagerInterface;

class CustomModule extends AbstractEntity
{
    public const ENTITY_TYPE = 'custom_module';

    /**#@+
     * Attribute collection name
     */
    const ATTRIBUTE_COLLECTION_NAME = AttributeCollectionProvider::class;

    /**
     * @var string[]
     */
    protected $_permanentAttributes = [
        CustomModuleInterface::FIRST_NAME,
        CustomModuleInterface::LAST_NAME
    ];

    /**
     * @var Collection
     */
    protected Collection $customCollection;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param Factory $collectionFactory
     * @param CollectionByPagesIteratorFactory $resourceColFactory
     * @param CollectionFactory $customCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\ImportExport\Model\Export\Factory $collectionFactory,
        \Magento\ImportExport\Model\ResourceModel\CollectionByPagesIteratorFactory $resourceColFactory,
        CollectionFactory $customCollectionFactory,
        array $data = []
    ) {
        $this->customCollection = $customCollectionFactory->create();
        parent::__construct($scopeConfig, $storeManager, $collectionFactory, $resourceColFactory, $data);
    }

    /**
     * @inheritDoc
     */
    public function export()
    {
        $writer = $this->getWriter();
        $writer->setHeaderCols($this->_getHeaderColumns());
        $this->_exportCollectionByPages($this->_getEntityCollection());
        return $writer->getContents();
    }

    /**
     * @inheritDoc
     */
    public function exportItem($item)
    {
        $row = [];
        $row[CustomModuleInterface::FIRST_NAME] = $item->getFirstName();
        $row[CustomModuleInterface::LAST_NAME] = $item->getLastName();
        $this->getWriter()->writeRow($row);
    }

    /**
     * @inheritDoc
     */
    public function getEntityTypeCode()
    {
        return self::ENTITY_TYPE;
    }

    /**
     * @inheritDoc
     */
    protected function _getHeaderColumns()
    {
        return $this->_permanentAttributes;
    }

    /**
     * @inheritDoc
     */
    protected function _getEntityCollection()
    {
        return $this->customCollection;
    }
}
