<?php

namespace SubscribePro\Tests\Service\Event\Destination;

use SubscribePro\Service\Webhook\Event\Destination;
use SubscribePro\Service\Webhook\Event\DestinationInterface;
use SubscribePro\Service\Webhook\Event\Destination\EndpointInterface;
use SubscribePro\Service\Webhook\Event\Destination\Endpoint;

class DestinationTest extends \PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        $endPointData = [
            EndpointInterface::ID => 444
        ];
        $destination = new Destination([
            DestinationInterface::ID => 11,
            DestinationInterface::ENDPOINT => new Endpoint($endPointData),
        ]);
        
        $expectedData = [
            DestinationInterface::ID => 11,
            DestinationInterface::ENDPOINT => $endPointData,
        ];
        
        $this->assertEquals($expectedData, $destination->toArray());
    }
}
