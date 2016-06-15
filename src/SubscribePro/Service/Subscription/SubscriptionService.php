<?php

namespace SubscribePro\Service\Subscription;

use SubscribePro\Sdk;
use SubscribePro\Service\AbstractService;
use SubscribePro\Exception\InvalidArgumentException;

class SubscriptionService extends AbstractService
{
    /**
     * Service name
     */
    const NAME = 'subscription';

    const API_NAME_SUBSCRIPTION = 'subscription';
    const API_NAME_SUBSCRIPTIONS = 'subscriptions';

    /**
     * @param \SubscribePro\Sdk $sdk
     * @return \SubscribePro\Service\DataFactoryInterface
     */
    protected function createDataFactory(Sdk $sdk)
    {
        return new SubscriptionFactory(
            $sdk->getAddressService()->getDataFactory(),
            $sdk->getPaymentProfileService()->getDataFactory(),
            $this->getConfigValue(self::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\Subscription\Subscription')
        );
    }

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
     * @throws \SubscribePro\Exception\InvalidArgumentException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function saveSubscription(SubscriptionInterface $subscription)
    {
        if (!$subscription->isValid()) {
            throw new InvalidArgumentException('Not all required fields are set.');
        }

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
        $filters = $customerId ? [SubscriptionInterface::CUSTOMER_ID => $customerId] : [];
        $response = $this->httpClient->get('/services/v2/subscriptions.json', $filters);
        return $this->retrieveItems($response, self::API_NAME_SUBSCRIPTIONS);
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
