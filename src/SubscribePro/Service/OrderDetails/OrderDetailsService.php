<?php

declare(strict_types=1);

namespace SubscribePro\Service\OrderDetails;

use SubscribePro\Service\AbstractService;
use SubscribePro\Service\SalesOrder\SalesOrderFactory;

class OrderDetailsService extends AbstractService
{
    const NAME = 'order_details';

    const API_NAME_ORDER_DETAILS = 'order_details';
    const API_NAME_SALES_ORDER = 'sales_order';

    /**
     * @param array $orderDetailsData
     * @return \SubscribePro\Service\DataInterface
     */
    public function createOrderDetails(array $orderDetailsData = [])
    {
        return $this->dataFactory->create($orderDetailsData);
    }

    /**
     * @param \SubscribePro\Service\OrderDetails\OrderDetailsInterface $orderDetails
     * @return \SubscribePro\Service\DataInterface
     */
    public function saveNewOrderDetails(OrderDetailsInterface $orderDetails)
    {
        $response = $this->httpClient->post('/services/v2/order-details.json', [
            self::API_NAME_ORDER_DETAILS => $orderDetails->getOrderDetails(),
        ]);
        $salesOrderFactory = new SalesOrderFactory('\SubscribePro\Service\SalesOrder\SalesOrder');
        return $salesOrderFactory->create($response[self::API_NAME_SALES_ORDER]);
    }

    /**
     * @param \SubscribePro\Service\OrderDetails\OrderDetailsInterface $orderDetails
     * @return \SubscribePro\Service\DataInterface
     */
    public function saveNewOrUpdateExistingOrderDetails(OrderDetailsInterface $orderDetails)
    {
        $response = $this->httpClient->post('services/v2/order-details/create-or-update.json', [
            self::API_NAME_ORDER_DETAILS => $orderDetails->getOrderDetails(),
        ]);
        $salesOrderFactory = new SalesOrderFactory('\SubscribePro\Service\SalesOrder\SalesOrder');
        return $salesOrderFactory->create($response[self::API_NAME_SALES_ORDER]);
    }
}
