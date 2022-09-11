<?php

namespace SubscribePro\Service\SalesOrder;

use SubscribePro\Service\DataObject;

class SalesOrder extends DataObject implements SalesOrderInterface
{
    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::ID, (int) $id);
    }

    /**
     * @return SubscribePro\Service\DataInterface|null
     */
    public function getCustomer()
    {
        return $this->getData(self::CUSTOMER);
    }

    /**
     * @param SubscribePro\Service\DataInterface $customer
     *
     * @return $this
     */
    public function setCustomer(\SubscribePro\Service\DataInterface $customer)
    {
        return $this->setData(self::CUSTOMER, $customer);
    }

    /**
     * @return bool
     */
    public function getIsSubscriptionOrder()
    {
        return $this->getData(self::IS_SUBSCRIPTION_ORDER);
    }

    /**
     * @param $bool $isSubscriptionOrder
     *
     * @return $this
     */
    public function setIsSubscriptionOrder($isSubscriptionOrder = false)
    {
        return $this->setData(self::IS_SUBSCRIPTION_ORDER, (bool) $isSubscriptionOrder);
    }

    /**
     * @return string|null
     */
    public function getOrderNumber()
    {
        return $this->getData(self::ORDER_NUMBER);
    }

    /**
     * @param string $orderNumber
     *
     * @return $this
     */
    public function setOrderNumber($orderNumber = '')
    {
        return $this->setData(self::ORDER_NUMBER, $orderNumber);
    }

    /**
     * @return string|null
     */
    public function getOrderStatus()
    {
        return $this->getData(self::ORDER_STATUS);
    }

    /**
     * @param string $orderStatus
     *
     * @return $this
     */
    public function setOrderStatus($orderStatus = '')
    {
        return $this->setData(self::ORDER_STATUS, $orderStatus);
    }

    /**
     * @return string|null
     */
    public function getOrderDateTime()
    {
        return $this->getData(self::ORDER_DATE_TIME);
    }

    /**
     * @param string $orderDateTime
     *
     * @return $this
     */
    public function setOrderDateTime($orderDateTime = '')
    {
        return $this->setData(self::ORDER_DATE_TIME, $orderDateTime);
    }

    /**
     * @return string|null
     */
    public function getTotal()
    {
        return $this->getData(self::TOTAL);
    }

    /**
     * @param string $total
     *
     * @return $this
     */
    public function setTotal($total = '')
    {
        return $this->setData(self::TOTAL, $total);
    }

    /**
     * @return array|null
     */
    public function getItems()
    {
        return $this->getData(self::ITEMS);
    }

    /**
     * @param array $items
     *
     * @return $this
     */
    public function setItems($items = [])
    {
        return $this->setData(self::ITEMS, $items);
    }

    /**
     * @return array|null
     */
    public function getSubscriptions()
    {
        return $this->getData(self::SUBSCRIPTIONS);
    }

    /**
     * @param array $subscriptions
     *
     * @return $this
     */
    public function setSubscriptions($subscriptions = [])
    {
        return $this->setData(self::SUBSCRIPTIONS, $subscriptions);
    }

    /**
     * @return string|null
     */
    public function getCreated()
    {
        return $this->getData(self::CREATED);
    }

    /**
     * @param string $created
     *
     * @return $this
     */
    public function setCreated($created = '')
    {
        return $this->setData(self::CREATED, $created);
    }

    /**
     * @return string|null
     */
    public function getUpdated()
    {
        return $this->getData(self::UPDATED);
    }

    /**
     * @param string $updated
     *
     * @return $this
     */
    public function setUpdated($updated = '')
    {
        return $this->setData(self::UPDATED, $updated);
    }
}
