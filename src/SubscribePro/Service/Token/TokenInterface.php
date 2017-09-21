<?php

namespace SubscribePro\Service\Token;

use SubscribePro\Service\DataInterface;
use SubscribePro\Service\Address\AddressInterface;

interface TokenInterface extends DataInterface
{
    /**
     * Data fields
     */
    const TOKEN = 'token';
    const PAYMENT_METHOD_TYPE = 'payment_method_type';
    const CARD_TYPE = 'card_type';
    const CREDITCARD_NUMBER = 'creditcard_number';
    const CREDITCARD_VERIFICATION_VALUE = 'creditcard_verification_value';
    const CREDITCARD_MONTH = 'creditcard_month';
    const CREDITCARD_YEAR = 'creditcard_year';
    const APPLEPAY_PAYMENT_DATA = 'applepay_payment_data';
    const TEST_CARD_NUMBER = 'test_card_number';
    const BILLING_ADDRESS = 'billing_address';

    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const FULL_NAME = 'full_name';
    const ADDRESS1 = 'address1';
    const ADDRESS2 = 'address2';
    const CITY = 'city';
    const STATE = 'state';
    const ZIP = 'zip';
    const COUNTRY = 'country';
    const COMPANY = 'company';
    const PHONE_NUMBER = 'phone_number';
    const TEST = 'test';
    const ELIGIBLE_FOR_CARD_UPDATER = 'eligible_for_card_updater';
    const STORAGE_STATE = 'storage_state';
    const LAST_FOUR_DIGITS = 'last_four_digits';
    const FIRST_SIX_DIGITS = 'first_six_digits';
    const FINGERPRINT = 'fingerprint';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


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
    public function getCreditCardNumber();

    /**
     * @param string $number
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
     * @return $this
     */
    public function setCreditCardVerificationValue($verificationValue);

    /**
     * @return string|null
     */
    public function getCreditCardMonth();

    /**
     * @param string $month
     * @return $this
     */
    public function setCreditCardMonth($month);

    /**
     * @return string|null
     */
    public function getCreditCardYear();

    /**
     * @param string $year
     * @return $this
     */
    public function setCreditCardYear($year);

    /**
     * @return mixed[]|null
     */
    public function getApplePayPaymentData();

    /**
     * @param mixed[]|null $applePayPaymentData
     * @return $this
     */
    public function setApplePayPaymentData($applePayPaymentData);

    /**
     * @return string
     */
    public function getTestCardNumber();

    /**
     * @param string $testCardNumber
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
     * @return string
     */
    public function getCreatedAt($format = null);

    /**
     * @param string|null $format
     * @return string
     */
    public function getUpdatedAt($format = null);
}
