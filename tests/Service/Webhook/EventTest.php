<?php

namespace SubscribePro\Tests\Service\Event;

use SubscribePro\Service\Webhook\Event;
use SubscribePro\Service\Webhook\EventInterface;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        $destination1Mock = $this->getMockBuilder('SubscribePro\Service\Webhook\Event\DestinationInterface')->getMock();
        $destination1Mock->expects($this->once())
            ->method('toArray')
            ->willReturn(['first destination data']);

        $destination2Mock = $this->getMockBuilder('SubscribePro\Service\Webhook\Event\DestinationInterface')->getMock();
        $destination2Mock->expects($this->once())
            ->method('toArray')
            ->willReturn(['second destination data']);

        $customerMock = $this->getMockBuilder('SubscribePro\Service\Customer\CustomerInterface')->getMock();
        $customerMock->expects($this->once())
            ->method('toArray')
            ->willReturn(['customer data']);

        $subscriptionMock = $this->getMockBuilder('SubscribePro\Service\Subscription\SubscriptionInterface')->getMock();
        $subscriptionMock->expects($this->once())
            ->method('toArray')
            ->willReturn(['subscription data']);

        $event = new Event([
            EventInterface::ID => 111,
            EventInterface::CUSTOMER => $customerMock,
            EventInterface::SUBSCRIPTION => $subscriptionMock,
            EventInterface::DESTINATIONS => [$destination1Mock, $destination2Mock],
        ]);
        
        $expectedData = [
            EventInterface::ID => 111,
            EventInterface::CUSTOMER => ['customer data'],
            EventInterface::SUBSCRIPTION => ['subscription data'],
            EventInterface::DESTINATIONS => [
                ['first destination data'],
                ['second destination data']
            ],
        ];
        
        $this->assertEquals($expectedData, $event->toArray());
    }
}
