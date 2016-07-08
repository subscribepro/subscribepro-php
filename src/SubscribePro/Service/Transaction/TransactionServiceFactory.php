<?php

namespace SubscribePro\Service\Transaction;

use SubscribePro\Service\AbstractServiceFactory;

class TransactionServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SubscribePro\Service\AbstractService
     */
    public function create()
    {
        return new TransactionService(
            $this->httpClient,
            $this->createDataFactory(),
            $this->config
        );
    }

    /**
     * @return \SubscribePro\Service\DataFactoryInterface
     */
    protected function createDataFactory()
    {
        return new TransactionFactory(
            $this->getConfigValue(TransactionService::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\Transaction\Transaction')
        );
    }
}
