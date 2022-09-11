<?php

namespace SubscribePro\Service\Webhook;

use SubscribePro\Exception\InvalidArgumentException;
use SubscribePro\Service\DataFactoryInterface;

/**
 * @codeCoverageIgnore
 */
class EventFactory implements DataFactoryInterface
{
    /**
     * @var string
     */
    protected $instanceName;

    /**
     * @var \SubscribePro\Service\DataFactoryInterface
     */
    protected $destinationFactory;

    /**
     * @param \SubscribePro\Service\DataFactoryInterface $destinationFactory
     * @param string                                     $instanceName
     */
    public function __construct(
        DataFactoryInterface $destinationFactory,
        $instanceName = '\SubscribePro\Service\Webhook\Event'
    ) {
        if (!is_subclass_of($instanceName, '\SubscribePro\Service\Webhook\EventInterface')) {
            throw new InvalidArgumentException("{$instanceName} must implement \\SubscribePro\\Service\\Webhook\\EventInterface'.");
        }

        $this->instanceName = $instanceName;
        $this->destinationFactory = $destinationFactory;
    }

    /**
     * @param array $data
     *
     * @return \SubscribePro\Service\Webhook\EventInterface
     */
    public function create(array $data = [])
    {
        $eventData = $this->getEventData($data);
        $destinationsData = $this->getFieldData($data, EventInterface::DESTINATIONS);

        $data[EventInterface::DESTINATIONS] = $this->createDestinationItems($destinationsData);
        $data[EventInterface::DATA] = $eventData;

        return new $this->instanceName($data);
    }

    /**
     * @param array  $data
     * @param string $field
     *
     * @return array
     */
    protected function getFieldData($data, $field)
    {
        return isset($data[$field]) && is_array($data[$field]) ? $data[$field] : [];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getEventData($data)
    {
        $eventData = $this->getJsonData($data, EventInterface::DATA);

        return is_array($eventData) ? $eventData : [];
    }

    /**
     * @param array  $data
     * @param string $field
     *
     * @return mixed
     */
    private function getJsonData($data, $field)
    {
        return isset($data[$field]) && is_string($data[$field]) ? json_decode($data[$field], true) : [];
    }

    /**
     * @param array $data
     *
     * @return \SubscribePro\Service\Webhook\Event\DestinationInterface[]
     */
    protected function createDestinationItems(array $data = [])
    {
        return array_map(function ($itemData) {
            return $this->destinationFactory->create($itemData);
        }, $data);
    }
}
