<?php

namespace SubscribePro\Service\PaymentProfile;

use SubscribePro\Service\DataInterface;
use SubscribePro\Service\Address\AddressInterface;

interface PaymentProfileInterface extends DataInterface
{
    /**
     * Data fields
     */
    const ID = 'id';
    const CUSTOMER_ID = 'customer_id';
    const MAGENTO_CUSTOMER_ID = 'magento_customer_id';
    const CUSTOMER_EMAIL = 'customer_email';
    const CUSTOMER_FACING_NAME = 'customer_facing_name';
    const MERCHANT_FACING_NAME = 'merchant_facing_name';
    const CREDITCARD_TYPE = 'creditcard_type';
    const CREDITCARD_NUMBER = 'creditcard_number';
    const CREDITCARD_FIRST_DIGITS = 'creditcard_first_digits';
    const CREDITCARD_LAST_DIGITS = 'creditcard_last_digits';
    const CREDITCARD_VERIFICATION_VALUE = 'creditcard_verification_value';
    const CREDITCARD_MONTH = 'creditcard_month';
    const CREDITCARD_YEAR = 'creditcard_year';
    const BANK_ROUTING_NUMBER = 'bank_routing_number';
    const BANK_ACCOUNT_NUMBER = 'bank_account_number';
    const BANK_ACCOUNT_LAST_DIGITS = 'bank_account_last_digits';
    const BANK_NAME = 'bank_name';
    const BANK_ACCOUNT_TYPE = 'bank_account_type';
    const BANK_ACCOUNT_HOLDER_TYPE = 'bank_account_holder_type';
    const BILLING_ADDRESS = 'billing_address';
    const PROFILE_TYPE = 'profile_type';
    const GATEWAY = 'gateway';
    const DUAL_VAULT_MODE = 'dual_vault_mode';
    const PAYMENT_METHOD_TYPE = 'payment_method_type';
    const PAYMENT_TOKEN = 'payment_token';
    const PAYMENT_VAULT = 'payment_vault';
    const THIRD_PARTY_VAULT_TYPE = 'third_party_vault_type';
    const THIRD_PARTY_PAYMENT_TOKEN = 'third_party_payment_token';
    const STATUS = 'status';
    const CREATED = 'created';
    const UPDATED = 'updated';

    /**
     * Payment profile statuses
     */
    const STATUS_RETAINED = 'retained';
    const STATUS_REDACTED = 'redacted';

    /**
     * Payment profile types
     */
    const TYPE_EXTERNAL_VAULT = 'external_vault';
    const TYPE_SPREEDLY_DUAL_VAULT = 'spreedly_dual_vault';
    const TYPE_SPREEDLY_VAULT = 'spreedly_vault';

    /**
     * Payment method types
     */
    const TYPE_CREDIT_CARD = 'credit_card';
    const TYPE_THIRD_PARTY_TOKEN = 'third_party_token';
    const TYPE_BANK_ACCOUNT = 'bank_account';
    const TYPE_APPLE_PAY = 'apple_pay';
    const TYPE_ANDROID_PAY = 'android_pay';

    /**
     * Credit card types
     */
    const CC_TYPE_VISA = 'visa';
    const CC_TYPE_MASTER = 'master';
    const CC_TYPE_AMERICAN_EXPRESS = 'american_express';
    const CC_TYPE_DISCOVER = 'discover';
    const CC_TYPE_JCB = 'jcb';
    const CC_TYPE_DINERS_CLUB = 'diners_club';
    const CC_TYPE_DANKORT = 'dankort';

    /**
     * @return array
     */
    public function getFormData();

    /**
     * @return array
     */
    public function getThirdPartyTokenCreatingFormData();

    /**
     * @return array
     */
    public function getThirdPartyTokenSavingFormData();

    /**
     * @return array
     */
    public function getBankAccountCreatingFormData();

    /**

    /**
     * @return array
     */
    public function getTokenFormData();

    /**
     * @param string
     * @param string|null
     * @return bool
     */
    public function isType($profileType, $paymentMethodType = null);

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
     * @return string|null
     */
    public function getMagentoCustomerId();

    /**
     * @param string $magentoCustomerId
     * @return $this
     */
    public function setMagentoCustomerId($magentoCustomerId);

    /**
     * @return string|null
     */
    public function getCustomerEmail();

    /**
     * @return string|null
     */
    public function getCustomerFacingName();

    /**
     * @return string|null
     */
    public function getMerchantFacingName();

    /**
     * Credit card type: visa, master, american_express, discover, jcb, diners_club or dankort
     *
     * @return string|null
     */
    public function getCreditcardType();

    /**
     * Credit card type: visa, master, american_express, discover, jcb, diners_club or dankort
     *
     * @param string $creditcardType
     * @return $this
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setCreditcardType($creditcardType);

    /**
     * @return string
     */
    public function getCreditcardNumber();

    /**
     * @param string $creditcardNumber
     * @return $this
     */
    public function setCreditcardNumber($creditcardNumber);

    /**
     * @return string
     */
    public function getCreditcardFirstDigits();

    /**
     * @param string $creditcardFirstDigits
     * @return $this
     */
    public function setCreditcardFirstDigits($creditcardFirstDigits);

    /**
     * @param string $creditcardLastDigits
     * @return $this
     */
    public function setCreditcardLastDigits($creditcardLastDigits);

    /**
     * @return string
     */
    public function getCreditcardLastDigits();

    /**
     * @return string|null
     */
    public function getCreditcardVerificationValue();

    /**
     * @param string $creditcardVerificationValue
     * @return $this
     */
    public function setCreditcardVerificationValue($creditcardVerificationValue);

    /**
     * @return string
     */
    public function getCreditcardMonth();

    /**
     * @param string $creditcardMonth
     * @return $this
     */
    public function setCreditcardMonth($creditcardMonth);

    /**
     * @return string
     */
    public function getCreditcardYear();

    /**
     * @param string $creditcardYear
     * @return $this
     */
    public function setCreditcardYear($creditcardYear);

    /**
     * @return string
     */
    public function getBankRoutingNumber();

    /**
     * @param string $routingNumber
     * @return $this
     */
    public function setBankRoutingNumber($routingNumber);

    /**
       * @return string
       */
    public function getBankAccountNumber();

    /**
     * @param string $accountNumber
     * @return $this
     */
    public function setBankAccountNumber($accountNumber);

    /**
     * @return string
     */
    public function getBankAccountLastDigits();

    /**
     * @return string
     */
    public function getBankName();

    /**
     * @param string $name
     * @return $this
     */
    public function setBankName($name);

    /**
     * @return string
     */
    public function getBankAccountType();

    /**
     * @param string $type
     * @return $this
     */
    public function setBankAccountType($type);

    /**
     * @return string
     */
    public function getBankAccountHolderType();

    /**
     * @param string $holderType
     * @return $this
     */
    public function setBankAccountHolderType($holderType);

    /**
     * @return \SubscribePro\Service\Address\AddressInterface
     */
    public function getBillingAddress();

    /**
     * @param \SubscribePro\Service\Address\AddressInterface $billingAddress
     * @return $this
     */
    public function setBillingAddress(AddressInterface $billingAddress);

    /**
     * @return string
     */
    public function getProfileType();

    /**
     * @return string
     */
    public function getGateway();

    /**
     * @return boolean
     */
    public function getDualVaultMode();

    /**
     * Payment method type: credit_card or third_party_token
     *
     * @return string|null
     */
    public function getPaymentMethodType();

    /**
     * @return string|null
     */
    public function getPaymentToken();

    /**
     * @return string|null
     */
    public function getPaymentVault();

    /**
     * @return string|null
     */
    public function getThirdPartyVaultType();

    /**
     * @param string $thirdPartyVaultType
     * @return $this
     */
    public function setThirdPartyVaultType($thirdPartyVaultType);

    /**
     * @param string $thirdPartyPaymentToken
     * @return $this
     */
    public function setThirdPartyPaymentToken($thirdPartyPaymentToken);

    /**
     * @return string|null
     */
    public function getThirdPartyPaymentToken();

    /**
     * Current status of the payment profile: retained or redacted
     *
     * @return string
     */
    public function getStatus();

    /**
     * @param null $format
     * @return string
     */
    public function getCreated($format = null);

    /**
     * @param null $format
     * @return string
     */
    public function getUpdated($format = null);
}
