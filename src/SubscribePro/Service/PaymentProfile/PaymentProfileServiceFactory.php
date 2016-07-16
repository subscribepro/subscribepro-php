<?php

namespace SubscribePro\Service\PaymentProfile;

use SubscribePro\Service\AbstractServiceFactory;
use SubscribePro\Service\Address\AddressService;

class PaymentProfileServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SubscribePro\Service\AbstractService
     */
    public function create()
    {
        return new PaymentProfileService(
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
        return new PaymentProfileFactory(
            $this->serviceFactoryResolver->getServiceFactory(AddressService::NAME)->createDataFactory(),
            $this->getConfigValue(PaymentProfileService::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\PaymentProfile\PaymentProfile')
        );
    }
}
