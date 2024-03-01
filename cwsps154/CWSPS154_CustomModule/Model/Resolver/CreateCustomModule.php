<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Model\Resolver;

use CWSPS154\CustomModule\Api\CustomModuleRepositoryInterface;
use CWSPS154\CustomModule\Api\Data\CustomModuleInterfaceFactory;
use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class CreateCustomModule implements ResolverInterface
{

    public function __construct(
        private readonly CustomModuleInterfaceFactory    $customModuleInterfaceFactory,
        private readonly CustomModuleRepositoryInterface $customModuleRepository,
    ) {
    }

    /**
     * Fetches the data from persistence models and format it according to the GraphQL schema.
     *
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return mixed|Value
     * @throws Exception
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $data = $args['input'];
        $model = $this->customModuleInterfaceFactory->create();
        if (!empty($data['entity_id'])) {
            $model->setId($data['entity_id']);
        }
        $model->setFirstName($data['first_name']);
        $model->setLastName($data['last_name']);
        try {
            return $this->customModuleRepository->save($model);
        } catch (CouldNotSaveException $e) {
            return $e;
        }
    }
}
