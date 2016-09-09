<?php

namespace SubscribePro\Service\Webhook;

use SubscribePro\Service\DataObject;
use SubscribePro\Service\Webhook\Event\DestinationInterface;

class Event extends DataObject implements EventInterface
{
    //@codeCoverageIgnoreStart

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

    //@codeCoverageIgnoreEnd

    /**
     * @param string|null $field
     * @return mixed|null
     */
    public function getEventData($field = null)
    {
        $data = $this->getData(self::DATA);
        if ($field && $data !== null) {
            return isset($data[$field]) ? $data[$field] : null;
        }

        return $data;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        $data[self::DESTINATIONS] = array_map(function (DestinationInterface $destination) {
            return $destination->toArray();
        }, $this->getDestinations());
        return $data;
    }
}
