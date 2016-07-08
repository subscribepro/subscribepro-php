<?php

namespace SubscribePro\Service\Webhook;

use SubscribePro\Service\AbstractService;
use SubscribePro\Exception\HttpException;

/**
 * Config options for webhook service:
 * - instance_name
 *   Specified class must implement \SubscribePro\Service\Webhook\EventInterface interface
 *   Default value is \SubscribePro\Service\Webhook\Event
 *   @see \SubscribePro\Service\Webhook\EventInterface
 * - instance_name_destination
 *   Specified class must implement \SubscribePro\Service\Webhook\Event\DestinationInterface interface
 *   Default value is \SubscribePro\Service\Webhook\Event\Destination
 *   @see \SubscribePro\Service\Webhook\Event\DestinationInterface
 * - instance_name_endpoint
 *   Specified class must implement \SubscribePro\Service\Webhook\Event\Destination\EndpointInterface interface
 *   Default value is \SubscribePro\Service\Webhook\Event\Destination\Endpoint
 *   @see \SubscribePro\Service\Webhook\Event\Destination\EndpointInterface
 */
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
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadEvent($eventId)
    {
        $response = $this->httpClient->get("/services/v2/webhook-events/{$eventId}.json");
        return $this->retrieveItem($response, self::API_NAME_WEBHOOK_EVENT);
    }
}
