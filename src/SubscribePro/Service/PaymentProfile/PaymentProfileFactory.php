<?php

namespace SubscribePro\Service\PaymentProfile;

use SubscribePro\Service\DataFactoryInterface;
use SubscribePro\Exception\InvalidArgumentException;

class PaymentProfileFactory implements DataFactoryInterface
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
        $instanceName = '\SubscribePro\Service\PaymentProfile\PaymentProfile'
    ) {
        if (!is_subclass_of($instanceName, '\SubscribePro\Service\PaymentProfile\PaymentProfileInterface')) {
            throw new InvalidArgumentException("{$instanceName} must implement \\SubscribePro\\Service\\PaymentProfile\\PaymentProfileInterface.");
        }
        $this->instanceName = $instanceName;
        $this->addressFactory = $addressFactory;
    }

    /**
     * @param array $data
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     */
    public function create(array $data = [])
    {
        $addressData = $this->getFieldData($data, PaymentProfileInterface::BILLING_ADDRESS);
        $data[PaymentProfileInterface::BILLING_ADDRESS] = $this->addressFactory->create($addressData);

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
