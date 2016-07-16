<?php

namespace SubscribePro\Tests;

use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;
use SubscribePro\Http;

class HttpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpMock;

    /**
     * @var \GuzzleHttp\HandlerStack|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $handlerStackMock;

    /**
     * @var \GuzzleHttp\Client|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $clientMock;

    protected function setUp()
    {
        $this->clientMock = $this->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->setMethods(['get', 'post', 'put'])
            ->getMock();
        $this->handlerStackMock = $this->getMockBuilder('GuzzleHttp\HandlerStack')
            ->disableOriginalConstructor()
            ->setMethods(['push'])
            ->getMock();

        $appMock = $this->getMockBuilder('SubscribePro\App')->disableOriginalConstructor()->getMock();

        $this->httpMock = $this->getMockBuilder('SubscribePro\Http')
            ->setMethods(['createClient', 'getHandlerStack', 'createMiddlewareLogCallback'])
            ->setConstructorArgs([$appMock])
            ->getMock();
        $this->httpMock->expects($this->any())->method('createClient')->willReturn($this->clientMock);
        $this->httpMock->expects($this->any())->method('getHandlerStack')->willReturn($this->handlerStackMock);
    }

    /**
     * @param string|null $fileName
     * @param string|null $lineFormat
     * @param string|null $messageFormat
     * @param string $logLevel
     * @param \Psr\Log\LoggerInterface $logger
     * @param \GuzzleHttp\MessageFormatter $messageFormatter
     * @param callable $middlewareCallback
     * @dataProvider initDefaultLoggerDataProvider
     */
    public function testInitDefaultLogger($fileName, $lineFormat, $messageFormat, $logLevel, $logger, $messageFormatter, $middlewareCallback)
    {
        $this->httpMock->expects($this->once())
            ->method('createMiddlewareLogCallback')
            ->with($logger, $messageFormatter, $logLevel)
            ->willReturn($middlewareCallback);

        $this->handlerStackMock->expects($this->once())
            ->method('push')
            ->with($middlewareCallback, 'logger');

        $this->httpMock->initDefaultLogger($fileName, $lineFormat, $messageFormat, $logLevel);
    }

    /**
     * @return array
     */
    public function initDefaultLoggerDataProvider()
    {
        $logHandler1 = new RotatingFileHandler(Http::DEFAULT_LOG_FILE_NAME);
        $logHandler1->setFormatter(new LineFormatter(Http::DEFAULT_LOG_LINE_FORMAT, null, true));

        $logHandler2 = new RotatingFileHandler('fileName');
        $logHandler2->setFormatter(new LineFormatter('%message%', null, true));

        return [
            'Default params' => [
                'fileName' => null,
                'lineFormat' => null,
                'messageFormat' => null,
                'logLevel' => LogLevel::INFO,
                'logger' => new Logger('Logger', [$logHandler1]),
                'messageFormatter' => new MessageFormatter(Http::DEFAULT_LOG_MESSAGE_FORMAT),
                'middlewareCallback' => function () {},
            ],
            'Custom params' => [
                'fileName' => 'fileName',
                'lineFormat' => '%message%',
                'messageFormat' => '{code}',
                'logLevel' => LogLevel::NOTICE,
                'logger' => new Logger('Logger', [$logHandler2]),
                'messageFormatter' => new MessageFormatter('{code}'),
                'middlewareCallback' => function () {},
            ],
        ];
    }

    public function testAddLogger()
    {
        $logger = new Logger('Logger');
        $messageFormatter = new MessageFormatter();
        $logLevel = LogLevel::ALERT;
        $middlewareCallback = function() {};

        $this->httpMock->expects($this->once())
            ->method('createMiddlewareLogCallback')
            ->with($logger, $messageFormatter, $logLevel)
            ->willReturn($middlewareCallback);

        $this->handlerStackMock->expects($this->once())
            ->method('push')
            ->with($middlewareCallback, 'logger');

        $this->httpMock->addLogger($logger, $messageFormatter, $logLevel);
    }

    /**
     * @param array $params
     * @param array $expectedRequestParams
     * @param string $url
     * @param string $bodyText
     * @param array $body
     * @param \GuzzleHttp\Psr7\Response $response
     * @dataProvider failToGetIfStatusCodeIsNotSuccessDataProvider
     * @expectedException \SubscribePro\Exception\HttpException
     */
    public function testFailToGetIfStatusCodeIsNotSuccess($params, $expectedRequestParams, $url, $bodyText, $body, $response)
    {
        $this->clientMock->expects($this->once())
            ->method('get')
            ->with($url, $expectedRequestParams)
            ->willReturn($response);

        $this->expectExceptionMessage($bodyText);

        $this->httpMock->get($url, $params);
    }

    /**
     * @return array
     */
    public function failToGetIfStatusCodeIsNotSuccessDataProvider()
    {
        return [
            'Without params' => [
                'params' => [],
                'expectedRequestParams' => [],
                'url' => 'site/url',
                'bodyText' => 'error',
                'body' => ['message' => 'error'],
                'response' => new Response(300, [], json_encode(['message' => 'error'])),
            ],
            'With params' => [
                'params' => ['name' => 'John'],
                'expectedRequestParams' => [RequestOptions::QUERY => ['name' => 'John']],
                'url' => 'site/url',
                'bodyText' => 'error',
                'body' => ['message' => 'error'],
                'response' => new Response(400, [], json_encode(['message' => 'error'])),
            ],
        ];
    }

    /**
     * @param string $url
     * @param \GuzzleHttp\Psr7\Response $response
     * @param array $params
     * @param array $expectedRequestParams
     * @param array|int $result
     * @dataProvider getDataProvider
     */
    public function testGet($url, $response, $params, $expectedRequestParams, $result)
    {
        $this->clientMock->expects($this->once())
            ->method('get')
            ->with($url, $expectedRequestParams)
            ->willReturn($response);

        $this->assertEquals($result, $this->httpMock->get($url, $params));
    }

    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            'Not empty response' => [
                'url' => 'site/url',
                'response' => new Response(200, [], json_encode(['message' => 'success'])),
                'params' => ['name' => 'John'],
                'expectedRequestParams' => [RequestOptions::QUERY => ['name' => 'John']],
                'result' => ['message' => 'success'],
            ],
            'Empty response' => [
                'url' => 'site/url',
                'response' => new Response(201, [], ''),
                'params' => [],
                'expectedRequestParams' => [],
                'result' => 201,
            ],
        ];
    }

    /**
     * @param array $params
     * @param array $expectedRequestParams
     * @param string $url
     * @param string $bodyText
     * @param array $body
     * @param \GuzzleHttp\Psr7\Response $response
     * @dataProvider failToPostIfStatusCodeIsNotSuccessDataProvider
     * @expectedException \SubscribePro\Exception\HttpException
     */
    public function testFailToPostIfStatusCodeIsNotSuccess($params, $expectedRequestParams, $url, $bodyText, $body, $response)
    {
        $this->clientMock->expects($this->once())
            ->method('post')
            ->with($url, $expectedRequestParams)
            ->willReturn($response);

        $this->expectExceptionMessage($bodyText);

        $this->httpMock->post($url, $params);
    }

    /**
     * @return array
     */
    public function failToPostIfStatusCodeIsNotSuccessDataProvider()
    {
        return $this->getErrorPostData();
    }

    /**
     * @param string $url
     * @param \GuzzleHttp\Psr7\Response $response
     * @param array $params
     * @param array $expectedRequestParams
     * @param array|int $result
     * @dataProvider postDataProvider
     */
    public function testPost($url, $response, $params, $expectedRequestParams, $result)
    {
        $this->clientMock->expects($this->once())
            ->method('post')
            ->with($url, $expectedRequestParams)
            ->willReturn($response);

        $this->assertEquals($result, $this->httpMock->post($url, $params));
    }

    /**
     * @return array
     */
    public function postDataProvider()
    {
        return $this->getSuccessPostData();
    }

    /**
     * @param array $params
     * @param array $expectedRequestParams
     * @param string $url
     * @param string $bodyText
     * @param array $body
     * @param \GuzzleHttp\Psr7\Response $response
     * @dataProvider failToPutIfStatusCodeIsNotSuccessDataProvider
     * @expectedException \SubscribePro\Exception\HttpException
     */
    public function testFailToPutIfStatusCodeIsNotSuccess($params, $expectedRequestParams, $url, $bodyText, $body, $response)
    {
        $this->clientMock->expects($this->once())
            ->method('put')
            ->with($url, $expectedRequestParams)
            ->willReturn($response);

        $this->expectExceptionMessage($bodyText);

        $this->httpMock->put($url, $params);
    }

    /**
     * @return array
     */
    public function failToPutIfStatusCodeIsNotSuccessDataProvider()
    {
        return $this->getErrorPostData();
    }

    /**
     * @param string $url
     * @param \GuzzleHttp\Psr7\Response $response
     * @param array $params
     * @param array $expectedRequestParams
     * @param array|int $result
     * @dataProvider putDataProvider
     */
    public function testPut($url, $response, $params, $expectedRequestParams, $result)
    {
        $this->clientMock->expects($this->once())
            ->method('put')
            ->with($url, $expectedRequestParams)
            ->willReturn($response);

        $this->assertEquals($result, $this->httpMock->put($url, $params));
    }

    /**
     * @return array
     */
    public function putDataProvider()
    {
        return $this->getSuccessPostData();
    }

    /**
     * @expectedException \SubscribePro\Exception\HttpException
     * @expectedExceptionMessage error
     */
    public function testFailToGetToSinkIfStatusCodeIsNotSuccess()
    {
        $url = 'site/url';
        $filePath = 'file/path';
        $response = new Response(300, [], json_encode(['message' => 'error']));

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with($url, [RequestOptions::SINK => $filePath])
            ->willReturn($response);

        $this->httpMock->getToSink($url, $filePath);
    }

    /**
     * @param string $url
     * @param \GuzzleHttp\Psr7\Response $response
     * @param string $filePath
     * @param array|int $result
     * @dataProvider getToSinkDataProvider
     */
    public function testGetToSink($url, $response, $filePath, $result)
    {
        $this->clientMock->expects($this->once())
            ->method('get')
            ->with($url, [RequestOptions::SINK => $filePath])
            ->willReturn($response);

        $this->assertEquals($result, $this->httpMock->getToSink($url, $filePath));
    }

    /**
     * @return array
     */
    public function getToSinkDataProvider()
    {
        return [
            'Not empty response' => [
                'url' => 'site/url',
                'response' => new Response(200, [], json_encode(['message' => 'success'])),
                'filePath' => 'file/path',
                'result' => ['message' => 'success'],
            ],
            'Empty response' => [
                'url' => 'site/url',
                'response' => new Response(201, [], ''),
                'filePath' => 'file/path',
                'result' => 201,
            ],
        ];
    }

    /**
     * @return array
     */
    private function getErrorPostData()
    {
        return [
            'Empty params' => [
                'params' => [],
                'expectedRequestParams' => [],
                'url' => 'site/url',
                'bodyText' => 'error',
                'body' => ['message' => 'error'],
                'response' => new Response(300, [], json_encode(['message' => 'error'])),
            ],
            'Not empty params' => [
                'params' => ['name' => 'John'],
                'expectedRequestParams' => [RequestOptions::JSON => ['name' => 'John']],
                'url' => 'site/url',
                'bodyText' => 'error',
                'body' => ['message' => 'error'],
                'response' => new Response(400, [], json_encode(['message' => 'error'])),
            ],
        ];
    }

    /**
     * @return array
     */
    private function getSuccessPostData()
    {
        return [
            'Not empty response' => [
                'url' => 'site/url',
                'response' => new Response(200, [], json_encode(['message' => 'success'])),
                'params' => ['name' => 'John'],
                'expectedRequestParams' => [RequestOptions::JSON => ['name' => 'John']],
                'result' => ['message' => 'success'],
            ],
            'Empty response' => [
                'url' => 'site/url',
                'response' => new Response(201, [], ''),
                'params' => [],
                'expectedRequestParams' => [],
                'result' => 201,
            ],
        ];
    }
}
