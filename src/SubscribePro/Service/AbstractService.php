<?php

namespace SubscribePro\Service;

use SubscribePro\Sdk;

abstract class AbstractService
{
    /**
     * Config instance name
     */
    const CONFIG_INSTANCE_NAME = 'instance_name';

    /**
     * @var \SubscribePro\Http
     */
    protected $httpClient;

    /**
     * @var \SubscribePro\Service\DataFactoryInterface
     */
    protected $dataFactory;

    /**
     * @var array
     */
    protected $config;

    /**
     * @param \SubscribePro\Sdk $sdk
     * @param array $config
     */
    public function __construct(Sdk $sdk, array $config = [])
    {
        $this->httpClient = $sdk->getHttp();
        $this->config = $config;
        $this->dataFactory = $this->createDataFactory($sdk);
    }

    /**
     * @param string $key
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    protected function getConfigValue($key, $defaultValue = null)
    {
        return isset($this->config[$key]) ? $this->config[$key] : $defaultValue;
    }

    /**
     * @param \SubscribePro\Sdk $sdk
     * @return \SubscribePro\Service\DataFactoryInterface
     */
    abstract protected function createDataFactory(Sdk $sdk);

    /**
     * @return \SubscribePro\Service\DataFactoryInterface
     */
    protected function getDataFactory()
    {
        return $this->dataFactory;
    }

    /**
     * @param array $response
     * @param string $entityName
     * @param \SubscribePro\Service\DataInterface|null $item
     * @return \SubscribePro\Service\DataInterface
     */
    protected function retrieveItem($response, $entityName, DataInterface $item = null)
    {
        $itemData = !empty($response[$entityName]) ? $response[$entityName] : [];
        $item = $item ? $item->importData($itemData) : $this->dataFactory->create($itemData);
        return $item;
    }

    /**
     * @param array $response
     * @param string $entitiesName
     * @return \SubscribePro\Service\DataInterface[]
     */
    protected function retrieveItems($response, $entitiesName)
    {
        $responseData = !empty($response[$entitiesName]) ? $response[$entitiesName] : [];
        $items = $this->createItems($responseData);
        return $items;
    }

    /**
     * @param array $data
     * @return \SubscribePro\Service\DataInterface[]
     */
    protected function createItems(array $data = [])
    {
        return array_map(function ($itemData) {
            return $this->dataFactory->create($itemData);
        }, $data);
    }
}
