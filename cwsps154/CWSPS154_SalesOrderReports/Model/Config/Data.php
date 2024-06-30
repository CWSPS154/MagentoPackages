<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

declare(strict_types=1);

namespace CWSPS154\SalesOrderReports\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data
{
    public const CONFIG_BASE_PATH = 'cwsps_salesorder_dashboard/general';

    /**
     * @param ScopeConfigInterface $storeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        public readonly ScopeConfigInterface $storeConfig,
        public readonly StoreManagerInterface $storeManager
    ) {
    }

    /**
     * get this feature is enable or not
     *
     * @param int|null $storeId
     * @return bool|null
     * @throws NoSuchEntityException
     */
    public function isEnable(int $storeId = null): ?bool
    {
        return (bool)$this->storeConfig->getValue(
            self::CONFIG_BASE_PATH.'/enable',
            ScopeInterface::SCOPE_STORE,
            $storeId ?? $this->storeManager->getStore()->getId()
        );
    }

    /**
     * get the active categories for this feature
     *
     * @param int|null $storeId
     * @return array
     * @throws NoSuchEntityException
     */
    public function getCategories(int $storeId = null):array
    {
        $categories = $this->storeConfig->getValue(
            self::CONFIG_BASE_PATH.'/categories',
            ScopeInterface::SCOPE_STORE,
            $storeId ?? $this->storeManager->getStore()->getId()
        );
        if (!empty($categories)) {
            return explode(',', $categories);
        }
        return [];
    }

    /**
     * get the active order status
     *
     * @param int|null $storeId
     * @return array
     * @throws NoSuchEntityException
     */
    public function getOrderStatus(int $storeId = null):array
    {
        $status = $this->storeConfig->getValue(
            self::CONFIG_BASE_PATH.'/order_export_report_statuses',
            ScopeInterface::SCOPE_STORE,
            $storeId ?? $this->storeManager->getStore()->getId()
        );
        if (!empty($status)) {
            return explode(',', $status);
        }
        return [];
    }
}
