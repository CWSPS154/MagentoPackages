<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Api\Data;

interface CustomModuleInterface
{
    public const ENTITY_ID = 'entity_id';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    public const REQUIRED_FIELD = [
        self::FIRST_NAME,
        self::LAST_NAME
    ];

    /**
     * Get ID
     *
     * @return int
     */
    public function getId();

    /**
     * @param $id
     * @return CustomModuleInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @param string $firstName
     * @return CustomModuleInterface
     */
    public function setFirstName(string $firstName): CustomModuleInterface;

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @param string $lastName
     * @return CustomModuleInterface
     */
    public function setLastName(string $lastName): CustomModuleInterface;

}
