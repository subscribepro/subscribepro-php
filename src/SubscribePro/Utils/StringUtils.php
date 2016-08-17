<?php

namespace SubscribePro\Utils;

/**
 * @codeCoverageIgnore
 */
trait StringUtils
{
    /**
     * @param string $name
     * @return string
     */
    protected function camelize($name)
    {
        return implode('', array_map('ucfirst', explode('_', $name)));
    }

    /**
     * @param string $name
     * @return string
     */
    protected function underscore($name)
    {
        return strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', '_$1', $name), '_'));
    }
}
