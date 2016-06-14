<?php

namespace SubscribePro\Service\Webhook;

use SubscribePro\Service\DataFactoryInterface;
use SubscribePro\Exception\InvalidArgumentException;

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
     * @var \SubscribePro\Service\DataFactoryInterface
     */
    protected $customerFactory;

    /**
     * @var \SubscribePro\Service\DataFactoryInterface
     */
    protected $subscriptionFactory;

    /**
     * @param \SubscribePro\Service\DataFactoryInterface $customerFactory
     * @param \SubscribePro\Service\DataFactoryInterface $subscriptionFactory
     * @param \SubscribePro\Service\DataFactoryInterface $destinationFactory
     * @param string $instanceName
     */
    public function __construct(
        \SubscribePro\Service\DataFactoryInterface $customerFactory,
        \SubscribePro\Service\DataFactoryInterface $subscriptionFactory,
        \SubscribePro\Service\DataFactoryInterface $destinationFactory,
        $instanceName = '\SubscribePro\Service\Webhook\Event'
    ) {
        if (!is_subclass_of($instanceName, '\SubscribePro\Service\Webhook\EventInterface')) {
            throw new InvalidArgumentException("{$instanceName} must implement \\SubscribePro\\Service\\Webhook\\EventInterface'.");
        }
        
        $this->instanceName = $instanceName;
        $this->destinationFactory = $destinationFactory;
        $this->customerFactory = $customerFactory;
        $this->subscriptionFactory = $subscriptionFactory;
    }

    /**
     * @param array $data
     * @return \SubscribePro\Service\Webhook\EventInterface
     */
    public function create(array $data = [])
    {
        $jsonData = $this->getJsonData($data, EventInterface::DATA);
        $customerData = $this->getFieldData($jsonData, EventInterface::CUSTOMER);
        $subscriptionData = $this->getFieldData($jsonData, EventInterface::SUBSCRIPTION);
        $destinationsData = $this->getFieldData($data, EventInterface::DESTINATIONS);
            
        $data[EventInterface::DESTINATIONS] = $this->createDestinationItems($destinationsData);
        $data[EventInterface::CUSTOMER] = $this->customerFactory->create($customerData);
        $data[EventInterface::SUBSCRIPTION] = $this->subscriptionFactory->create($subscriptionData);
        
        if (isset($data[EventInterface::DATA])) {
            unset($data[EventInterface::DATA]);
        }
        
        return new $this->instanceName($data);
    }

    /**
     * @param array $data
     * @param string $field
     * @return array
     */
    protected function getFieldData($data, $field)
    {
        return isset($data[$field]) && is_array($data[$field]) ? $data[$field] : [];
    }

    /**
     * @param array $data
     * @param string $field
     * @return array
     */
    protected function getJsonData($data, $field)
    {
        return isset($data[$field]) && is_string($data[$field]) ? json_decode($data[$field], true) : [];
    }

    /**
     * @param array $data
     * @return \SubscribePro\Service\Webhook\Event\DestinationInterface[]
     */
    protected function createDestinationItems(array $data = [])
    {
        return array_map(function ($itemData) {
            return $this->destinationFactory->create($itemData);
        }, $data);
    }
}
