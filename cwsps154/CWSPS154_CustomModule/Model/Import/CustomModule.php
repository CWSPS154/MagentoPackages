<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Model\Import;

use CWSPS154\CustomModule\Api\Data\CustomModuleInterface;
use CWSPS154\CustomModule\Model\ResourceModel\CustomModule as ResourceModel;
use Magento\ImportExport\Model\Import\AbstractEntity;
use Magento\ImportExport\Model\Import;

class CustomModule extends AbstractEntity
{
    public const COLUMNS = [
        CustomModuleInterface::FIRST_NAME,
        CustomModuleInterface::LAST_NAME
    ];

    public const ENTITY_TYPE_CODE = 'custom_module';

    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = true;

    /**
     * The valid database columns
     *
     * @var string[]
     */
    protected $validColumnNames = self::COLUMNS;

    /**
     * Permanent entity columns.
     *
     * @var string[]
     */
    protected $_permanentAttributes = self::COLUMNS;

    /**
     * Row validation
     *
     * @param array $rowData
     * @param int $rowNumber
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function validateRow(array $rowData, $rowNumber): bool
    {
        foreach ($this->_permanentAttributes as $field => $column) {
            if (empty(trim($rowData[$column]))) {
                $this->addRowError(self::ERROR_CODE_COLUMN_NOT_FOUND, $rowNumber, $column);
                continue;
            }
        }
        if (isset($this->_validatedRows[$rowNumber])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNumber);
        }

        $this->_validatedRows[$rowNumber] = true;

        return !$this->getErrorAggregator()->isRowInvalid($rowNumber);
    }

    /**
     * @inheritDoc
     */
    protected function _importData()
    {
        if ($this->getBehavior() == Import::BEHAVIOR_ADD_UPDATE) {
            $this->addUpdate();
        }
        return true;
    }

    /**
     * Save and replace entities
     *
     * @return void
     */
    private function addUpdate(): void
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];
            $validColumnNames = $this->getValidColumnNames();
            foreach ($bunch as $rowNum => $row) {
                if (!$this->validateRow($row, $rowNum)) {
                    continue;
                }

                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);

                    continue;
                }
                $columnValues = [];

                foreach ($validColumnNames as $columnKey) {
                    $columnValues[$columnKey] = $row[$columnKey];
                }
            }
            $this->saveEntityFinish($entityList);
        }
    }

    /**
     * Save entities
     *
     * @param array $entityData
     *
     * @return void
     */
    private function saveEntityFinish(array $entityData): void
    {
        if ($entityData) {
            $tableName = $this->_connection->getTableName(ResourceModel::TABLE_CUSTOM_MODULE);
            $rows = [];

            foreach ($entityData as $entityRows) {
                foreach ($entityRows as $row) {
                    $rows[] = $row;
                }
            }

            if ($rows) {
                $this->_connection->insertOnDuplicate($tableName, $rows, $this->getValidColumnNames());
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getEntityTypeCode()
    {
        return self::ENTITY_TYPE_CODE;
    }
}
