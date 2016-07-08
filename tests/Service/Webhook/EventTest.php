<?php

namespace SubscribePro\Tests\Service\Event;

use SubscribePro\Service\Address\Address;
use SubscribePro\Service\Customer\Customer;
use SubscribePro\Service\Customer\CustomerInterface;
use SubscribePro\Service\PaymentProfile\PaymentProfile;
use SubscribePro\Service\PaymentProfile\PaymentProfileInterface;
use SubscribePro\Service\Subscription\Subscription;
use SubscribePro\Service\Subscription\SubscriptionInterface;
use SubscribePro\Service\Webhook\Event;
use SubscribePro\Service\Webhook\Event\Destination;
use SubscribePro\Service\Webhook\Event\DestinationInterface;
use SubscribePro\Service\Webhook\Event\Destination\EndpointInterface;
use SubscribePro\Service\Webhook\Event\Destination\Endpoint;
use SubscribePro\Service\Webhook\EventInterface;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        $customerData = [
            CustomerInterface::ID => 123
        ];
        $subscriptionData = [
            SubscriptionInterface::QTY => 222,
            SubscriptionInterface::PAYMENT_PROFILE => [
                PaymentProfileInterface::BILLING_ADDRESS => []
            ],
            SubscriptionInterface::SHIPPING_ADDRESS => [],
            SubscriptionInterface::SHIPPING_ADDRESS_ID => null,
            SubscriptionInterface::PAYMENT_PROFILE_ID => null,
        ];
        $subscription = new Subscription([
            SubscriptionInterface::QTY => 222,
            SubscriptionInterface::PAYMENT_PROFILE => new PaymentProfile([
                PaymentProfileInterface::BILLING_ADDRESS => new Address() 
            ]),
            SubscriptionInterface::SHIPPING_ADDRESS => new Address()
        ]);
        $endPointData = [
            EndpointInterface::ID => 444
        ];
        $event = new Event([
            EventInterface::ID => 111,
            EventInterface::CUSTOMER => new Customer($customerData),
            EventInterface::SUBSCRIPTION => $subscription,
            EventInterface::DESTINATIONS => [new Destination([
                DestinationInterface::ENDPOINT => new Endpoint($endPointData)
            ])],
        ]);
        
        $expectedData = [
            EventInterface::ID => 111,
            EventInterface::CUSTOMER => $customerData,
            EventInterface::SUBSCRIPTION => $subscriptionData,
            EventInterface::DESTINATIONS => [
                [DestinationInterface::ENDPOINT => $endPointData]
            ],
        ];
        
        $this->assertEquals($expectedData, $event->toArray());
    }
}
