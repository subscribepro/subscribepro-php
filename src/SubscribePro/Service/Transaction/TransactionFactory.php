<?php

namespace SubscribePro\Service\Transaction;

use SubscribePro\Service\DataFactoryInterface;
use SubscribePro\Exception\InvalidArgumentException;

class TransactionFactory implements DataFactoryInterface
{
    /**
     * @var string
     */
    protected $instanceName;

    /**
     * @param string $instanceName
     */
    public function __construct(
        $instanceName = '\SubscribePro\Service\Transaction\Transaction'
    ) {
        if (!is_subclass_of($instanceName, '\SubscribePro\Service\Transaction\TransactionInterface')) {
            throw new InvalidArgumentException("{$instanceName} must implement \\SubscribePro\\Service\\Transaction\\TransactionInterface.");
        }
        $this->instanceName = $instanceName;
    }

    /**
     * @param array $data
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     */
    public function create(array $data = [])
    {
        return new $this->instanceName($data);
    }
}
