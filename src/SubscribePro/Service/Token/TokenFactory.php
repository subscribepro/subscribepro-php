<?php

namespace SubscribePro\Service\Token;

use SubscribePro\Service\DataFactoryInterface;
use SubscribePro\Exception\InvalidArgumentException;

/**
 * @codeCoverageIgnore
 */
class TokenFactory implements DataFactoryInterface
{
    /**
     * @var \SubscribePro\Service\DataFactoryInterface
     */
    protected $addressFactory;

    /**
     * @var string
     */
    protected $instanceName;

    /**
     * @param \SubscribePro\Service\DataFactoryInterface $addressFactory
     * @param string $instanceName
     */
    public function __construct(
        \SubscribePro\Service\DataFactoryInterface $addressFactory,
        $instanceName = '\SubscribePro\Service\Token\Token'
    ) {
        if (!is_subclass_of($instanceName, '\SubscribePro\Service\Token\TokenInterface')) {
            throw new InvalidArgumentException("{$instanceName} must implement \\SubscribePro\\Service\\Token\\TokenInterface.");
        }
        $this->instanceName = $instanceName;
        $this->addressFactory = $addressFactory;
    }

    /**
     * @param array $data
     * @return \SubscribePro\Service\Token\TokenInterface
     */
    public function create(array $data = [])
    {
        $addressData = $this->getFieldData($data, TokenInterface::BILLING_ADDRESS);
        $data[TokenInterface::BILLING_ADDRESS] = $this->addressFactory->create($addressData);

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
