<?php

namespace SubscribePro\Tools;

abstract class AbstractTool
{
    /**
     * @var \SubscribePro\Http
     */
    protected $httpClient;

    /**
     * @param \SubscribePro\Http $http
     */
    public function __construct(\SubscribePro\Http $http)
    {
        $this->httpClient = $http;
    }
}
