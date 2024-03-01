<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Model;

use CWSPS154\CustomModule\Api\Data\CustomModuleInterface;
use Magento\Framework\Model\AbstractModel;

class CustomModule extends AbstractModel implements CustomModuleInterface
{
    /**
     * CustomModule cache tag
     */
    const CACHE_TAG = 'custom_module';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Set resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\CustomModule::class);
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->getData(self::FIRST_NAME);
    }

    /**
     * @param string $firstName
     * @return CustomModuleInterface
     */
    public function setFirstName(string $firstName): CustomModuleInterface
    {
        return $this->setData(self::FIRST_NAME, $firstName);
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->getData(self::LAST_NAME);
    }

    /**
     * @param string $lastName
     * @return CustomModuleInterface
     */
    public function setLastName(string $lastName): CustomModuleInterface
    {
        return $this->setData(self::LAST_NAME, $lastName);
    }
}
