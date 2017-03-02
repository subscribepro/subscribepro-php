<?php

namespace SubscribePro\Service\OauthToken;

use SubscribePro\Service\AbstractServiceFactory;

/**
 * @codeCoverageIgnore
 */
class OauthTokenServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SubscribePro\Service\AbstractService
     */
    public function create()
    {
        return new OauthTokenService(
            $this->httpClient,
            $this->createDataFactory(),
            $this->config
        );
    }

    /**
     * @return null
     */
    protected function createDataFactory()
    {
        return new OauthTokenFactory(
            $this->getConfigValue(OauthTokenService::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\OauthToken\OauthToken')
        );
    }
}

