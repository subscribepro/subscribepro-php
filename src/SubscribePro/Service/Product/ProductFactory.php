<?php

namespace SubscribePro\Service\Product;

use SubscribePro\Service\DataFactoryInterface;
use SubscribePro\Exception\InvalidArgumentException;

class ProductFactory implements DataFactoryInterface
{
    /**
     * @var string
     */
    protected $instanceName;

    /**
     * @param string $instanceName
     */
    public function __construct(
        $instanceName = '\SubscribePro\Service\Product\Product'
    ) {
        if (!is_subclass_of($instanceName, '\SubscribePro\Service\Product\ProductInterface')) {
            throw new InvalidArgumentException("{$instanceName} must implement \\SubscribePro\\Service\\Product\\ProductInterface.");
        }
        $this->instanceName = $instanceName;
    }

    /**
     * @param array $data
     * @return \SubscribePro\Service\Product\ProductInterface
     */
    public function create(array $data = [])
    {
        return new $this->instanceName($data);
    }
}
