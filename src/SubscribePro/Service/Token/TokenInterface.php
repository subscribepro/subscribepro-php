<?php

namespace SubscribePro\Service\Token;

use SubscribePro\Service\Address\AddressInterface;
use SubscribePro\Service\DataInterface;

interface TokenInterface extends DataInterface
{
    /**
     * Data fields
     */
    public const TOKEN = 'token';
    public const PAYMENT_METHOD_TYPE = 'payment_method_type';

    // Fields which are sent back by service endpoints
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';
    public const FULL_NAME = 'full_name';
    public const ADDRESS1 = 'address1';
    public const ADDRESS2 = 'address2';
    public const CITY = 'city';
    public const STATE = 'state';
    public const ZIP = 'zip';
    public const COUNTRY = 'country';
    public const COMPANY = 'company';
    public const PHONE_NUMBER = 'phone_number';
    public const TEST = 'test';
    public const CARD_TYPE = 'card_type';
    public const MONTH = 'month';
    public const YEAR = 'year';
    public const ELIGIBLE_FOR_CARD_UPDATER = 'eligible_for_card_updater';
    public const STORAGE_STATE = 'storage_state';
    public const LAST_FOUR_DIGITS = 'last_four_digits';
    public const FIRST_SIX_DIGITS = 'first_six_digits';
    public const FINGERPRINT = 'fingerprint';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    // Fields for create POST endpoints
    public const CREDITCARD_NUMBER = 'creditcard_number';
    public const CREDITCARD_VERIFICATION_VALUE = 'creditcard_verification_value';
    public const CREDITCARD_MONTH = 'creditcard_month';
    public const CREDITCARD_YEAR = 'creditcard_year';
    public const APPLEPAY_PAYMENT_DATA = 'applepay_payment_data';
    public const TEST_CARD_NUMBER = 'test_card_number';
    public const BILLING_ADDRESS = 'billing_address';

    /**
     * @return mixed[]
     */
    public function getFormData();

    /**
     * @return string|null
     */
    public function getToken();

    /**
     * @return string|null
     */
    public function getPaymentMethodType();

    /**
     * @return string|null
     */
    public function getCardType();

    /**
     * @return string|null
     */
    public function getMonth();

    /**
     * @return string|null
     */
    public function getYear();

    /**
     * @return string|null
     */
    public function getCreditCardNumber();

    /**
     * @param string $number
     *
     * @return $this
     */
    public function setCreditCardNumber($number);

    /**
     * @return string|null
     */
    public function getLastFourDigits();

    /**
     * @return string|null
     */
    public function getFirstSixDigits();

    /**
     * @return string|null
     */
    public function getCreditCardVerificationValue();

    /**
     * @param string $verificationValue
     *
     * @return $this
     */
    public function setCreditCardVerificationValue($verificationValue);

    /**
     * @return string|null
     */
    public function getCreditCardMonth();

    /**
     * @param string $month
     *
     * @return $this
     */
    public function setCreditCardMonth($month);

    /**
     * @return string|null
     */
    public function getCreditCardYear();

    /**
     * @param string $year
     *
     * @return $this
     */
    public function setCreditCardYear($year);

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
     * @return string|null
     */
    public function getFirstName();

    /**
     * @return string|null
     */
    public function getLastName();

    /**
     * @return string|null
     */
    public function getFullName();

    /**
     * @return string|null
     */
    public function getAddress();

    /**
     * @return string|null
     */
    public function getCity();

    /**
     * @return string|null
     */
    public function getState();

    /**
     * @return string|null
     */
    public function getZip();

    /**
     * @return string|null
     */
    public function getCountry();

    /**
     * @return string|null
     */
    public function getPhoneNumber();

    /**
     * @param AddressInterface $address
     *
     * @return $this
     */
    public function setBillingAddress(AddressInterface $address);

    /**
     * @return string|null
     */
    public function getEligibleForCardUpdater();

    /**
     * @return string|null
     */
    public function getStorageState();

    /**
     * @return string|null
     */
    public function getFingerprint();

    /**
     * @param string|null $format
     *
     * @return string
     */
    public function getCreatedAt($format = null);

    /**
     * @param string|null $format
     *
     * @return string
     */
    public function getUpdatedAt($format = null);
}
