<?php

namespace SubscribePro\Service;

/**
 * @codeCoverageIgnore
 */
abstract class AbstractServiceFactory implements ServiceFactoryInterface
{
    /**
     * @var \SubscribePro\Service\ServiceFactoryResolver
     */
    protected $serviceFactoryResolver;

    /**
     * @var \SubscribePro\Http
     */
    protected $httpClient;

    /**
     * @var array
     */
    protected $config;

    /**
     * @param \SubscribePro\Service\ServiceFactoryResolver $serviceFactoryResolver
     * @param \SubscribePro\Http                           $httpClient
     * @param array                                        $config
     */
    public function __construct(
        ServiceFactoryResolver $serviceFactoryResolver,
        \SubscribePro\Http $httpClient,
        array $config = []
    ) {
        $this->serviceFactoryResolver = $serviceFactoryResolver;
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    /**
     * @param string     $key
     * @param mixed|null $defaultValue
     *
     * @return mixed|null
     */
    protected function getConfigValue($key, $defaultValue = null)
    {
        return isset($this->config[$key]) ? $this->config[$key] : $defaultValue;
    }

    /**
     * @return \SubscribePro\Service\DataFactoryInterface
     */
    abstract protected function createDataFactory();
}
