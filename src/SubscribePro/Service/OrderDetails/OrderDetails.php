<?php

namespace SubscribePro\Service\OrderDetails;

use SubscribePro\Service\DataObject;

class OrderDetails extends DataObject implements OrderDetailsInterface
{
    /**
     * @param array $data
     *
     * @return $this
     */
    public function importData(array $data = [])
    {
        $this->data[self::ORDER_DETAILS] = $data;

        return $this;
    }

    public function getOrderDetails()
    {
        return $this->getData(self::ORDER_DETAILS);
    }

    public function setOrderDetails(array $orderDetails)
    {
        return $this->setData(self::ORDER_DETAILS, $orderDetails);
    }
}
