<?php

namespace SubscribePro\Service\Subscription;

use SubscribePro\Service\DataFactoryInterface;
use SubscribePro\Exception\InvalidArgumentException;

class SubscriptionFactory implements DataFactoryInterface
{
    /**
     * @var \SubscribePro\Service\DataFactoryInterface
     */
    protected $addressFactory;

    /**
     * @var \SubscribePro\Service\DataFactoryInterface
     */
    protected $paymentProfileFactory;

    /**
     * @var string
     */
    protected $instanceName;

    /**
     * @param \SubscribePro\Service\DataFactoryInterface $addressFactory
     * @param \SubscribePro\Service\DataFactoryInterface $paymentProfileFactory
     * @param string $instanceName
     */
    public function __construct(
        \SubscribePro\Service\DataFactoryInterface $addressFactory,
        \SubscribePro\Service\DataFactoryInterface $paymentProfileFactory,
        $instanceName = '\SubscribePro\Service\Subscription\Subscription'
    ) {
        if (!is_subclass_of($instanceName, '\SubscribePro\Service\Subscription\SubscriptionInterface')) {
            throw new InvalidArgumentException("{$instanceName} must implement \\SubscribePro\\Service\\Subscription\\SubscriptionInterface.");
        }
        $this->instanceName = $instanceName;
        $this->addressFactory = $addressFactory;
        $this->paymentProfileFactory = $paymentProfileFactory;
    }

    /**
     * @param array $data
     * @return \SubscribePro\Service\Subscription\SubscriptionInterface
     */
    public function create(array $data = [])
    {
        $addressData = $this->getFieldData($data, SubscriptionInterface::SHIPPING_ADDRESS);
        $data[SubscriptionInterface::SHIPPING_ADDRESS] = $this->addressFactory->create($addressData);

        $paymentProfileData = $this->getFieldData($data, SubscriptionInterface::PAYMENT_PROFILE);
        $data[SubscriptionInterface::PAYMENT_PROFILE] = $this->paymentProfileFactory->create($paymentProfileData);

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
