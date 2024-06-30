<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Api\Data;

interface DashboardPendingOrderInterface
{
    public const ENTITY_ID = 'entity_id';
    public const STORE_ID = 'store_id';
    public const TYPE = 'type';
    public const AGEING = 'ageing';
    public const NO_OF_ORDERS = 'no_of_orders';
    public const VALUE = 'value';
    public const CREATED_AT = 'created_at';

    /**
     * @return int
     */
    public function getStoreId(): int;

    /**
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): static;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): static;

    /**
     * @return string
     */
    public function getAgeing(): string;

    /**
     * @param string $ageing
     * @return $this
     */
    public function setAgeing(string $ageing): static;

    /**
     * @return int
     */
    public function getNoOfOrders(): int;

    /**
     * @param int $noOfOrders
     * @return $this
     */
    public function setNoOfOrders(int $noOfOrders): static;

    /**
     * @return float
     */
    public function getValue(): float;

    /**
     * @param float $value
     * @return $this
     */
    public function setValue(float $value): static;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): static;
}
