<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Model;

use CWSPS154\SalesOrderReports\Api\Data\DashboardPendingOrderInterface;
use Magento\Framework\Model\AbstractModel;

class DashboardPendingOrder extends AbstractModel implements DashboardPendingOrderInterface
{
    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = self::ENTITY_ID;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\DashboardPendingOrder::class);
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
    public function getAgeing(): string
    {
        return $this->_getData(self::AGEING);
    }

    /**
     * @param string $ageing
     * @return $this
     */
    public function setAgeing(string $ageing): static
    {
        $this->setData(self::AGEING, $ageing);
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
