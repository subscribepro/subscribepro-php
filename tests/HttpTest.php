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
            ->setConstructorArgs([$appMock, 30])
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
     * @dataProvider addDefaultLoggerDataProvider
     */
    public function testAddDefaultLogger($fileName, $lineFormat, $messageFormat, $logLevel, $logger, $messageFormatter, $middlewareCallback)
    {
        $this->httpMock->expects($this->once())
            ->method('createMiddlewareLogCallback')
            ->with($logger, $messageFormatter, $logLevel)
            ->willReturn($middlewareCallback);

        $this->handlerStackMock->expects($this->once())
            ->method('push')
            ->with($middlewareCallback, 'logger');

        $this->httpMock->addDefaultLogger($fileName, $lineFormat, $messageFormat, $logLevel);
    }

    /**
     * @return array
     */
    public function addDefaultLoggerDataProvider()
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
     * @param \GuzzleHttp\Psr7\Response $response
     * @dataProvider failToGetIfStatusCodeIsNotSuccessDataProvider
     * @expectedException \SubscribePro\Exception\HttpException
     * @expectedExceptionMessageRegExp /[first|second] error message/
     */
    public function testFailToGetIfStatusCodeIsNotSuccess($params, $expectedRequestParams, $url, $response)
    {
        $this->clientMock->expects($this->once())
            ->method('get')
            ->with($url, $expectedRequestParams)
            ->willReturn($response);

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
                'url' => 'site-one/url',
                'response' => new Response(300, [], json_encode(['message' => 'first error message'])),
            ],
            'With params' => [
                'params' => ['name' => 'John'],
                'expectedRequestParams' => [RequestOptions::QUERY => ['name' => 'John']],
                'url' => 'site-two/url',
                'response' => new Response(400, [], json_encode(['message' => 'second error message'])),
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
                'url' => 'site-one/url',
                'response' => new Response(200, [], json_encode(['message' => 'success'])),
                'params' => ['name' => 'John'],
                'expectedRequestParams' => [RequestOptions::QUERY => ['name' => 'John']],
                'result' => ['message' => 'success'],
            ],
            'Empty response' => [
                'url' => 'site-two/url',
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
     * @param \GuzzleHttp\Psr7\Response $response
     * @dataProvider failToPostIfStatusCodeIsNotSuccessDataProvider
     * @expectedException \SubscribePro\Exception\HttpException
     * @expectedExceptionMessageRegExp /[api|http] error/
     */
    public function testFailToPostIfStatusCodeIsNotSuccess($params, $expectedRequestParams, $url, $response)
    {
        $this->clientMock->expects($this->once())
            ->method('post')
            ->with($url, $expectedRequestParams)
            ->willReturn($response);

        $this->httpMock->post($url, $params);
    }

    /**
     * @return array
     */
    public function failToPostIfStatusCodeIsNotSuccessDataProvider()
    {
        return [
            'Empty params' => [
                'params' => [],
                'expectedRequestParams' => [],
                'url' => 'site-one/url',
                'response' => new Response(300, [], json_encode(['message' => 'api error'])),
            ],
            'Not empty params' => [
                'params' => ['name' => 'John'],
                'expectedRequestParams' => [RequestOptions::JSON => ['name' => 'John']],
                'url' => 'site-one/url',
                'response' => new Response(400, [], json_encode(['message' => 'http error'])),
            ],
        ];
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
        return [
            'Not empty response' => [
                'url' => 'site-one/url',
                'response' => new Response(200, [], json_encode(['message' => 'success'])),
                'params' => ['name' => 'John'],
                'expectedRequestParams' => [RequestOptions::JSON => ['name' => 'John']],
                'result' => ['message' => 'success'],
            ],
            'Empty response' => [
                'url' => 'site-two/url',
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
     * @param \GuzzleHttp\Psr7\Response $response
     * @dataProvider failToPutIfStatusCodeIsNotSuccessDataProvider
     * @expectedException \SubscribePro\Exception\HttpException
     * @expectedExceptionMessageRegExp /[one|another] error/
     */
    public function testFailToPutIfStatusCodeIsNotSuccess($params, $expectedRequestParams, $url, $response)
    {
        $this->clientMock->expects($this->once())
            ->method('put')
            ->with($url, $expectedRequestParams)
            ->willReturn($response);

        $this->httpMock->put($url, $params);
    }

    /**
     * @return array
     */
    public function failToPutIfStatusCodeIsNotSuccessDataProvider()
    {
        return [
            'Empty params' => [
                'params' => [],
                'expectedRequestParams' => [],
                'url' => 'site-one/url',
                'response' => new Response(300, [], json_encode(['message' => 'one error'])),
            ],
            'Not empty params' => [
                'params' => ['name' => 'John'],
                'expectedRequestParams' => [RequestOptions::JSON => ['name' => 'John']],
                'url' => 'site-two/url',
                'response' => new Response(400, [], json_encode(['message' => 'another error'])),
            ],
        ];
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
        return [
            'Not empty response' => [
                'url' => 'site-one/path',
                'response' => new Response(200, [], json_encode(['message' => 'success'])),
                'params' => ['name' => 'John'],
                'expectedRequestParams' => [RequestOptions::JSON => ['name' => 'John']],
                'result' => ['message' => 'success'],
            ],
            'Empty response' => [
                'url' => 'site-two/url',
                'response' => new Response(201, [], ''),
                'params' => [],
                'expectedRequestParams' => [],
                'result' => 201,
            ],
        ];
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
                'url' => 'site-one/url',
                'response' => new Response(200, [], json_encode(['message' => 'success'])),
                'filePath' => 'file/path',
                'result' => ['message' => 'success'],
            ],
            'Empty response' => [
                'url' => 'site-two/url',
                'response' => new Response(201, [], ''),
                'filePath' => 'file/path',
                'result' => 201,
            ],
        ];
    }
}
