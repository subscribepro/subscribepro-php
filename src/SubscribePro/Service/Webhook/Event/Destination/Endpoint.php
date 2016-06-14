<?php

namespace SubscribePro\Service\Webhook\Event\Destination;

use SubscribePro\Service\DataObject;

class Endpoint extends DataObject implements EndpointInterface
{
    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * @return bool
     */
    public function getAllSubscribedEventTypes()
    {
        return $this->getData(self::ALL_SUBSCRIBED_EVENT_TYPES);
    }

    /**
     * @return string
     */
    public function getSubscribedEventTypes()
    {
        return $this->getData(self::SUBSCRIBED_EVENT_TYPES);
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
}
