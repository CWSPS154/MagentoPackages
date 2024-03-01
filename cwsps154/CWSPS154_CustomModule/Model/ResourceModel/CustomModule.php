<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Model\ResourceModel;

use CWSPS154\CustomModule\Api\Data\CustomModuleInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Validator\NotEmpty;
use Magento\Framework\Validator\ValidateException;
use Magento\Framework\Validator\ValidatorChain;

class CustomModule extends AbstractDb
{
    /**
     * Table custom entity Const
     */
    public const TABLE_CUSTOM_MODULE = 'custom_entity_table';

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct(): void
    {
        $this->_init(self::TABLE_CUSTOM_MODULE, "entity_id");
    }

    /**
     * Perform actions before object save like validations
     *
     * @param AbstractModel $object
     * @return $this
     * @throws ValidateException
     * @throws InputException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        $exception = new InputException();
        foreach (CustomModuleInterface::REQUIRED_FIELD as $field) {
            $fieldValue = $object->getData($field);
            if ($fieldValue === null
                || !ValidatorChain::is(trim($fieldValue), NotEmpty::class)
            ) {
                $exception->addError(
                    __('"%fieldName" is required.', ['fieldName' => $field])
                );
            }
        }
        if ($exception->wasErrorAdded()) {
            throw $exception;
        }
        return parent::_beforeSave($object);
    }

//    /**
//     * Template method to return validate rules to be executed before entity is saved
//     *
//     * @return null
//     */
//    public function getValidationRulesBeforeSave()
//    {
//        return false;
//    }
}
