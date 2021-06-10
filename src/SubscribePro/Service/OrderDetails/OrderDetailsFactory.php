<?php

namespace SubscribePro\Service\OrderDetails;

use SubscribePro\Service\DataFactoryInterface;
use SubscribePro\Exception\InvalidArgumentException;

/**
 * @codeCoverageIgnore
 */
class OrderDetailsFactory implements DataFactoryInterface
{
    /**
     * @var string
     */
    protected $instanceName;

    /**
     * @param string $instanceName
     */
    public function __construct(
        $instanceName = '\SubscribePro\Service\OrderDetails\OrderDetails'
    ) {
        $this->instanceName = $instanceName;
    }

    /**
     * @param array $data
     * @return \SubscribePro\Service\Subscription\SubscriptionInterface
     */
    public function create(array $data = [])
    {
        return new $this->instanceName($data);
    }
}
