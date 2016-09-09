<?php

namespace SubscribePro\Tests\Service\Webhook;

use GuzzleHttp\Psr7\Response;
use SubscribePro\Service\Webhook\EventInterface;
use SubscribePro\Service\Webhook\WebhookService;

class WebhookServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Webhook\WebhookService
     */
    protected $webhookService;

    /**
     * @var \SubscribePro\Service\DataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventFactoryMock;

    /**
     * @var \SubscribePro\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClientMock;

    protected function setUp()
    {
        $this->httpClientMock = $this->getMockBuilder('SubscribePro\Http')
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventFactoryMock = $this->getMockBuilder('SubscribePro\Service\DataFactoryInterface')->getMock();

        $this->webhookService = new WebhookService($this->httpClientMock, $this->eventFactoryMock);
    }

    public function testFailToPing()
    {
        $exception = new \SubscribePro\Exception\HttpException(new Response());

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with('/services/v2/webhook-test.json')
            ->willThrowException($exception);

        $this->assertFalse($this->webhookService->ping());
    }

    public function testPing()
    {
        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with('/services/v2/webhook-test.json');

        $this->assertTrue($this->webhookService->ping());
    }

    /**
     * @param string $request
     * @dataProvider readEventDataProvider
     */
    public function testReadEventIfWrongRequest($request)
    {
        $this->httpClientMock->expects($this->once())
            ->method('getRawRequest')
            ->willReturn($request);

        $this->assertFalse($this->webhookService->readEvent());
    }

    /**
     * @return array
     */
    public function readEventDataProvider()
    {
        return [
            'not array' => [
                'request' => 'some text'
            ],
            'wrong array' => [
                'request' => ['test' => 'value']
            ],
            'empty webhook_event' => [
                'request' => ['webhook_event' => '']
            ],
            'not json webhook_event' => [
                'request' => ['webhook_event' => 'text']
            ]
        ];
    }

    public function testReadEvent()
    {
        $webhookEvent = ['key' => 'value'];

        $this->httpClientMock->expects($this->once())
            ->method('getRawRequest')
            ->willReturn(['webhook_event' => json_encode($webhookEvent)]);

        $eventMock = $this->getMockBuilder(EventInterface::class)->getMock();

        $this->eventFactoryMock->expects($this->once())
            ->method('create')
            ->with($webhookEvent)
            ->willReturn($eventMock);

        $this->assertSame($eventMock, $this->webhookService->readEvent());
    }

    public function testLoadEvent()
    {
        $eventId = 52231;
        $itemData = [EventInterface::ID => $eventId];
        $eventMock = $this->getMockBuilder('SubscribePro\Service\Webhook\EventInterface')->getMock();

        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with("/services/v2/webhook-events/{$eventId}.json")
            ->willReturn([WebhookService::API_NAME_WEBHOOK_EVENT => $itemData]);

        $this->eventFactoryMock->expects($this->once())
            ->method('create')
            ->with($itemData)
            ->willReturn($eventMock);

        $this->assertSame($eventMock, $this->webhookService->loadEvent($eventId));
    }
}
