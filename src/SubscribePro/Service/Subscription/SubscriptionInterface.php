<?php

namespace SubscribePro\Service\Subscription;

use SubscribePro\Service\DataInterface;
use SubscribePro\Service\Address\AddressInterface;

interface SubscriptionInterface extends DataInterface
{
    /**
     * Data fields
     */
    const ID = 'id';
    const CUSTOMER_ID = 'customer_id';
    const STATUS = 'status';
    const PRODUCT_SKU = 'product_sku';
    const SUBSCRIPTION_PRODUCTS = 'subscription_products';
    const QTY = 'qty';
    const USE_FIXED_PRICE = 'use_fixed_price';
    const FIXED_PRICE = 'fixed_price';
    const INTERVAL = 'interval';
    const MAGENTO_STORE_CODE = 'magento_store_code';
    const PAYMENT_PROFILE_ID = 'payment_profile_id';
    const PAYMENT_PROFILE = 'payment_profile';
    const AUTHORIZE_NET_PAYMENT_PROFILE_ID = 'authorize_net_payment_profile_id';
    const CREDITCARD_LAST_DIGITS = 'creditcard_last_digits';
    const MAGENTO_BILLING_ADDRESS_ID = 'magento_billing_address_id';
    const SHIPPING_ADDRESS_ID = 'shipping_address_id';
    const SHIPPING_ADDRESS = 'shipping_address';
    const MAGENTO_SHIPPING_ADDRESS_ID = 'magento_shipping_address_id';
    const MAGENTO_SHIPPING_METHOD_CODE = 'magento_shipping_method_code';
    const SEND_CUSTOMER_NOTIFICATION_EMAIL = 'send_customer_notification_email';
    const FIRST_ORDER_ALREADY_CREATED = 'first_order_already_created';
    const NEXT_ORDER_DATE = 'next_order_date';
    const LAST_ORDER_DATE = 'last_order_date';
    const EXPIRATION_DATE = 'expiration_date';
    const COUPON_CODE = 'coupon_code';
    const USER_DEFINED_FIELDS = 'user_defined_fields';
    const RECURRING_ORDER_COUNT = 'recurring_order_count';
    const ERROR_TIME = 'error_time';
    const ERROR_CLASS = 'error_class';
    const ERROR_CLASS_DESCRIPTION = 'error_class_description';
    const ERROR_TYPE = 'error_type';
    const ERROR_MESSAGE = 'error_message';
    const FAILED_ORDER_ATTEMPT_COUNT = 'failed_order_attempt_count';
    const RETRY_AFTER = 'retry_after';
    const CREATED = 'created';
    const UPDATED = 'updated';
    const CANCELLED = 'cancelled';

    /**
     * Subscription statuses
     */
    const STATUS_ACTIVE = 'Active';
    const STATUS_CANCELLED = 'Cancelled';
    const STATUS_EXPIRED = 'Expired';
    const STATUS_RETRY = 'Retry';
    const STATUS_FAILED = 'Failed';
    const STATUS_PAUSED = 'Paused';


    /**
     * @return array
     */
    public function getFormData();

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @param int|null $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string|null
     */
    public function getCustomerId();

    /**
     * @param string $customerId
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
     * @param string $productSku
     * @return $this
     */
    public function setProductSku($productSku);

    /**
     * @return array
     */
    public function getSubscriptionProducts();

    /**
     * @return int|null
     */
    public function getQty();

    /**
     * @param int $qty
     * @return $this
     */
    public function setQty($qty);

    /**
     * @return bool|null
     */
    public function getUseFixedPrice();

    /**
     * @param bool $useFixedPrice
     * @return $this
     */
    public function setUseFixedPrice($useFixedPrice);

    /**
     * @return float|null
     */
    public function getFixedPrice();

    /**
     * @param float $fixedPrice
     * @return $this
     */
    public function setFixedPrice($fixedPrice);

    /**
     * @return string|null
     */
    public function getInterval();

    /**
     * @param string $interval
     * @return $this
     */
    public function setInterval($interval);

    /**
     * @return string|null
     */
    public function getMagentoStoreCode();

    /**
     * @param string $magentoStoreCode
     * @return $this
     */
    public function setMagentoStoreCode($magentoStoreCode);

    /**
     * @return int|null
     */
    public function getPaymentProfileId();

    /**
     * @param int $paymentProfileId
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
     * @return $this
     */
    public function setShippingAddressId($shippingAddressId);

    /**
     * @return \SubscribePro\Service\Address\AddressInterface
     */
    public function getShippingAddress();

    /**
     * @param \SubscribePro\Service\Address\AddressInterface $shippingAddress
     * @return $this
     */
    public function setShippingAddress(AddressInterface $shippingAddress);

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
     * @return $this
     */
    public function setMagentoShippingMethodCode($magentoShippingMethodCode);

    /**
     * @return bool|null
     */
    public function getSendCustomerNotificationEmail();

    /**
     * @param bool $sendCustomerNotificationEmail
     * @return $this
     */
    public function setSendCustomerNotificationEmail($sendCustomerNotificationEmail);

    /**
     * @return bool|null
     */
    public function getFirstOrderAlreadyCreated();

    /**
     * @param bool $firstOrderAlreadyCreated
     * @return $this
     */
    public function setFirstOrderAlreadyCreated($firstOrderAlreadyCreated);

    /**
     * @param string $nextOrderDate
     * @return $this
     */
    public function setNextOrderDate($nextOrderDate);

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getNextOrderDate($format = null);

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getLastOrderDate($format = null);

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getExpirationDate($format = null);

    /**
     * @param string $expirationDate
     * @return $this
     */
    public function setExpirationDate($expirationDate);

    /**
     * @return string|null
     */
    public function getCouponCode();

    /**
     * @param string $couponCode
     * @return $this
     */
    public function setCouponCode($couponCode);

    /**
     * @return array
     */
    public function getUserDefinedFields();

    /**
     * @param array $userDefinedFields
     * @return $this
     */
    public function setUserDefinedFields(array $userDefinedFields);

    /**
     * @param string|null $format
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
     * @return string|null
     */
    public function getRetryAfter($format = null);

    /**
     * @return int|null
     */
    public function getRecurringOrderCount();

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getCreated($format = null);

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getUpdated($format = null);

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getCancelled($format = null);
}
