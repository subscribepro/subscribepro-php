<?php

namespace SubscribePro\Service\Token;

use SubscribePro\Service\DataFactoryInterface;
use SubscribePro\Exception\InvalidArgumentException;

class TokenFactory implements DataFactoryInterface
{
    /**
     * @var string
     */
    protected $instanceName;

    /**
     * @param string $instanceName
     */
    public function __construct(
        $instanceName = '\SubscribePro\Service\Token\Token'
    ) {
        if (!is_subclass_of($instanceName, '\SubscribePro\Service\Token\TokenInterface')) {
            throw new InvalidArgumentException("{$instanceName} must implement \\SubscribePro\\Service\\Token\\TokenInterface.");
        }
        $this->instanceName = $instanceName;
    }

    /**
     * @param array $data
     * @return \SubscribePro\Service\Token\TokenInterface
     */
    public function create(array $data = [])
    {
        return new $this->instanceName($data);
    }
}
