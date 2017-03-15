<?php

namespace SubscribePro\Service\Token;

use SubscribePro\Service\DataInterface;

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
    const BILLING_ADDRESS = 'billing_address';
    const LAST_FOUR_DIGITS = 'last_four_digits';
    const FIRST_SIX_DIGITS = 'first_six_digits';
    const ELIGIBLE_FOR_CARD_UPDATER = 'eligible_for_card_updater';
    const STORAGE_STATE = 'storage_state';
    const TEST = 'test';
    const FINGERPRINT = 'fingerprint';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    /**
     * @return array
     */
    public function getFormData();

    /**
     * @return bool
     */
    public function isValid();

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
    public function getTest();

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
