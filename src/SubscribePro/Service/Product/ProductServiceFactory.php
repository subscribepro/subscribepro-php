<?php

namespace SubscribePro\Service\Product;

use SubscribePro\Service\AbstractServiceFactory;

/**
 * @codeCoverageIgnore
 */
class ProductServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SubscribePro\Service\AbstractService
     */
    public function create()
    {
        return new ProductService(
            $this->httpClient,
            $this->createDataFactory(),
            $this->config
        );
    }

    /**
     * @return \SubscribePro\Service\DataFactoryInterface
     */
    protected function createDataFactory()
    {
        return new ProductFactory(
            $this->getConfigValue(ProductService::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\Product\Product')
        );
    }
}
