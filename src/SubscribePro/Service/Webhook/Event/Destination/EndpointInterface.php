<?php

namespace SubscribePro\Service\Webhook\Event\Destination;

use SubscribePro\Service\DataInterface;

interface EndpointInterface extends DataInterface
{
    /**
     * Data fields
     */
    public const ID = 'id';
    public const STATUS = 'status';
    public const URL = 'url';
    public const ALL_SUBSCRIBED_EVENT_TYPES = 'all_subscribed_event_types';
    public const SUBSCRIBED_EVENT_TYPES = 'subscribed_event_types';
    public const CREATED = 'created';
    public const UPDATED = 'updated';

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return bool
     */
    public function getAllSubscribedEventTypes();

    /**
     * @return string
     */
    public function getSubscribedEventTypes();

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
