<?php

namespace CWSPS154\SalesOrderReports\Model;

use CWSPS154\SalesOrderReports\Model\ResourceModel\DashboardOrderStatus as ResourceModel;
use CWSPS154\SalesOrderReports\Api\Data\DashboardOrderStatusInterface;
use Magento\Framework\Model\AbstractModel;

class DashboardOrderStatus extends AbstractModel implements DashboardOrderStatusInterface
{
    /**
     * Initialize model
     *
     * @return void
     */
    protected function _construct()
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
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId): static
    {
        $this->setData(self::ENTITY_ID, $entityId);
        return $this;
    }

    /**
     * @return int
     */
    public function getStoreId():int
    {
        return $this->_getData(self::STORE_ID);
    }

    /**
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId):static
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
    public function getOrderStatus(): string
    {
        return $this->_getData(self::ORDER_STATUS);
    }

    /**
     * @param string $orderStatus
     * @return $this
     */
    public function setOrderStatus(string $orderStatus): static
    {
        $this->setData(self::ORDER_STATUS, $orderStatus);
        return $this;
    }

    /**
     * @return int
     */
    public function getNoOfOrders(): int
    {
        return $this->_getData(self::NO_OF_ORDERS);
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
        return $this->_getData(self::VALUE);
    }

    /**
     * @param float $value
     * @return $this
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
