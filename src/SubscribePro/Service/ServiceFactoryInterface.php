<?php

namespace SubscribePro\Service;

interface ServiceFactoryInterface
{
    /**
     * @return \SubscribePro\Service\AbstractService
     */
    public function create();
}
