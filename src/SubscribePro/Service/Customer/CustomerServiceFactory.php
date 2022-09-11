<?php

namespace SubscribePro\Service\Customer;

use SubscribePro\Service\AbstractServiceFactory;

/**
 * @codeCoverageIgnore
 */
class CustomerServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SubscribePro\Service\AbstractService
     */
    public function create()
    {
        return new CustomerService(
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
        return new CustomerFactory(
            $this->getConfigValue(CustomerService::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\Customer\Customer')
        );
    }
}
