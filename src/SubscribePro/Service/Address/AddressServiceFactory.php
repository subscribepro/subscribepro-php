<?php

namespace SubscribePro\Service\Address;

use SubscribePro\Service\AbstractServiceFactory;

class AddressServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SubscribePro\Service\AbstractService
     */
    public function create()
    {
        return new AddressService(
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
        return new AddressFactory(
            $this->getConfigValue(AddressService::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\Address\Address')
        );
    }
}
