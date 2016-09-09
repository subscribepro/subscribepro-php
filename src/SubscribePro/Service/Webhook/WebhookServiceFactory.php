<?php

namespace SubscribePro\Service\Webhook;

use SubscribePro\Service\AbstractServiceFactory;
use SubscribePro\Service\Webhook\Event\DestinationFactory;

/**
 * @codeCoverageIgnore
 */
class WebhookServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SubscribePro\Service\AbstractService
     */
    public function create()
    {
        return new WebhookService(
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
        $destinationFactory = new DestinationFactory(
            $this->getConfigValue(WebhookService::CONFIG_INSTANCE_NAME_DESTINATION, '\SubscribePro\Service\Webhook\Event\Destination'),
            $this->getConfigValue(WebhookService::CONFIG_INSTANCE_NAME_ENDPOINT, '\SubscribePro\Service\Webhook\Event\Destination\Endpoint')
        );
        
        return new EventFactory(
            $destinationFactory,
            $this->getConfigValue(WebhookService::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\Webhook\Event')
        );
    }
}
