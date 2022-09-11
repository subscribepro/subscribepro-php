<?php

namespace SubscribePro\Service\Webhook\Event;

use SubscribePro\Service\DataInterface;

interface DestinationInterface extends DataInterface
{
    /**
     * Data fields
     */
    public const ID = 'id';
    public const STATUS = 'status';
    public const LAST_ATTEMPT = 'last_attempt';
    public const ENDPOINT = 'endpoint';
    public const LAST_ERROR_MESSAGE = 'last_error_message';

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string|null $format
     *
     * @return string
     */
    public function getLastAttempt($format = null);

    /**
     * @return string|null
     */
    public function getLastErrorMessage();

    /**
     * @return \SubscribePro\Service\Webhook\Event\Destination\EndpointInterface
     */
    public function getEndpoint();
}
