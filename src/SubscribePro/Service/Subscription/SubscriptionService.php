<?php

namespace SubscribePro\Service\Subscription;

use SubscribePro\Service\AbstractService;

/**
 * Config options for subscription service:
 * - instance_name
 *   Specified class must implement \SubscribePro\Service\Subscription\SubscriptionInterface interface
 *   Default value is \SubscribePro\Service\Subscription\Subscription
 *   @see \SubscribePro\Service\Subscription\SubscriptionInterface
 *
 * @method \SubscribePro\Service\Subscription\SubscriptionInterface retrieveItem($response, $entityName, \SubscribePro\Service\DataInterface $item = null)
 * @method \SubscribePro\Service\Subscription\SubscriptionInterface[] retrieveItems($response, $entitiesName)
 * @property \SubscribePro\Service\Subscription\SubscriptionFactory $dataFactory
 */
class SubscriptionService extends AbstractService
{
    /**
     * Service name
     */
    const NAME = 'subscription';

    const API_NAME_SUBSCRIPTION = 'subscription';
    const API_NAME_SUBSCRIPTIONS = 'subscriptions';

    /**
     * @param array $subscriptionData
     * @return \SubscribePro\Service\Subscription\SubscriptionInterface
     */
    public function createSubscription(array $subscriptionData = [])
    {
        return $this->dataFactory->create($subscriptionData);
    }

    /**
     * @param \SubscribePro\Service\Subscription\SubscriptionInterface $subscription
     * @return \SubscribePro\Service\Subscription\SubscriptionInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function saveSubscription(SubscriptionInterface $subscription)
    {
        $url = $subscription->isNew() ? '/services/v2/subscription.json' : "/services/v2/subscriptions/{$subscription->getId()}.json";
        $response = $this->httpClient->post($url, [self::API_NAME_SUBSCRIPTION => $subscription->getFormData()]);
        return $this->retrieveItem($response, self::API_NAME_SUBSCRIPTION, $subscription);
    }

    /**
     * @param int $subscriptionId
     * @return \SubscribePro\Service\Subscription\SubscriptionInterface
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadSubscription($subscriptionId)
    {
        $response = $this->httpClient->get("/services/v2/subscriptions/{$subscriptionId}.json");
        return $this->retrieveItem($response, self::API_NAME_SUBSCRIPTION);
    }

    /**
     * @param int|null $customerId
     * @return \SubscribePro\Service\Subscription\SubscriptionInterface[]
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadSubscriptions($customerId = null)
    {
        $items = [];
        
        do {
            $since_id = isset($lastItem) ? $lastItem->getId() + 1 : 0;
            $filters = $customerId ? [SubscriptionInterface::CUSTOMER_ID => $customerId, 'since_id' => $since_id] : [];
            $response = $this->httpClient->get('/services/v2/subscriptions.json', $filters);
            $responseItems = $this->retrieveItems($response, self::API_NAME_SUBSCRIPTIONS);
            $lastItem = end($responseItems);

            $items = array_merge($items, $responseItems);
        } while (count($responseItems) >= SubscriptionInterface::PAGINATION_DEFAULT);

        return $items;
    }

    /**
     * @param int $subscriptionId
     * @throws \SubscribePro\Exception\HttpException
     */
    public function cancelSubscription($subscriptionId)
    {
        $this->httpClient->post("/services/v2/subscriptions/{$subscriptionId}/cancel.json");
    }

    /**
     * @param int $subscriptionId
     * @throws \SubscribePro\Exception\HttpException
     */
    public function pauseSubscription($subscriptionId)
    {
        $this->httpClient->post("/services/v2/subscriptions/{$subscriptionId}/pause.json");
    }

    /**
     * @param int $subscriptionId
     * @throws \SubscribePro\Exception\HttpException
     */
    public function restartSubscription($subscriptionId)
    {
        $this->httpClient->post("/services/v2/subscriptions/{$subscriptionId}/restart.json");
    }

    /**
     * @param int $subscriptionId
     * @throws \SubscribePro\Exception\HttpException
     */
    public function skipSubscription($subscriptionId)
    {
        $this->httpClient->post("/services/v2/subscriptions/{$subscriptionId}/skip.json");
    }
}
