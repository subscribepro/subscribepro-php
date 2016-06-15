<?php

namespace SubscribePro\Service\Webhook\Event;

use SubscribePro\Service\DataObject;

class Destination extends DataObject implements DestinationInterface
{
    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @param string|null $format
     * @return string
     */
    public function getLastAttempt($format = null)
    {
        return $this->getDatetimeData(self::LAST_ATTEMPT, $format);
    }

    /**
     * @return string|null
     */
    public function getLastErrorMessage()
    {
        return $this->getData(self::LAST_ERROR_MESSAGE);
    }

    /**
     * @return \SubscribePro\Service\Webhook\Event\Destination\EndpointInterface
     */
    public function getEndpoint()
    {
        return $this->getData(self::ENDPOINT);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        $data[self::ENDPOINT] = $this->getEndpoint()->toArray();
        return $data;
    }
}
