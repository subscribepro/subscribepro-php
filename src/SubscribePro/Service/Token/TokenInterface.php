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
    const NUMBER = 'number';
    const LAST_FOUR_DIGITS = 'last_four_digits';
    const FIRST_SIX_DIGITS = 'first_six_digits';
    const VERIFICATION_VALUE = 'verification_value';
    const MONTH = 'month';
    const YEAR = 'year';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const FULL_NAME = 'full_name';
    const COMPANY = 'company';
    const ADDRESS1 = 'address1';
    const ADDRESS2 = 'address2';
    const CITY = 'city';
    const STATE = 'state';
    const ZIP = 'zip';
    const COUNTRY = 'country';
    const PHONE = 'phone';
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
    public function getNumber();

    /**
     * @param string $number
     * @return $this
     */
    public function setNumber($number);

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
    public function getVerificationValue();

    /**
     * @param string $verificationValue
     * @return $this
     */
    public function setVerificationValue($verificationValue);

    /**
     * @return string|null
     */
    public function getMonth();

    /**
     * @param string $month
     * @return $this
     */
    public function setMonth($month);

    /**
     * @return string|null
     */
    public function getYear();

    /**
     * @param string $year
     * @return $this
     */
    public function setYear($year);

    /**
     * @return string|null
     */
    public function getFirstName();

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName);

    /**
     * @return string|null
     */
    public function getLastName();

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName);

    /**
     * @return string|null
     */
    public function getFullName();

    /**
     * @return string|null
     */
    public function getCompany();

    /**
     * @param string|null $company
     * @return $this
     */
    public function setCompany($company);

    /**
     * @return string|null
     */
    public function getAddress1();

    /**
     * @param string|null $address1
     * @return $this
     */
    public function setAddress1($address1);

    /**
     * @return string|null
     */
    public function getAddress2();

    /**
     * @param string|null $address2
     * @return $this
     */
    public function setAddress2($address2);

    /**
     * @return string|null
     */
    public function getCity();

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city);

    /**
     * @return string|null
     */
    public function getState();

    /**
     * @param string $state
     * @return $this
     */
    public function setState($state);

    /**
     * @return string|null
     */
    public function getZip();

    /**
     * @param string $zip
     * @return $this
     */
    public function setZip($zip);

    /**
     * @return string|null
     */
    public function getCountry();

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry($country);

    /**
     * @return string|null
     */
    public function getPhone();

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone);

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
