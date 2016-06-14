<?php

namespace SubscribePro\Service\Token;

use SubscribePro\Service\DataObject;

class Token extends DataObject implements TokenInterface
{
    /**
     * @var string
     */
    protected $idField = self::TOKEN;

    /**
     * @var array
     */
    protected $creatingFields = [
        self::NUMBER => true,
        self::VERIFICATION_VALUE => false,
        self::MONTH => true,
        self::YEAR => true,
        self::FIRST_NAME => true,
        self::LAST_NAME => true,
        self::COMPANY => false,
        self::ADDRESS1 => true,
        self::ADDRESS2 => false,
        self::CITY => true,
        self::COUNTRY => true,
        self::ZIP => true,
        self::STATE => true,
        self::PHONE => false,
    ];

    /**
     * @return array
     */
    public function getFormData()
    {
        return array_intersect_key($this->data, $this->creatingFields);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->checkRequiredFields($this->creatingFields);
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->getData(self::TOKEN);
    }

    /**
     * @return string|null
     */
    public function getPaymentMethodType()
    {
        return $this->getData(self::PAYMENT_METHOD_TYPE);
    }

    /**
     * @return string|null
     */
    public function getCardType()
    {
        return $this->getData(self::CARD_TYPE);
    }

    /**
     * @return string|null
     */
    public function getNumber()
    {
        return $this->getData(self::NUMBER);
    }

    /**
     * @param string $number
     * @return $this
     */
    public function setNumber($number)
    {
        return $this->setData(self::NUMBER, $number);
    }

    /**
     * @return string|null
     */
    public function getLastFourDigits()
    {
        return $this->getData(self::LAST_FOUR_DIGITS);
    }

    /**
     * @return string|null
     */
    public function getFirstSixDigits()
    {
        return $this->getData(self::FIRST_SIX_DIGITS);
    }

    /**
     * @return string|null
     */
    public function getVerificationValue()
    {
        return $this->getData(self::VERIFICATION_VALUE);
    }

    /**
     * @param string $verificationValue
     * @return $this
     */
    public function setVerificationValue($verificationValue)
    {
        return $this->setData(self::VERIFICATION_VALUE, $verificationValue);
    }

    /**
     * @return string|null
     */
    public function getMonth()
    {
        return $this->getData(self::MONTH);
    }

    /**
     * @param string $month
     * @return $this
     */
    public function setMonth($month)
    {
        return $this->setData(self::MONTH, $month);
    }

    /**
     * @return string|null
     */
    public function getYear()
    {
        return $this->getData(self::YEAR);
    }

    /**
     * @param string $year
     * @return $this
     */
    public function setYear($year)
    {
        return $this->setData(self::YEAR, $year);
    }

    /**
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->getData(self::FIRST_NAME);
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        return $this->setData(self::FIRST_NAME, $firstName);
    }

    /**
     * @return string|null
     */
    public function getLastName()
    {
        return $this->getData(self::LAST_NAME);
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        return $this->setData(self::LAST_NAME, $lastName);
    }

    /**
     * @return string|null
     */
    public function getFullName()
    {
        return $this->getData(self::FULL_NAME);
    }

    /**
     * @return string|null
     */
    public function getCompany()
    {
        return $this->getData(self::COMPANY);
    }

    /**
     * @param string|null $company
     * @return $this
     */
    public function setCompany($company)
    {
        return $this->setData(self::COMPANY, $company);
    }

    /**
     * @return string|null
     */
    public function getAddress1()
    {
        return $this->getData(self::ADDRESS1);
    }

    /**
     * @param string|null $address1
     * @return $this
     */
    public function setAddress1($address1)
    {
        return $this->setData(self::ADDRESS1, $address1);
    }

    /**
     * @return string|null
     */
    public function getAddress2()
    {
        return $this->getData(self::ADDRESS2);
    }

    /**
     * @param string|null $address2
     * @return $this
     */
    public function setAddress2($address2)
    {
        return $this->setData(self::ADDRESS2, $address2);
    }

    /**
     * @return string|null
     */
    public function getCity()
    {
        return $this->getData(self::CITY);
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * @return string|null
     */
    public function getState()
    {
        return $this->getData(self::STATE);
    }

    /**
     * @param string $state
     * @return $this
     */
    public function setState($state)
    {
        return $this->setData(self::STATE, $state);
    }

    /**
     * @return string|null
     */
    public function getZip()
    {
        return $this->getData(self::ZIP);
    }

    /**
     * @param string $zip
     * @return $this
     */
    public function setZip($zip)
    {
        return $this->setData(self::ZIP, $zip);
    }

    /**
     * @return string|null
     */
    public function getCountry()
    {
        return $this->getData(self::COUNTRY);
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * @return string|null
     */
    public function getPhone()
    {
        return $this->getData(self::PHONE);
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        return $this->setData(self::PHONE, $phone);
    }

    /**
     * @return string|null
     */
    public function getEligibleForCardUpdater()
    {
        return $this->getData(self::ELIGIBLE_FOR_CARD_UPDATER);
    }

    /**
     * @return string|null
     */
    public function getStorageState()
    {
        return $this->getData(self::STORAGE_STATE);
    }

    /**
     * @return string|null
     */
    public function getTest()
    {
        return $this->getData(self::TEST);
    }

    /**
     * @return string|null
     */
    public function getFingerprint()
    {
        return $this->getData(self::FINGERPRINT);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getCreatedAt($format = null)
    {
        return $this->getDatetimeData(self::CREATED_AT, $format);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getUpdatedAt($format = null)
    {
        return $this->getDatetimeData(self::UPDATED_AT, $format);
    }
}
