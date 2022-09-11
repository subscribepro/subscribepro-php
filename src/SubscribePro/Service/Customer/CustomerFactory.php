<?php

namespace SubscribePro\Service\Customer;

use SubscribePro\Exception\InvalidArgumentException;
use SubscribePro\Service\DataFactoryInterface;

/**
 * @codeCoverageIgnore
 */
class CustomerFactory implements DataFactoryInterface
{
    /**
     * @var string
     */
    protected $instanceName;

    /**
     * @param string $instanceName
     */
    public function __construct(
        $instanceName = '\SubscribePro\Service\Customer\Customer'
    ) {
        if (!is_subclass_of($instanceName, '\SubscribePro\Service\Customer\CustomerInterface')) {
            throw new InvalidArgumentException("{$instanceName} must implement \\SubscribePro\\Service\\Customer\\CustomerInterface.");
        }
        $this->instanceName = $instanceName;
    }

    /**
     * @param array $data
     *
     * @return \SubscribePro\Service\Customer\CustomerInterface
     */
    public function create(array $data = [])
    {
        return new $this->instanceName($data);
    }
}
