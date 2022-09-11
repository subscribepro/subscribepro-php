<?php

namespace SubscribePro\Service\Webhook;

use SubscribePro\Service\DataInterface;

interface EventInterface extends DataInterface
{
    /**
     * Data fields
     */
    public const ID = 'id';
    public const TYPE = 'type';
    public const DATA = 'data';
    public const DESTINATIONS = 'destinations';
    public const CREATED = 'created';
    public const UPDATED = 'updated';

    /**
     * @param string|null $field
     *
     * @return mixed|null
     */
    public function getEventData($field = null);

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
     *
     * @return string
     */
    public function getCreated($format = null);

    /**
     * @param string|null $format
     *
     * @return string
     */
    public function getUpdated($format = null);
}
