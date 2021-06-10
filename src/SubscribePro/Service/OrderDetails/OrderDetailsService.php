<?php

namespace SubscribePro\Service\OrderDetails;

use SubscribePro\Service\AbstractService;

class OrderDetailsService extends AbstractService
{
    const NAME = 'order_details';

    CONST API_NAME_ORDER_DETAILS = 'order_details';

    /**
     * @param OrderDetails $orderDetails
     * @return \SubscribePro\Service\DataInterface
     */
    public function createSalesOrder(OrderDetails $orderDetails)
    {
        $response = $this->httpClient->post('/services/v2/order-details.json', [
            self::API_NAME_ORDER_DETAILS => $orderDetails->toArray(),
        ]);
        return $this->retrieveItem($response, self::API_NAME_ORDER_DETAILS);
    }

    /**
     * @param OrderDetails $orderDetails
     * @return \SubscribePro\Service\DataInterface
     */
    public function createOrUpdateSalesOrder(OrderDetails $orderDetails)
    {
        $response = $this->httpClient->post('services/v2/order-details/create-or-update.json', [
            self::API_NAME_ORDER_DETAILS => $orderDetails->toArray(),
        ]);
        return $this->retrieveItem($response, self::API_NAME_ORDER_DETAILS);;
    }
}