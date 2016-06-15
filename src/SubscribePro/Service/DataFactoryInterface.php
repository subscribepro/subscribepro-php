<?php

namespace SubscribePro\Service;

interface DataFactoryInterface
{
    /**
     * @param array $data
     * @return \SubscribePro\Service\DataInterface
     */
    public function create(array $data = []);
}
