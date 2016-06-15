<?php

namespace SubscribePro\Service\Webhook\Event;

use SubscribePro\Service\DataFactoryInterface;
use SubscribePro\Exception\InvalidArgumentException;

class DestinationFactory implements DataFactoryInterface
{
    /**
     * @var string
     */
    protected $instanceName;

    /**
     * @var string
     */
    protected $endpointInstanceName;

    /**
     * @param string $instanceName
     * @param string $endpointInstanceName
     */
    public function __construct(
        $instanceName = '\SubscribePro\Service\Webhook\Event\Destination',
        $endpointInstanceName = '\SubscribePro\Service\Webhook\Event\Destination\Endpoint'
    ) {
        if (!is_subclass_of($instanceName, '\SubscribePro\Service\Webhook\Event\DestinationInterface')) {
            throw new InvalidArgumentException("{$instanceName} must implement \\SubscribePro\\Service\\Webhook\\Event\\DestinationInterface.");
        }
        if (!is_subclass_of($endpointInstanceName, '\SubscribePro\Service\Webhook\Event\Destination\EndpointInterface')) {
            throw new InvalidArgumentException("{$endpointInstanceName} must implement \\SubscribePro\\Service\\Webhook\\Event\\Destination\\EndpointInterface.");
        }
        $this->instanceName = $instanceName;
        $this->endpointInstanceName = $endpointInstanceName;
    }

    /**
     * @param array $data
     * @return \SubscribePro\Service\Webhook\Event\DestinationInterface
     */
    public function create(array $data = [])
    {
        $endpointData = $this->getFieldData($data, DestinationInterface::ENDPOINT);
        $data[DestinationInterface::ENDPOINT] = new $this->endpointInstanceName($endpointData);
        
        return new $this->instanceName($data);
    }

    /**
     * @param array $data
     * @param string $field
     * @return array
     */
    protected function getFieldData($data, $field)
    {
        return !empty($data[$field]) ? $data[$field] : [];
    }
}
