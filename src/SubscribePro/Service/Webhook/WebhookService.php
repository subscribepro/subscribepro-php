<?php

namespace SubscribePro\Service\Webhook;

use SubscribePro\Sdk;
use SubscribePro\Service\AbstractService;
use SubscribePro\Exception\HttpException;
use SubscribePro\Service\Webhook\Event\DestinationFactory;

class WebhookService extends AbstractService
{
    /**
     * Service name
     */
    const NAME = 'webhook';
    
    const API_NAME_WEBHOOK_EVENT = 'webhook_event';

    const CONFIG_INSTANCE_NAME_DESTINATION = 'instance_name_destination';
    const CONFIG_INSTANCE_NAME_ENDPOINT = 'instance_name_endpoint';

    /**
     * @param \SubscribePro\Sdk $sdk
     * @return \SubscribePro\Service\DataFactoryInterface
     */
    protected function createDataFactory(Sdk $sdk)
    {
        $destinationFactory = new DestinationFactory(
            $this->getConfigValue(self::CONFIG_INSTANCE_NAME_DESTINATION, '\SubscribePro\Service\Webhook\Event\Destination'),
            $this->getConfigValue(self::CONFIG_INSTANCE_NAME_ENDPOINT, '\SubscribePro\Service\Webhook\Event\Destination\Endpoint')
        );
        return new EventFactory(
            $sdk->getCustomerService()->getDataFactory(),
            $sdk->getSubscriptionService()->getDataFactory(),
            $destinationFactory,
            $this->getConfigValue(self::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\Webhook\Event')
        );
    }

    /**
     * @return bool
     */
    public function ping()
    {
        try {
            $this->httpClient->post('/services/v2/webhook-test.json');
        } catch (HttpException $exception) {
            return false;
        }
        return true;
    }

    /**
     * @param int $eventId
     * @return \SubscribePro\Service\Webhook\EventInterface
     */
    public function loadEvent($eventId)
    {
        $response = $this->httpClient->get("/services/v2/webhook-events/{$eventId}.json");
        return $this->retrieveItem($response, self::API_NAME_WEBHOOK_EVENT);
    }
}
