<?php

namespace SubscribePro\Service\SalesOrder;

use SubscribePro\Service\AbstractService;

/**
 * Config options for sales order service:
 * - instance_name
 *   Specified class must implement \SubscribePro\Service\SalesOrder\SalesOrderInterface interface
 *   Default value is \SubscribePro\Service\SalesOrder\SalesOrder
 *
 *   @see \SubscribePro\Service\SalesOrder\SalesOrderInterface
 *
 * @method \SubscribePro\Service\SalesOrder\SalesOrderInterface   retrieveItem($response, $entityName, \SubscribePro\Service\DataInterface $item = null)
 * @method \SubscribePro\Service\SalesOrder\SalesOrderInterface[] retrieveItems($response, $entitiesName)
 *
 * @property \SubscribePro\Service\SalesOrder\SubscriptionFactory $dataFactory
 */
class SalesOrderService extends AbstractService
{
    /**
     * Service name
     */
    public const NAME = 'sales_order';

    /**
     * @param array $salesOrderData
     *
     * @return \SubscribePro\Service\SalesOrder\SalesOrderInterface
     */
    public function createSalesOrder(array $salesOrderData = [])
    {
        return $this->dataFactory->create($salesOrderData);
    }
}
