<?php

namespace SubscribePro\Service\SalesOrder;

use SubscribePro\Service\AbstractServiceFactory;
use SubscribePro\Service\Address\AddressService;
use SubscribePro\Service\PaymentProfile\PaymentProfileService;

/**
 * @codeCoverageIgnore
 */
class SalesOrderServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SubscribePro\Service\AbstractService
     */
    public function create()
    {
        return new SalesOrderService(
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
        return new SalesOrderFactory(
            $this->getConfigValue(SalesOrderService::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\SalesOrder\SalesOrder')
        );
    }
}
