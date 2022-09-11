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

        $event = new Event([
            EventInterface::ID => 111,
            EventInterface::DATA => ['data'],
            EventInterface::DESTINATIONS => [$destination1Mock, $destination2Mock],
        ]);

        $expectedData = [
            EventInterface::ID => 111,
            EventInterface::DATA => ['data'],
            EventInterface::DESTINATIONS => [
                ['first destination data'],
                ['second destination data'],
            ],
        ];

        $this->assertEquals($expectedData, $event->toArray());
    }

    /**
     * @param array       $data
     * @param string|null $field
     * @param mixed|null  $result
     * @dataProvider getEventDataDataProvider
     */
    public function testGetEventData($data, $field, $result)
    {
        $event = new Event($data);

        $this->assertEquals($result, $event->getEventData($field));
    }

    /**
     * @return array
     */
    public function getEventDataDataProvider()
    {
        return [
            'Event data not set:without field' => [
                'data' => [
                    EventInterface::ID => 2312,
                    EventInterface::TYPE => 'type',
                ],
                'field' => null,
                'result' => null,
            ],
            'Event data not set:with field' => [
                'data' => [
                    EventInterface::ID => 54,
                    EventInterface::TYPE => 'subscription',
                ],
                'field' => 'field_value',
                'result' => null,
            ],
            'With event data:without field' => [
                'data' => [
                    EventInterface::ID => 897,
                    EventInterface::CREATED => '2020-12-12',
                    EventInterface::DATA => ['data'],
                ],
                'field' => null,
                'result' => ['data'],
            ],
            'With event data:with field:not found in data' => [
                'data' => [
                    EventInterface::UPDATED => '2018-08-08',
                    EventInterface::TYPE => 'payment',
                    EventInterface::DATA => ['field' => 'data'],
                ],
                'field' => 'another_field',
                'result' => null,
            ],
            'With event data:with field:field found' => [
                'data' => [
                    EventInterface::ID => 65455,
                    EventInterface::TYPE => 'some_type',
                    EventInterface::DATA => ['field' => 'value'],
                ],
                'field' => 'field',
                'result' => 'value',
            ],
        ];
    }
}
