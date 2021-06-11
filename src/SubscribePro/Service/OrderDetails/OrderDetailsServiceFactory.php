<?php

namespace SubscribePro\Service\OrderDetails;

use SubscribePro\Service\AbstractService;
use SubscribePro\Service\AbstractServiceFactory;

/**
 * @codeCoverageIgnore
 */
class OrderDetailsServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SubscribePro\Service\AbstractService
     */
    public function create()
    {
        return new OrderDetailsService(
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
        return new OrderDetailsFactory();
    }
}
