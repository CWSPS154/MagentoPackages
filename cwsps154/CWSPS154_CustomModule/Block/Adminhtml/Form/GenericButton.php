<?php
/**
 * Copyright CWSPS154. All rights reserved.
 */

namespace CWSPS154\CustomModule\Block\Adminhtml\Form;

use CWSPS154\CustomModule\Api\CustomModuleRepositoryInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    /**
     * @param Context $context
     * @param CustomModuleRepositoryInterface $customModuleRepository
     */
    public function __construct(
        private readonly Context                $context,
        private readonly CustomModuleRepositoryInterface $customModuleRepository
    ) {
    }

    /**
     * @return mixed|null
     */
    public function getCustomModuleId()
    {
        try {
            $entityId = $this->context->getRequest()->getParam('entity_id');
            if($entityId) {
                return $this->customModuleRepository->getById($entityId)->getId();
            }
        } catch (NoSuchEntityException|LocalizedException $e) {
            $this->context->getLogger()->error($e->getMessage(), $e->getTrace());
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return  string
     */
    public function getUrl(string $route = '', array $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
