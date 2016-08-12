<?php

namespace SubscribePro\Service\Subscription;

use SubscribePro\Service\AbstractServiceFactory;
use SubscribePro\Service\Address\AddressService;
use SubscribePro\Service\PaymentProfile\PaymentProfileService;

/**
 * @codeCoverageIgnore
 */
class SubscriptionServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SubscribePro\Service\AbstractService
     */
    public function create()
    {
        return new SubscriptionService(
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
        return new SubscriptionFactory(
            $this->serviceFactoryResolver->getServiceFactory(AddressService::NAME)->createDataFactory(),
            $this->serviceFactoryResolver->getServiceFactory(PaymentProfileService::NAME)->createDataFactory(),
            $this->getConfigValue(SubscriptionService::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\Subscription\Subscription')
        );
    }
}
