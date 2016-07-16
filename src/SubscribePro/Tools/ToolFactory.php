<?php

namespace SubscribePro\Tools;

use SubscribePro\Exception\InvalidArgumentException;
use SubscribePro\Utils\StringUtils;

class ToolFactory
{
    use StringUtils;

    /**
     * @var \SubscribePro\Http
     */
    protected $httpClient;

    /**
     * @param \SubscribePro\Http $httpClient
     */
    public function __construct(\SubscribePro\Http $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $name
     * @return \SubscribePro\Tools\AbstractTool
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function create($name)
    {
        $className = $this->getClassName($name);

        if (!class_exists($className)) {
            throw new InvalidArgumentException("Tool with '{$name}' name does not exist.");
        }

        return new $className($this->httpClient);
    }

    /**
     * @param string $name
     * @return string
     */
    private function getClassName($name)
    {
        $name = $this->camelize($name);

        return "SubscribePro\\Tools\\{$name}";
    }
}
