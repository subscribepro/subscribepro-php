<?php

namespace SubscribePro\Service\Webhook;

use SubscribePro\Service\DataObject;
use SubscribePro\Service\Webhook\Event\DestinationInterface;

class Event extends DataObject implements EventInterface
{
    /**
     * @return \SubscribePro\Service\Customer\CustomerInterface
     */
    public function getCustomer()
    {
        return $this->getData(self::CUSTOMER);
    }

    /**
     * @return \SubscribePro\Service\Subscription\SubscriptionInterface
     */
    public function getSubscription()
    {
        return $this->getData(self::SUBSCRIPTION);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * @return \SubscribePro\Service\Webhook\Event\DestinationInterface[]
     */
    public function getDestinations()
    {
        return $this->getData(self::DESTINATIONS);
    }

    /**
     * @param string|null $format
     * @return string
     */
    public function getCreated($format = null)
    {
        return $this->getDatetimeData(self::CREATED, $format);
    }

    /**
     * @param string|null $format
     * @return string
     */
    public function getUpdated($format = null)
    {
        return $this->getDatetimeData(self::UPDATED, $format);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        $data[self::CUSTOMER] = $this->getCustomer()->toArray();
        $data[self::SUBSCRIPTION] = $this->getSubscription()->toArray();
        $data[self::DESTINATIONS] = array_map(function (DestinationInterface $destination) {
            return $destination->toArray();
        }, $this->getDestinations());
        return $data;
    }
}
