<?php

namespace SubscribePro\Service\Subscription;

use SubscribePro\Service\DataObject;
use SubscribePro\Service\Address\AddressInterface;
use SubscribePro\Service\PaymentProfile\PaymentProfileInterface;

class Subscription extends DataObject implements SubscriptionInterface
{
    /**
     * @var string
     */
    protected $idField = self::ID;

    /**
     * @var array
     */
    protected $creatingFields = [
        self::CUSTOMER_ID => true,
        self::PAYMENT_PROFILE_ID => true,
        self::SHIPPING_ADDRESS_ID => false,
        self::SHIPPING_ADDRESS => false,
        self::PRODUCT_SKU => true,
        self::QTY => true,
        self::USE_FIXED_PRICE => true,
        self::FIXED_PRICE => false,
        self::INTERVAL => true,
        self::NEXT_ORDER_DATE => true,
        self::FIRST_ORDER_ALREADY_CREATED => false,
        self::SEND_CUSTOMER_NOTIFICATION_EMAIL => false,
        self::EXPIRATION_DATE => false,
        self::COUPON_CODE => false,
        self::MAGENTO_STORE_CODE => false,
        self::MAGENTO_SHIPPING_METHOD_CODE => true,
        self::USER_DEFINED_FIELDS => false
    ];

    /**
     * @var array
     */
    protected $updatingFields = [
        self::PAYMENT_PROFILE_ID => true,
        self::SHIPPING_ADDRESS_ID => false,
        self::SHIPPING_ADDRESS => false,
        self::PRODUCT_SKU => true,
        self::QTY => true,
        self::USE_FIXED_PRICE => true,
        self::FIXED_PRICE => false,
        self::INTERVAL => true,
        self::NEXT_ORDER_DATE => true,
        self::SEND_CUSTOMER_NOTIFICATION_EMAIL => false,
        self::EXPIRATION_DATE => false,
        self::COUPON_CODE => false,
        self::MAGENTO_STORE_CODE => false,
        self::MAGENTO_SHIPPING_METHOD_CODE => true,
        self::USER_DEFINED_FIELDS => false
    ];

    /**
     * @param array $data
     * @return $this
     */
    public function importData(array $data = [])
    {
        if (!isset($data[self::SHIPPING_ADDRESS]) || !($data[self::SHIPPING_ADDRESS] instanceof AddressInterface)) {
            $shippingAddressData = isset($data[self::SHIPPING_ADDRESS]) && is_array($data[self::SHIPPING_ADDRESS]) ? $data[self::SHIPPING_ADDRESS] : [];
            $data[self::SHIPPING_ADDRESS] = $this->getShippingAddress()->importData($shippingAddressData);
        }
        if (empty($data[self::SHIPPING_ADDRESS_ID])) {
            $data[self::SHIPPING_ADDRESS_ID] = $data[self::SHIPPING_ADDRESS]->getId();
        }

        if (!isset($data[self::PAYMENT_PROFILE]) || !($data[self::PAYMENT_PROFILE] instanceof PaymentProfileInterface)) {
            $paymentProfileData = isset($data[self::PAYMENT_PROFILE]) && is_array($data[self::PAYMENT_PROFILE]) ? $data[self::PAYMENT_PROFILE] : [];
            $data[self::PAYMENT_PROFILE] = $this->getPaymentProfile()->importData($paymentProfileData);
        }
        if (empty($data[self::PAYMENT_PROFILE_ID])) {
            $data[self::PAYMENT_PROFILE_ID] = $data[self::PAYMENT_PROFILE]->getId();
        }

        return parent::importData($data);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();

        $data[self::PAYMENT_PROFILE] = $this->getPaymentProfile()->toArray();
        $data[self::SHIPPING_ADDRESS] = $this->getShippingAddress()->toArray();

        return $data;
    }

    /**
     * @return array
     */
    protected function getFormFields()
    {
        return $this->isNew() ? $this->creatingFields : $this->updatingFields;
    }

    /**
     * @return array
     */
    public function getFormData()
    {
        $formData = array_intersect_key($this->data, $this->getFormFields());

        $formData[self::SHIPPING_ADDRESS] = $this->getShippingAddress()->getAsChildFormData($this->isNew());

        if (!empty($formData[self::SHIPPING_ADDRESS_ID])) {
            unset($formData[self::SHIPPING_ADDRESS]);
        } else {
            unset($formData[self::SHIPPING_ADDRESS_ID]);
        }

        return $formData;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return ($this->getData(self::SHIPPING_ADDRESS_ID, false) || $this->getShippingAddress()->isAsChildValid($this->isNew()))
            && $this->checkRequiredFields($this->getFormFields());
    }

    /**
     * @param int|null $id
     * @return $this
     */
    public function setId($id)
    {
        $this->setData($this->idField, $id);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @param string $customerId
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Subscription status: Active, Cancelled, Expired, Retry, Failed or Paused
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @return string|null
     */
    public function getProductSku()
    {
        return $this->getData(self::PRODUCT_SKU);
    }

    /**
     * @param string $productSku
     * @return $this
     */
    public function setProductSku($productSku)
    {
        return $this->setData(self::PRODUCT_SKU, $productSku);
    }

    /**
     * @return array
     */
    public function getSubscriptionProducts()
    {
        return $this->getData(self::SUBSCRIPTION_PRODUCTS, []);
    }

    /**
     * @return int|null
     */
    public function getQty()
    {
        return $this->getData(self::QTY);
    }

    /**
     * @param int $qty
     * @return $this
     */
    public function setQty($qty)
    {
        return $this->setData(self::QTY, $qty);
    }

    /**
     * @return bool|null
     */
    public function getUseFixedPrice()
    {
        return $this->getData(self::USE_FIXED_PRICE);
    }

    /**
     * @param bool $useFixedPrice
     * @return $this
     */
    public function setUseFixedPrice($useFixedPrice)
    {
        return $this->setData(self::USE_FIXED_PRICE, $useFixedPrice);
    }

    /**
     * @return float|null
     */
    public function getFixedPrice()
    {
        return $this->getData(self::FIXED_PRICE);
    }

    /**
     * @param float $fixedPrice
     * @return $this
     */
    public function setFixedPrice($fixedPrice)
    {
        return $this->setData(self::FIXED_PRICE, $fixedPrice);
    }

    /**
     * @return string|null
     */
    public function getInterval()
    {
        return $this->getData(self::INTERVAL);
    }

    /**
     * @param string $interval
     * @return $this
     */
    public function setInterval($interval)
    {
        return $this->setData(self::INTERVAL, $interval);
    }

    /**
     * @return string|null
     */
    public function getMagentoStoreCode()
    {
        return $this->getData(self::MAGENTO_STORE_CODE);
    }

    /**
     * @param string $magentoStoreCode
     * @return $this
     */
    public function setMagentoStoreCode($magentoStoreCode)
    {
        return $this->setData(self::MAGENTO_STORE_CODE, $magentoStoreCode);
    }

    /**
     * @return int|null
     */
    public function getPaymentProfileId()
    {
        return $this->getData(self::PAYMENT_PROFILE_ID);
    }

    /**
     * @param int $paymentProfileId
     * @return $this
     */
    public function setPaymentProfileId($paymentProfileId)
    {
        $this->setData(self::PAYMENT_PROFILE_ID, $paymentProfileId);
        if ($this->getPaymentProfile()->getId() != $paymentProfileId) {
            $this->getPaymentProfile()->importData([PaymentProfileInterface::ID => $paymentProfileId]);
        }
        return $this;
    }

    /**
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     */
    public function getPaymentProfile()
    {
        return $this->getData(self::PAYMENT_PROFILE);
    }

    /**
     * @return string|null
     */
    public function getAuthorizeNetPaymentProfileId()
    {
        return $this->getData(self::AUTHORIZE_NET_PAYMENT_PROFILE_ID);
    }

    /**
     * @return string|null
     */
    public function getCreditcardLastDigits()
    {
        return $this->getData(self::CREDITCARD_LAST_DIGITS);
    }

    /**
     * @return int|null
     */
    public function getMagentoBillingAddressId()
    {
        return $this->getData(self::MAGENTO_BILLING_ADDRESS_ID);
    }

    /**
     * @return int|null
     */
    public function getShippingAddressId()
    {
        return $this->getData(self::SHIPPING_ADDRESS_ID);
    }

    /**
     * @param int $shippingAddressId
     * @return $this
     */
    public function setShippingAddressId($shippingAddressId)
    {
        $this->setData(self::SHIPPING_ADDRESS_ID, $shippingAddressId);
        if ($shippingAddressId && $this->getShippingAddress()->getId() != $shippingAddressId) {
            $this->getShippingAddress()->importData([AddressInterface::ID => $shippingAddressId]);
        }
        return $this;
    }

    /**
     * @return \SubscribePro\Service\Address\AddressInterface
     */
    public function getShippingAddress()
    {
        return $this->getData(self::SHIPPING_ADDRESS);
    }

    /**
     * @param \SubscribePro\Service\Address\AddressInterface $shippingAddress
     * @return $this
     */
    public function setShippingAddress(AddressInterface $shippingAddress)
    {
        return $this->setData(self::SHIPPING_ADDRESS, $shippingAddress);
    }

    /**
     * @return int|null
     */
    public function getMagentoShippingAddressId()
    {
        return $this->getData(self::MAGENTO_SHIPPING_ADDRESS_ID);
    }

    /**
     * @return string|null
     */
    public function getMagentoShippingMethodCode()
    {
        return $this->getData(self::MAGENTO_SHIPPING_METHOD_CODE);
    }

    /**
     * @param string $magentoShippingMethodCode
     * @return $this
     */
    public function setMagentoShippingMethodCode($magentoShippingMethodCode)
    {
        return $this->setData(self::MAGENTO_SHIPPING_METHOD_CODE, $magentoShippingMethodCode);
    }

    /**
     * @return bool|null
     */
    public function getSendCustomerNotificationEmail()
    {
        return $this->getData(self::SEND_CUSTOMER_NOTIFICATION_EMAIL);
    }

    /**
     * @param bool $sendCustomerNotificationEmail
     * @return $this
     */
    public function setSendCustomerNotificationEmail($sendCustomerNotificationEmail)
    {
        return $this->setData(self::SEND_CUSTOMER_NOTIFICATION_EMAIL, $sendCustomerNotificationEmail);
    }

    /**
     * @return bool|null
     */
    public function getFirstOrderAlreadyCreated()
    {
        return $this->getData(self::FIRST_ORDER_ALREADY_CREATED);
    }

    /**
     * @param bool $firstOrderAlreadyCreated
     * @return $this
     */
    public function setFirstOrderAlreadyCreated($firstOrderAlreadyCreated)
    {
        return $this->setData(self::FIRST_ORDER_ALREADY_CREATED, $firstOrderAlreadyCreated);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getNextOrderDate($format = null)
    {
        return $this->getDateData(self::NEXT_ORDER_DATE, $format);
    }

    /**
     * @param string $nextOrderDate
     * @return $this
     */
    public function setNextOrderDate($nextOrderDate)
    {
        return $this->setData(self::NEXT_ORDER_DATE, $nextOrderDate);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getLastOrderDate($format = null)
    {
        return $this->getDateData(self::LAST_ORDER_DATE, $format);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getExpirationDate($format = null)
    {
        return $this->getDateData(self::EXPIRATION_DATE, $format);
    }

    /**
     * @param string $expirationDate
     * @return $this
     */
    public function setExpirationDate($expirationDate)
    {
        return $this->setData(self::EXPIRATION_DATE, $expirationDate);
    }

    /**
     * @return string|null
     */
    public function getCouponCode()
    {
        return $this->getData(self::COUPON_CODE);
    }

    /**
     * @param string $couponCode
     * @return $this
     */
    public function setCouponCode($couponCode)
    {
        return $this->setData(self::COUPON_CODE, $couponCode);
    }

    /**
     * @return array
     */
    public function getUserDefinedFields()
    {
        return $this->getData(self::USER_DEFINED_FIELDS, []);
    }

    /**
     * @param array $userDefinedFields
     * @return $this
     */
    public function setUserDefinedFields(array $userDefinedFields)
    {
        return $this->setData(self::USER_DEFINED_FIELDS, $userDefinedFields);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getErrorTime($format = null)
    {
        return $this->getDatetimeData(self::ERROR_TIME, $format);
    }

    /**
     * @return string|null
     */
    public function getErrorClass()
    {
        return $this->getData(self::ERROR_CLASS);
    }

    /**
     * @return string|null
     */
    public function getErrorClassDescription()
    {
        return $this->getData(self::ERROR_CLASS_DESCRIPTION);
    }

    /**
     * @return string|null
     */
    public function getErrorType()
    {
        return $this->getData(self::ERROR_TYPE);
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        return $this->getData(self::ERROR_MESSAGE);
    }

    /**
     * @return int|null
     */
    public function getFailedOrderAttemptCount()
    {
        return $this->getData(self::FAILED_ORDER_ATTEMPT_COUNT);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getRetryAfter($format = null)
    {
        return $this->getDatetimeData(self::RETRY_AFTER, $format);
    }

    /**
     * @return int|null
     */
    public function getRecurringOrderCount()
    {
        return $this->getData(self::RECURRING_ORDER_COUNT);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getCreated($format = null)
    {
        return $this->getDatetimeData(self::CREATED, $format);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getUpdated($format = null)
    {
        return $this->getDatetimeData(self::UPDATED, $format);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getCancelled($format = null)
    {
        return $this->getDatetimeData(self::CANCELLED, $format);
    }
}
