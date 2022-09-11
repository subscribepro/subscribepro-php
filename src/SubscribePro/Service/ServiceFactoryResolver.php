<?php

namespace SubscribePro\Service;

use SubscribePro\Exception\InvalidArgumentException;
use SubscribePro\Utils\StringUtils;

/**
 * @codeCoverageIgnore
 */
class ServiceFactoryResolver
{
    use StringUtils;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var \SubscribePro\Http
     */
    protected $httpClient;

    /**
     * @var \SubscribePro\Service\ServiceFactoryInterface[]
     */
    protected $factories;

    /**
     * @param \SubscribePro\Http $http
     * @param array              $config
     */
    public function __construct(
        \SubscribePro\Http $http,
        array $config = []
    ) {
        $this->config = $config;
        $this->httpClient = $http;
    }

    /**
     * @param string $name
     *
     * @return \SubscribePro\Service\ServiceFactoryInterface|\SubscribePro\Service\AbstractServiceFactory
     *
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function getServiceFactory($name)
    {
        if (!isset($this->factories[$name])) {
            $this->factories[$name] = $this->createServiceFactory($name);
        }

        return $this->factories[$name];
    }

    /**
     * @param string $name
     *
     * @return \SubscribePro\Service\ServiceFactoryInterface
     *
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    protected function createServiceFactory($name)
    {
        $className = $this->getClassName($name);

        if (!class_exists($className)) {
            throw new InvalidArgumentException("Service factory with '{$name}' name does not exist.");
        }

        return new $className($this, $this->httpClient, $this->getServiceConfig($name));
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function getClassName($name)
    {
        $name = $this->camelize($name);

        return "SubscribePro\\Service\\{$name}\\{$name}ServiceFactory";
    }

    /**
     * @param string $name
     *
     * @return array
     */
    protected function getServiceConfig($name)
    {
        return (array) (empty($this->config[$name]) ? [] : $this->config[$name]);
    }
}
