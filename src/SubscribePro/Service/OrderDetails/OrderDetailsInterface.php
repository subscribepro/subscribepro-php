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
     * Data fields in camelCase (will be refactored by the Subscribe Pro platform in future releases)
     */
    const CUSTOMER_ID = 'customerId';
    const CUSTOMER_EMAIL = 'customerEmail';
    const PLATFORM_CUSTOMER_ID = 'platformCustomerId';
    const PLATFORM_ORDER_ID = 'platformOrderId';
    const ORDER_NUMBER = 'orderNumber';
    const SALES_ORDER_TOKEN = 'salesOrderToken';
    const ORDER_STATUS = 'orderStatus';
    const ORDER_STATE = 'orderState';
    const ORDER_DATE_TIME = 'orderDateTime';
    const CURRENCY = 'currency';
    const DISCOUNT_TOTAL = 'discountTotal';
    const SHIPPING_TOTAL = 'shippingTotal';
    const TAX_TOTAL = 'taxTotal';
    const ORDER_TOTAL = 'total';
    const BILLING_ADDRESS = 'billingAddress';
    const SHIPPING_ADDRESS = 'shippingAddress';
    const ITEMS = 'items';

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
