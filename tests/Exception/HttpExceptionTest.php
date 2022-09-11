<?php

namespace SubscribePro\Tests\Exception;

use Psr\Http\Message\ResponseInterface;
use SubscribePro\Exception\HttpException;

class HttpExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Exception\HttpException
     */
    protected $httpException;

    /**
     * @var \Psr\Http\Message\ResponseInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $response;

    protected function setUp(): void
    {
        $this->response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $this->httpException = new HttpException($this->response);
    }

    public function testGetResponse()
    {
        $this->assertSame($this->response, $this->httpException->getResponse());
    }

    public function testGetStatusCode()
    {
        $statusCode = 'some-code';

        $this->response->expects($this->atLeastOnce())
            ->method('getStatusCode')
            ->willReturn($statusCode);

        $this->assertSame($statusCode, $this->httpException->getStatusCode());
    }

    public function testMessage()
    {
        $message = 'exception-message';

        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $response->expects($this->never())->method('getReasonPhrase');
        $response->expects($this->atLeastOnce())
            ->method('getBody')
            ->willReturn(json_encode(['message' => $message]));

        $httpException = new HttpException($response);
        $this->assertEquals($message, $httpException->getMessage());
    }

    public function testMessageWithReasonPhrase()
    {
        $reasonCode = 'reason-code';

        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $response->expects($this->atLeastOnce())
            ->method('getReasonPhrase')
            ->willReturn($reasonCode);
        $response->expects($this->atLeastOnce())
            ->method('getBody')
            ->willReturn(null);

        $httpException = new HttpException($response);
        $this->assertEquals($reasonCode, $httpException->getMessage());
    }
}
