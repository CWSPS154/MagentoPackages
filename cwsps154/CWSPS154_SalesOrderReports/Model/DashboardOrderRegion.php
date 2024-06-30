<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Model;

use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderRegionInterface;
use CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardOrderRegion as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class DashboardOrderRegion extends AbstractModel implements DashboardOrderRegionInterface
{
    /**
     * Initialize model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->_getData(self::ENTITY_ID);
    }


    /**
     * @param $entityId
     * @return $this|DashboardOrderRegion
     */
    public function setEntityId($entityId): DashboardOrderRegion|static
    {
        $this->setData(self::ENTITY_ID, $entityId);
        return $this;
    }

    /**
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->_getData(self::STORE_ID);
    }

    /**
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): static
    {
        $this->setData(self::STORE_ID, $storeId);
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->_getData(self::TYPE);
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): static
    {
        $this->setData(self::TYPE, $type);
        return $this;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->_getData(self::REGION);
    }

    /**
     * @param string $region
     * @return $this
     */
    public function setRegion(string $region): static
    {
        $this->setData(self::REGION, $region);
        return $this;
    }

    /**
     * @return int
     */
    public function getNoOfOrders(): int
    {
        return (int)$this->_getData(self::NO_OF_ORDERS);
    }

    /**
     * @param int $noOfOrders
     * @return $this
     */
    public function setNoOfOrders(int $noOfOrders): static
    {
        $this->setData(self::NO_OF_ORDERS, $noOfOrders);
        return $this;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return (float)$this->_getData(self::VALUE);
    }

    /**
     * @param float $value
     * @return static
     */
    public function setValue(float $value): static
    {
        $this->setData(self::VALUE, $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->_getData(self::CREATED_AT);
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): static
    {
        $this->setData(self::CREATED_AT, $createdAt);
        return $this;
    }
}
