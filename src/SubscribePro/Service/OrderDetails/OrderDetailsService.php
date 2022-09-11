<?php

namespace SubscribePro\Service\OrderDetails;

use SubscribePro\Service\AbstractService;
use SubscribePro\Service\SalesOrder\SalesOrderFactory;

class OrderDetailsService extends AbstractService
{
    public const NAME = 'order_details';

    public const API_NAME_ORDER_DETAILS = 'order_details';
    public const API_NAME_SALES_ORDER = 'sales_order';

    /**
     * @param array $orderDetails
     *
     * @return \SubscribePro\Service\OrderDetails\OrderDetailsInterface
     */
    public function createOrderDetails(array $orderDetailsData = [])
    {
        return $this->dataFactory->create($orderDetailsData);
    }

    /**
     * @param OrderDetails $orderDetails
     *
     * @return \SubscribePro\Service\DataInterface
     */
    public function saveNewOrderDetails(OrderDetails $orderDetails)
    {
        $response = $this->httpClient->post('/services/v2/order-details.json', [
            self::API_NAME_ORDER_DETAILS => $orderDetails->getOrderDetails(),
        ]);
        $salesOrderFactory = new SalesOrderFactory('\SubscribePro\Service\SalesOrder\SalesOrder');

        return $salesOrderFactory->create($response[self::API_NAME_SALES_ORDER]);
    }

    /**
     * @param OrderDetails $orderDetails
     *
     * @return \SubscribePro\Service\DataInterface
     */
    public function saveNewOrUpdateExistingOrderDetails(OrderDetails $orderDetails)
    {
        $response = $this->httpClient->post('services/v2/order-details/create-or-update.json', [
            self::API_NAME_ORDER_DETAILS => $orderDetails->getOrderDetails(),
        ]);
        $salesOrderFactory = new SalesOrderFactory('\SubscribePro\Service\SalesOrder\SalesOrder');

        return $salesOrderFactory->create($response[self::API_NAME_SALES_ORDER]);
    }
}
