<?php

namespace SubscribePro\Service\SalesOrder;

use SubscribePro\Service\DataFactoryInterface;
use SubscribePro\Exception\InvalidArgumentException;

/**
 * @codeCoverageIgnore
 */
class SalesOrderFactory implements DataFactoryInterface
{
    /**
     * @var string
     */
    protected $instanceName;

    /**
     * @param string $instanceName
     */
    public function __construct(
        $instanceName = '\SubscribePro\Service\SalesOrder\SalesOrder'
    ) {
        if (!is_subclass_of($instanceName, '\SubscribePro\Service\SalesOrder\SalesOrderInterface')) {
            throw new InvalidArgumentException("{$instanceName} must implement \\SubscribePro\\Service\\SalesOrder\\SalesOrderInterface.");
        }
        $this->instanceName = $instanceName;
    }

    /**
     * @param array $data
     * @return \SubscribePro\Service\SalesOrder\SalesOrderInterface
     */
    public function create(array $data = [])
    {
        return new $this->instanceName($data);
    }

    /**
     * @param array $data
     * @param string $field
     * @return mixed[]
     */
    protected function getFieldData($data, $field)
    {
        return !empty($data[$field]) ? $data[$field] : [];
    }
}
