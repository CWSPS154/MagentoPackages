<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\CustomModule\Model\Export;

use Magento\Eav\Model\AttributeFactory;
use Magento\Framework\Data\Collection;
use Magento\Framework\Data\Collection\EntityFactoryInterface;

class AttributeCollectionProvider extends Collection
{
    /**
     * @var AttributeFactory
     */
    protected \Magento\Eav\Model\AttributeFactory $_attributeFactory;

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param \Magento\Eav\Model\AttributeFactory $attributeFactory
     * @throws \Exception
     */
    public function __construct(
        EntityFactoryInterface              $entityFactory,
        \Magento\Eav\Model\AttributeFactory $attributeFactory
    ) {
        $this->_attributeFactory = $attributeFactory;
        $this->addFilters();
        parent::__construct($entityFactory);
    }

    /**
     * Add empty filter
     *
     * @return void
     * @throws \Exception
     */
    private function addFilters()
    {
        $attributeData = [
            'attribute_id' => '',
            'attribute_code' => '',
            'frontend_label' => '',
            'backend_type' => null,
            'is_required' => false,
        ];
        $this->addItem(
            $this->_attributeFactory->createAttribute(
                \Magento\Eav\Model\Entity\Attribute::class,
                $attributeData
            )
        );
    }
}
