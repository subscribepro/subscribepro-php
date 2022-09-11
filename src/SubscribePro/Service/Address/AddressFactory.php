<?php

namespace SubscribePro\Service\Address;

use SubscribePro\Exception\InvalidArgumentException;
use SubscribePro\Service\DataFactoryInterface;

/**
 * @codeCoverageIgnore
 */
class AddressFactory implements DataFactoryInterface
{
    /**
     * @var string
     */
    protected $instanceName;

    /**
     * @param string $instanceName
     */
    public function __construct(
        $instanceName = '\SubscribePro\Service\Address\Address'
    ) {
        if (!is_subclass_of($instanceName, '\SubscribePro\Service\Address\AddressInterface')) {
            throw new InvalidArgumentException("{$instanceName} must implement \\SubscribePro\\Service\\Address\\AddressInterface.");
        }
        $this->instanceName = $instanceName;
    }

    /**
     * @param array $data
     *
     * @return \SubscribePro\Service\Address\AddressInterface
     */
    public function create(array $data = [])
    {
        return new $this->instanceName($data);
    }
}
