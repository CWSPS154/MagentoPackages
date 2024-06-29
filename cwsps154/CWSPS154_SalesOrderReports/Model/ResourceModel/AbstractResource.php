<?php

namespace CWSPS154\SalesOrderReports\Model\ResourceModel;

use Exception;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AbstractResource extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init($this->_mainTable, $this->_idFieldName);
    }

    /**
     * Insert multiple raw of data
     *
     * @param array $data
     * @return true
     * @throws LocalizedException
     */
    public function bulkSave(array $data): bool
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();
        try {
            $table = $this->getMainTable();
            $connection->insertOnDuplicate($table, $data);
            $connection->commit();
        } catch (Exception $exception) {
            $connection->rollBack();
            throw new LocalizedException(__($exception->getMessage()), $exception);
        }
        return true;
    }

    /**
     * Truncate current table
     *
     * @return bool
     * @throws LocalizedException
     */
    public function truncateTable(): bool
    {
        $connection = $this->_resources->getConnection();
        try {
            $tableName = $this->getMainTable();
            $connection->truncateTable($tableName);
        } catch (Exception $exception) {
            throw new LocalizedException(__($exception->getMessage()), $exception);
        }
        return true;
    }
}
