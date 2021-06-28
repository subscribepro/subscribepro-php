<?php

namespace SubscribePro\Service\SalesOrder;

use SubscribePro\Service\DataInterface;

interface SalesOrderInterface extends DataInterface
{
    /**
     * Data fields
     */
    const ID = 'id';
    const CUSTOMER = 'customer';
    const IS_SUBSCRIPTION_ORDER = 'is_subscription_order';
    const ORDER_NUMBER = 'order_number';
    const ORDER_STATUS = 'order_status';
    const ORDER_DATE_TIME = 'order_date_time';
    const TOTAL = 'total';
    const ITEMS = 'items';
    const SUBSCRIPTIONS = 'subscriptions';
    const CREATED = 'created';
    const UPDATED = 'updated';

    /**
     * @return string|null
     */
    public function getId();
    /**
     * @param string $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return SubscribePro\Service\DataInterface|null
     */
    public function getCustomer();
    /**
     * @param SubscribePro\Service\DataInterface $customer
     * @return $this
     */
    public function setCustomer(\SubscribePro\Service\DataInterface $customer);

    /**
     * @return bool
     */
    public function getIsSubscriptionOrder();
    /**
     * @param $bool $isSubscriptionOrder
     * @return $this
     */
    public function setIsSubscriptionOrder($isSubscriptionOrder = false);

    /**
     * @return string|null
     */
    public function getOrderNumber();
    /**
     * @param string $orderNumber
     * @return $this
     */
    public function setOrderNumber($orderNumber = '');

    /**
     * @return string|null
     */
    public function getOrderStatus();
    /**
     * @param string $orderStatus
     * @return $this
     */
    public function setOrderStatus($orderStatus = '');

    /**
     * @return string|null
     */
    public function getOrderDateTime();
    /**
     * @param string $orderDateTime
     * @return $this
     */
    public function setOrderDateTime($orderDateTime =  '');

    /**
     * @return string|null
     */
    public function getTotal();
    /**
     * @param string $total
     * @return $this
     */
    public function setTotal($total = '');

    /**
     * @return array|null
     */
    public function getItems();
    /**
     * @param array $items
     * @return $this
     */
    public function setItems($items = []);

    /**
     * @return array|null
     */
    public function getSubscriptions();
    /**
     * @param array $subscriptions
     * @return $this
     */
    public function setSubscriptions($subscriptions = []);

    /**
     * @return string|null
     */
    public function getCreated();
    /**
     * @param string $created
     * @return $this
     */
    public function setCreated($created = '');

    /**
     * @return string|null
     */
    public function getUpdated();
    /**
     * @param string $updated
     * @return $this
     */
    public function setUpdated($updated = '');
}
