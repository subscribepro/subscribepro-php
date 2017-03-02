<?php

namespace SubscribePro\Service\OauthToken;

use SubscribePro\Service\DataFactoryInterface;
use SubscribePro\Exception\InvalidArgumentException;

/**
 * @codeCoverageIgnore
 */
class OauthTokenFactory implements DataFactoryInterface
{
    /**
     * @var string
     */
    protected $instanceName;

    /**
     * @param string $instanceName
     */
    public function __construct(
        $instanceName = '\SubscribePro\Service\OauthToken\OauthToken'
    ) {
        if (!is_subclass_of($instanceName, '\SubscribePro\Service\OauthToken\OauthTokenInterface')) {
            throw new InvalidArgumentException("{$instanceName} must implement \\SubscribePro\\Service\\OauthToken\\OauthTokenInterface.");
        }
        $this->instanceName = $instanceName;
    }

    /**
     * @param array $data
     * @return \SubscribePro\Service\OauthToken\OauthTokenInterface
     */
    public function create(array $data = [])
    {
        return new $this->instanceName($data);
    }
}

