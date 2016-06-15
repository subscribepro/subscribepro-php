<?php

namespace SubscribePro\Service\Webhook;

use SubscribePro\Service\DataInterface;

interface EventInterface extends DataInterface
{
    /**
     * Data fields
     */
    const ID = 'id';
    const TYPE = 'type';
    const DATA = 'data';
    const CUSTOMER = 'customer';
    const SUBSCRIPTION = 'subscription';
    const DESTINATIONS = 'destinations';
    const CREATED = 'created';
    const UPDATED = 'updated';

    /**
     * @return \SubscribePro\Service\Customer\CustomerInterface
     */
    public function getCustomer();

    /**
     * @return \SubscribePro\Service\Subscription\SubscriptionInterface
     */
    public function getSubscription();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return \SubscribePro\Service\Webhook\Event\DestinationInterface[]
     */
    public function getDestinations();

    /**
     * @param string|null $format
     * @return string
     */
    public function getCreated($format = null);

    /**
     * @param string|null $format
     * @return string
     */
    public function getUpdated($format = null);
}
