<?php

namespace SubscribePro\Service;

use SubscribePro\Http;

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
     * @param Http $http
     * @param DataFactoryInterface $factory
     * @param array $config
     */
    public function __construct(Http $http, DataFactoryInterface $factory, array $config = [])
    {
        $this->httpClient = $http;
        $this->dataFactory = $factory;
        $this->config = $config;
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
    private function createItems(array $data = [])
    {
        return array_map(function ($itemData) {
            return $this->dataFactory->create($itemData);
        }, $data);
    }
}
