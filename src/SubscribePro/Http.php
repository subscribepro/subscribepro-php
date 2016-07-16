<?php

namespace SubscribePro;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;
use Psr\Log\LogLevel;
use SubscribePro\Exception\HttpException;

class Http
{
    /**
     * Default log file name
     */
    const DEFAULT_LOG_FILE_NAME = 'log/subscribepro.log';

    /**
     * Default log line format
     */
    const DEFAULT_LOG_LINE_FORMAT = "[%datetime%] %channel%.%level_name%: %message%\n";

    /**
     * Default log message format
     */
    const DEFAULT_LOG_MESSAGE_FORMAT = "{method} - {uri}\nRequest body: {req_body}\n{code} {phrase}\nResponse body: {res_body}\n{error}\n";

    /**
     * @var string
     */
    protected $baseUrl = 'https://api.subscribepro.com';

    /**
     * @var \SubscribePro\App
     */
    protected $app;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var \GuzzleHttp\HandlerStack
     */
    protected $handlerStack;

    /**
     * @param App $app
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->handlerStack = $this->createHandlerStack();
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function createClient()
    {
        return new Client([
            'base_uri' => $this->baseUrl,
            'handler'  => $this->handlerStack,
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::AUTH => [$this->app->getClientId(), $this->app->getClientSecret()]
        ]);
    }

    /**
     * @return \GuzzleHttp\HandlerStack
     */
    protected function createHandlerStack()
    {
        return HandlerStack::create();
    }

    /**
     * @return \GuzzleHttp\HandlerStack
     */
    public function getHandlerStack()
    {
        return $this->handlerStack;
    }

    /**
     * @param string|null $fileName
     * @param string|null $lineFormat
     * @param string|null $messageFormat
     * @param string|null $logLevel
     * @return Http
     */
    public function initDefaultLogger($fileName = null, $lineFormat = null, $messageFormat = null, $logLevel = null)
    {
        $fileName = $fileName ?: static::DEFAULT_LOG_FILE_NAME;
        $lineFormat = $lineFormat ?: static::DEFAULT_LOG_LINE_FORMAT;
        $messageFormat = $messageFormat ?: static::DEFAULT_LOG_MESSAGE_FORMAT;
        $logLevel = $logLevel ?: LogLevel::INFO;

        $logHandler = new RotatingFileHandler($fileName);
        $logHandler->setFormatter(new LineFormatter($lineFormat, null, true));

        return $this->addLogger(new Logger('Logger', [$logHandler]), new MessageFormatter($messageFormat), $logLevel);
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \GuzzleHttp\MessageFormatter $messageFormatter
     * @param string $logLevel
     * @return $this
     */
    public function addLogger($logger, $messageFormatter, $logLevel = LogLevel::INFO)
    {
        $this->getHandlerStack()
            ->push(
                $this->createMiddlewareLogCallback($logger, $messageFormatter, $logLevel),
                'logger'
            );
        return $this;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \GuzzleHttp\MessageFormatter $messageFormatter
     * @param string $logLevel
     * @return callable
     */
    protected function createMiddlewareLogCallback($logger, $messageFormatter, $logLevel = LogLevel::INFO)
    {
        return Middleware::log($logger, $messageFormatter, $logLevel);
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function getClient()
    {
        if (null === $this->client) {
            $this->client = $this->createClient();
        }
        return $this->client;
    }

    /**
     * @param string $uri
     * @param array $params
     * @return array|int|null
     * @throws \SubscribePro\Exception\HttpException
     */
    public function get($uri, $params = [])
    {
        $options = empty($params) ? [] : [RequestOptions::QUERY => $params];
        $response = $this->getClient()->get($uri, $options);

        return $this->processResponse($response);
    }

    /**
     * @param string $uri
     * @param array $postData
     * @return array|int|null
     * @throws \SubscribePro\Exception\HttpException
     */
    public function post($uri, $postData = [])
    {
        $options = empty($postData) ? [] : [RequestOptions::JSON => $postData];
        $response = $this->getClient()->post($uri, $options);

        return $this->processResponse($response);
    }

    /**
     * @param string $uri
     * @param array $putData
     * @return array|int|null
     * @throws \SubscribePro\Exception\HttpException
     */
    public function put($uri, $putData = [])
    {
        $options = empty($putData) ? [] : [RequestOptions::JSON => $putData];
        $response = $this->getClient()->put($uri, $options);

        return $this->processResponse($response);
    }

    /**
     * @param string $uri
     * @param string $filePath
     * @return array|int|null
     * @throws \SubscribePro\Exception\HttpException
     */
    public function getToSink($uri, $filePath)
    {
        $response = $this->getClient()->get($uri, [RequestOptions::SINK => $filePath]);

        return $this->processResponse($response);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return array|int|null
     * @throws \SubscribePro\Exception\HttpException
     */
    protected function processResponse($response)
    {
        if ($response->getStatusCode() < 300) {
            $body = (string)$response->getBody();
            return !empty($body) ? json_decode($body, true) : $response->getStatusCode();
        }

        throw new HttpException($response);
    }
}
