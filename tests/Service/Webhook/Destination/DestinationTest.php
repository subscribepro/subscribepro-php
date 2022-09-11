<?php

namespace SubscribePro\Tests\Service\Event\Destination;

use SubscribePro\Service\Webhook\Event\Destination;
use SubscribePro\Service\Webhook\Event\DestinationInterface;

class DestinationTest extends \PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        $endpointMock = $this->getMockBuilder('SubscribePro\Service\Webhook\Event\Destination\EndpointInterface')
            ->getMock();
        $endpointMock->expects($this->once())
            ->method('toArray')
            ->willReturn(['endpoint data']);

        $destination = new Destination([
            DestinationInterface::ID => 11,
            DestinationInterface::ENDPOINT => $endpointMock,
        ]);

        $expectedData = [
            DestinationInterface::ID => 11,
            DestinationInterface::ENDPOINT => ['endpoint data'],
        ];

        $this->assertEquals($expectedData, $destination->toArray());
    }
}
