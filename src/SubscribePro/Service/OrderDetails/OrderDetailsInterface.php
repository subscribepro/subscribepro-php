<?php

namespace SubscribePro\Service\OrderDetails;

use SubscribePro\Service\DataInterface;

interface OrderDetailsInterface extends DataInterface
{
    /**
     * Data fields
     */
    const ORDER_DETAILS = 'order_details';

    /**
     * @return mixed[]
     */
    public function getOrderDetails();

    /**
     * @param array $orderDetails
     * @return mixed
     */
    public function setOrderDetails(array $orderDetails);
}
