<?php

namespace SubscribePro\Service\Subscription;

use SubscribePro\Service\DataInterface;

interface SubscriptionInterface extends DataInterface
{
    /**
     * Data fields
     */
    public const ID = 'id';
    public const CUSTOMER_ID = 'customer_id';
    public const STATUS = 'status';
    public const PRODUCT_SKU = 'product_sku';
    public const REQUIRES_SHIPPING = 'requires_shipping';
    public const SUBSCRIPTION_PRODUCTS = 'subscription_products';
    public const QTY = 'qty';
    public const USE_FIXED_PRICE = 'use_fixed_price';
    public const FIXED_PRICE = 'fixed_price';
    public const INTERVAL = 'interval';
    public const MAGENTO_STORE_CODE = 'magento_store_code';
    public const PAYMENT_PROFILE_ID = 'payment_profile_id';
    public const PAYMENT_PROFILE = 'payment_profile';
    public const PAYMENT_METHOD_CODE = 'payment_method_code';
    public const AUTHORIZE_NET_PAYMENT_PROFILE_ID = 'authorize_net_payment_profile_id';
    public const CREDITCARD_LAST_DIGITS = 'creditcard_last_digits';
    public const MAGENTO_BILLING_ADDRESS_ID = 'magento_billing_address_id';
    public const SHIPPING_ADDRESS_ID = 'shipping_address_id';
    public const SHIPPING_ADDRESS = 'shipping_address';
    public const BILLING_ADDRESS_ID = 'billing_address_id';
    public const BILLING_ADDRESS = 'billing_address';

    public const MAGENTO_SHIPPING_ADDRESS_ID = 'magento_shipping_address_id';
    public const MAGENTO_SHIPPING_METHOD_CODE = 'magento_shipping_method_code';
    public const SHIPPING_METHOD_CODE = 'shipping_method_code';
    public const SEND_CUSTOMER_NOTIFICATION_EMAIL = 'send_customer_notification_email';
    public const FIRST_ORDER_ALREADY_CREATED = 'first_order_already_created';
    public const NEXT_ORDER_DATE = 'next_order_date';
    public const LAST_ORDER_DATE = 'last_order_date';
    public const EXPIRATION_DATE = 'expiration_date';
    public const COUPON_CODE = 'coupon_code';
    public const USER_DEFINED_FIELDS = 'user_defined_fields';
    public const PLATFORM_SPECIFIC_FIELDS = 'platform_specific_fields';
    public const RECURRING_ORDER_COUNT = 'recurring_order_count';
    public const ERROR_TIME = 'error_time';
    public const ERROR_CLASS = 'error_class';
    public const ERROR_CLASS_DESCRIPTION = 'error_class_description';
    public const ERROR_TYPE = 'error_type';
    public const ERROR_MESSAGE = 'error_message';
    public const FAILED_ORDER_ATTEMPT_COUNT = 'failed_order_attempt_count';
    public const RETRY_AFTER = 'retry_after';
    public const CREATED = 'created';
    public const UPDATED = 'updated';
    public const CANCELLED = 'cancelled';
    public const ORDER_ITEM_ID = 'order_item_id';

    /**
     * Subscription statuses
     */
    public const STATUS_ACTIVE = 'Active';
    public const STATUS_CANCELLED = 'Cancelled';
    public const STATUS_EXPIRED = 'Expired';
    public const STATUS_RETRY = 'Retry';
    public const STATUS_FAILED = 'Failed';
    public const STATUS_PAUSED = 'Paused';

    /**
     * @return mixed[]
     */
    public function getFormData();

    /**
     * @param int|null $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * @return string|null
     */
    public function getCustomerId();

    /**
     * @param string $customerId
     *
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * Subscription status: Active, Cancelled, Expired, Retry, Failed or Paused
     *
     * @return string|null
     */
    public function getStatus();

    /**
     * @return string|null
     */
    public function getProductSku();

    /**
     * @return bool
     */
    public function getRequiresShipping();

    /**
     * @param bool $useShipping
     *
     * @return $this
     */
    public function setRequiresShipping($useShipping);

    /**
     * @param string $productSku
     *
     * @return $this
     */
    public function setProductSku($productSku);

    /**
     * @return mixed[]
     */
    public function getSubscriptionProducts();

    /**
     * @return int|null
     */
    public function getQty();

    /**
     * @param int $qty
     *
     * @return $this
     */
    public function setQty($qty);

    /**
     * @return bool|null
     */
    public function getUseFixedPrice();

    /**
     * @param bool $useFixedPrice
     *
     * @return $this
     */
    public function setUseFixedPrice($useFixedPrice);

    /**
     * @return float|null
     */
    public function getFixedPrice();

    /**
     * @param float $fixedPrice
     *
     * @return $this
     */
    public function setFixedPrice($fixedPrice);

    /**
     * @return string|null
     */
    public function getInterval();

    /**
     * @param string $interval
     *
     * @return $this
     */
    public function setInterval($interval);

    /**
     * @return string|null
     */
    public function getMagentoStoreCode();

    /**
     * @param string $magentoStoreCode
     *
     * @return $this
     */
    public function setMagentoStoreCode($magentoStoreCode);

    /**
     * @return int|null
     */
    public function getPaymentProfileId();

    /**
     * @param int $paymentProfileId
     *
     * @return $this
     */
    public function setPaymentProfileId($paymentProfileId);

    /**
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     */
    public function getPaymentProfile();

    /**
     * @return string|null
     */
    public function getPaymentMethodCode();

    /**
     * @param string $paymentMethodCode
     *
     * @return $this
     */
    public function setPaymentMethodCode($paymentMethodCode);

    /**
     * @return string|null
     */
    public function getAuthorizeNetPaymentProfileId();

    /**
     * @return string|null
     */
    public function getCreditcardLastDigits();

    /**
     * @return int|null
     */
    public function getMagentoBillingAddressId();

    /**
     * @return int|null
     */
    public function getShippingAddressId();

    /**
     * @param int $shippingAddressId
     *
     * @return $this
     */
    public function setShippingAddressId($shippingAddressId);

    /**
     * @return \SubscribePro\Service\Address\AddressInterface|null
     */
    public function getShippingAddress();

    /**
     * @param \SubscribePro\Service\Address\AddressInterface|null $shippingAddress
     *
     * @return $this
     */
    public function setShippingAddress($shippingAddress);

    /**
     * @return int|null
     */
    public function getMagentoShippingAddressId();

    /**
     * @return string|null
     */
    public function getMagentoShippingMethodCode();

    /**
     * @param string $magentoShippingMethodCode
     *
     * @return $this
     */
    public function setMagentoShippingMethodCode($magentoShippingMethodCode);

    /**
     * @return string|null
     */
    public function getShippingMethodCode();

    /**
     * @param string $shippingMethodCode
     *
     * @return $this
     */
    public function setShippingMethodCode($shippingMethodCode);

    /**
     * @return bool|null
     */
    public function getSendCustomerNotificationEmail();

    /**
     * @param bool $sendCustomerNotificationEmail
     *
     * @return $this
     */
    public function setSendCustomerNotificationEmail($sendCustomerNotificationEmail);

    /**
     * @return bool|null
     */
    public function getFirstOrderAlreadyCreated();

    /**
     * @param bool $firstOrderAlreadyCreated
     *
     * @return $this
     */
    public function setFirstOrderAlreadyCreated($firstOrderAlreadyCreated);

    /**
     * @param string $nextOrderDate
     *
     * @return $this
     */
    public function setNextOrderDate($nextOrderDate);

    /**
     * @param string|null $format
     *
     * @return string|null
     */
    public function getNextOrderDate($format = null);

    /**
     * @return string|null
     */
    public function getOrderItemId();

    /**
     * @param string $orderItemId
     *
     * @return $this
     */
    public function setOrderItemId($orderItemId);

    /**
     * @param string|null $format
     *
     * @return string|null
     */
    public function getLastOrderDate($format = null);

    /**
     * @param string|null $format
     *
     * @return string|null
     */
    public function getExpirationDate($format = null);

    /**
     * @param string $expirationDate
     *
     * @return $this
     */
    public function setExpirationDate($expirationDate);

    /**
     * @return string|null
     */
    public function getCouponCode();

    /**
     * @param string $couponCode
     *
     * @return $this
     */
    public function setCouponCode($couponCode);

    /**
     * @return mixed[]
     */
    public function getUserDefinedFields();

    /**
     * @param array $userDefinedFields
     *
     * @return $this
     */
    public function setUserDefinedFields(array $userDefinedFields);

    /**
     * @return mixed[]
     */
    public function getPlatformSpecificFields();

    /**
     * @param array $platformSpecificFields
     *
     * @return $this
     */
    public function setPlatformSpecificFields(array $platformSpecificFields);

    /**
     * @param string|null $format
     *
     * @return string|null
     */
    public function getErrorTime($format = null);

    /**
     * @return string|null
     */
    public function getErrorClass();

    /**
     * @return string|null
     */
    public function getErrorClassDescription();

    /**
     * @return string|null
     */
    public function getErrorType();

    /**
     * @return string|null
     */
    public function getErrorMessage();

    /**
     * @return int|null
     */
    public function getFailedOrderAttemptCount();

    /**
     * @param string|null $format
     *
     * @return string|null
     */
    public function getRetryAfter($format = null);

    /**
     * @return int|null
     */
    public function getRecurringOrderCount();

    /**
     * @param string|null $format
     *
     * @return string|null
     */
    public function getCreated($format = null);

    /**
     * @param string|null $format
     *
     * @return string|null
     */
    public function getUpdated($format = null);

    /**
     * @param string|null $format
     *
     * @return string|null
     */
    public function getCancelled($format = null);
}
