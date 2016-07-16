<?php

namespace SubscribePro\Service\Token;

use SubscribePro\Service\AbstractServiceFactory;

class TokenServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SubscribePro\Service\AbstractService
     */
    public function create()
    {
        return new TokenService(
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
        return new TokenFactory(
            $this->getConfigValue(TokenService::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\Token\Token')
        );
    }
}
