<?php

namespace SubscribePro\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;

class HttpException extends \RuntimeException
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(ResponseInterface $response, $code = 0, Exception $previous = null)
    {
        parent::__construct($this->getErrorMessage($response), $code, $previous);

        $this->response = $response;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return string
     */
    protected function getErrorMessage(ResponseInterface $response)
    {
        $errorBody = json_decode((string)$response->getBody(), true);
        return !empty($errorBody['message']) ? $errorBody['message'] : $response->getReasonPhrase();
    }
}
