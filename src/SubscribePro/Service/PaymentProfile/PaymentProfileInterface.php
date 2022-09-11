<?php

namespace SubscribePro\Service\PaymentProfile;

use SubscribePro\Service\Address\AddressInterface;
use SubscribePro\Service\DataInterface;

interface PaymentProfileInterface extends DataInterface
{
    /**
     * Data fields
     */
    public const ID = 'id';
    public const CUSTOMER_ID = 'customer_id';
    public const MAGENTO_CUSTOMER_ID = 'magento_customer_id';
    public const TRANSACTION_ID = 'transaction_id';
    public const CUSTOMER_EMAIL = 'customer_email';
    public const CUSTOMER_FACING_NAME = 'customer_facing_name';
    public const MERCHANT_FACING_NAME = 'merchant_facing_name';
    public const CREDITCARD_TYPE = 'creditcard_type';
    public const CREDITCARD_NUMBER = 'creditcard_number';
    public const CREDITCARD_FIRST_DIGITS = 'creditcard_first_digits';
    public const CREDITCARD_LAST_DIGITS = 'creditcard_last_digits';
    public const CREDITCARD_VERIFICATION_VALUE = 'creditcard_verification_value';
    public const CREDITCARD_MONTH = 'creditcard_month';
    public const CREDITCARD_YEAR = 'creditcard_year';
    public const BANK_ROUTING_NUMBER = 'bank_routing_number';
    public const BANK_ACCOUNT_NUMBER = 'bank_account_number';
    public const BANK_ACCOUNT_LAST_DIGITS = 'bank_account_last_digits';
    public const BANK_NAME = 'bank_name';
    public const BANK_ACCOUNT_TYPE = 'bank_account_type';
    public const BANK_ACCOUNT_HOLDER_TYPE = 'bank_account_holder_type';
    public const APPLEPAY_PAYMENT_DATA = 'applepay_payment_data';
    public const TEST_CARD_NUMBER = 'test_card_number';
    public const BILLING_ADDRESS = 'billing_address';
    public const PROFILE_TYPE = 'profile_type';
    public const GATEWAY = 'gateway';
    public const DUAL_VAULT_MODE = 'dual_vault_mode';
    public const PAYMENT_METHOD_TYPE = 'payment_method_type';
    public const PAYMENT_TOKEN = 'payment_token';
    public const PAYMENT_VAULT = 'payment_vault';
    public const THIRD_PARTY_VAULT_TYPE = 'third_party_vault_type';
    public const THIRD_PARTY_PAYMENT_TOKEN = 'third_party_payment_token';
    public const THREE_DS_STATUS = 'three_ds_status';
    public const STATUS = 'status';
    public const CREATED = 'created';
    public const UPDATED = 'updated';
    public const VAULT_SPECIFIC_FIELDS = 'vault_specific_fields';

    /**
     * Payment profile statuses
     */
    public const STATUS_RETAINED = 'retained';
    public const STATUS_REDACTED = 'redacted';

    /**
     * Payment profile types
     */
    public const TYPE_EXTERNAL_VAULT = 'external_vault';
    public const TYPE_SPREEDLY_DUAL_VAULT = 'spreedly_dual_vault';
    public const TYPE_SPREEDLY_VAULT = 'spreedly_vault';

    /**
     * Payment method types
     */
    public const TYPE_CREDIT_CARD = 'credit_card';
    public const TYPE_THIRD_PARTY_TOKEN = 'third_party_token';
    public const TYPE_BANK_ACCOUNT = 'bank_account';
    public const TYPE_APPLE_PAY = 'apple_pay';
    public const TYPE_ANDROID_PAY = 'android_pay';

    /**
     * Credit card types
     */
    public const CC_TYPE_VISA = 'visa';
    public const CC_TYPE_MASTER = 'master';
    public const CC_TYPE_AMERICAN_EXPRESS = 'american_express';
    public const CC_TYPE_DISCOVER = 'discover';
    public const CC_TYPE_JCB = 'jcb';
    public const CC_TYPE_DINERS_CLUB = 'diners_club';
    public const CC_TYPE_DANKORT = 'dankort';

    public const THREE_DS_NONE = 'none';
    public const THREE_DS_PENDING_AUTHENTICATION = 'pending_authentication';
    public const THREE_DS_AUTHENTICATED = 'authenticated';
    public const THREE_DS_AUTHENTICATION_FAILED = 'authentication_failed';

    /**
     * @return mixed[]
     */
    public function getFormData();

    /**
     * @return mixed[]
     */
    public function getThirdPartyTokenCreatingFormData();

    /**
     * @return mixed[]
     */
    public function getThirdPartyTokenSavingFormData();

    /**
     * @return mixed[]
     */
    public function getBankAccountCreatingFormData();

    /**
     * @return mixed[]
     */
    public function getBankAccountSavingFormData();

    /**
     * @return mixed[]
     */
    public function getApplePayCreatingFormData();

    /**
     * @return mixed[]
     */
    public function getApplePaySavingFormData();

    /**
     * @return mixed[]
     */
    public function getTokenFormData();

    /**
     * @param string
     * @param string|null
     *
     * @return bool
     */
    public function isType($profileType, $paymentMethodType = null);

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
     * @return string|null
     */
    public function getMagentoCustomerId();

    /**
     * @param string $magentoCustomerId
     *
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
     *
     * @return $this
     *
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setCreditcardType($creditcardType);

    /**
     * @return string
     */
    public function getCreditcardNumber();

    /**
     * @param string $creditcardNumber
     *
     * @return $this
     */
    public function setCreditcardNumber($creditcardNumber);

    /**
     * @return string
     */
    public function getCreditcardFirstDigits();

    /**
     * @param string $creditcardFirstDigits
     *
     * @return $this
     */
    public function setCreditcardFirstDigits($creditcardFirstDigits);

    /**
     * @param string $creditcardLastDigits
     *
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
     *
     * @return $this
     */
    public function setCreditcardVerificationValue($creditcardVerificationValue);

    /**
     * @return string
     */
    public function getCreditcardMonth();

    /**
     * @param string $creditcardMonth
     *
     * @return $this
     */
    public function setCreditcardMonth($creditcardMonth);

    /**
     * @return string
     */
    public function getCreditcardYear();

    /**
     * @param string $creditcardYear
     *
     * @return $this
     */
    public function setCreditcardYear($creditcardYear);

    /**
     * @return string
     */
    public function getBankRoutingNumber();

    /**
     * @param string $routingNumber
     *
     * @return $this
     */
    public function setBankRoutingNumber($routingNumber);

    /**
     * @return string
     */
    public function getBankAccountNumber();

    /**
     * @param string $accountNumber
     *
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
     *
     * @return $this
     */
    public function setBankName($name);

    /**
     * @return string
     */
    public function getBankAccountType();

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setBankAccountType($type);

    /**
     * @return string
     */
    public function getBankAccountHolderType();

    /**
     * @param string $holderType
     *
     * @return $this
     */
    public function setBankAccountHolderType($holderType);

    /**
     * @return mixed[]|null
     */
    public function getApplePayPaymentData();

    /**
     * @param mixed[]|null $applePayPaymentData
     *
     * @return $this
     */
    public function setApplePayPaymentData($applePayPaymentData);

    /**
     * @return string
     */
    public function getTestCardNumber();

    /**
     * @param string $testCardNumber
     *
     * @return $this
     */
    public function setTestCardNumber($testCardNumber);

    /**
     * @return \SubscribePro\Service\Address\AddressInterface
     */
    public function getBillingAddress();

    /**
     * @param \SubscribePro\Service\Address\AddressInterface $billingAddress
     *
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
     * @return bool
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
     *
     * @return $this
     */
    public function setThirdPartyVaultType($thirdPartyVaultType);

    /**
     * @param string $thirdPartyPaymentToken
     *
     * @return $this
     */
    public function setThirdPartyPaymentToken($thirdPartyPaymentToken);

    /**
     * @return string|null
     */
    public function getThirdPartyPaymentToken();

    /**
     * @return string
     */
    public function getThreeDsStatus();

    /**
     * Current status of the payment profile: retained or redacted
     *
     * @return string
     */
    public function getStatus();

    /**
     * @param null $format
     *
     * @return string
     */
    public function getCreated($format = null);

    /**
     * @param null $format
     *
     * @return string
     */
    public function getUpdated($format = null);
}
