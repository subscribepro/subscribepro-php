<?php

namespace SubscribePro\Service\Token;

use SubscribePro\Service\DataObject;
use SubscribePro\Service\Address\AddressInterface;

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
        self::CREDITCARD_NUMBER => true,
        self::CREDITCARD_VERIFICATION_VALUE => false,
        self::CREDITCARD_MONTH => true,
        self::CREDITCARD_YEAR => true,
        self::BILLING_ADDRESS => true
    ];

    public function toArray()
    {
        $data = parent::toArray();
        $data[self::BILLING_ADDRESS] = $this->getBillingAddress()->toArray();
        return $data;
    }

    public function importData(array $data = [])
    {
        if (!isset($data[self::BILLING_ADDRESS]) || !$data[self::BILLING_ADDRESS] instanceof AddressInterface) {
            $billingAddressData = (isset($data[self::BILLING_ADDRESS]) && is_array($data[self::BILLING_ADDRESS])) ? $data[self::BILLING_ADDRESS] : [];
            $data[self::BILLING_ADDRESS] = $this->getBillingAddress()->importData($billingAddressData);
        }

        return parent::importData($data);
    }

    /**
     * @return array
     */
    public function getFormData()
    {
        $formData =array_intersect_key($this->data, $this->creatingFields);
        return $this->updateBillingFormData($formData);
    }

    /**
     * @return array
     */
    protected function getFormFields()
    {
        return $this->isNew() ? $this->creatingFields : $this->updatingFields;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->isNew()
            && $this->checkRequiredFields($this->getFormFields())
            && $this->getBillingAddress()->isAsChildValid($this->isNew());
    }

    /**
     * @param array $tokenFormData
     * @return array
     */
    protected function updateBillingFormData(array $tokenFormData)
    {
        $tokenFormData[self::BILLING_ADDRESS] = $this->getBillingAddress()->getAsChildFormData($this->isNew());
        if (empty($tokenFormData[self::BILLING_ADDRESS])) {
            unset($tokenFormData[self::BILLING_ADDRESS]);
        }
        return $tokenFormData;
    }

    //@codeCoverageIgnoreStart

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
    public function getCreditCardNumber()
    {
        return $this->getData(self::CREDITCARD_NUMBER);
    }

    /**
     * @param string $number
     * @return $this
     */
    public function setCreditCardNumber($number)
    {
        return $this->setData(self::CREDITCARD_NUMBER, $number);
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
    public function getCreditCardVerificationValue()
    {
        return $this->getData(self::CREDITCARD_VERIFICATION_VALUE);
    }

    /**
     * @param string $verificationValue
     * @return $this
     */
    public function setCreditCardVerificationValue($verificationValue)
    {
        return $this->setData(self::CREDITCARD_VERIFICATION_VALUE, $verificationValue);
    }

    /**
     * @return string|null
     */
    public function getCreditCardMonth()
    {
        return $this->getData(self::CREDITCARD_MONTH);
    }

    /**
     * @param string $month
     * @return $this
     */
    public function setCreditCardMonth($month)
    {
        return $this->setData(self::CREDITCARD_MONTH, $month);
    }

    /**
     * @return string|null
     */
    public function getCreditCardYear()
    {
        return $this->getData(self::CREDITCARD_YEAR);
    }

    /**
     * @param string $year
     * @return $this
     */
    public function setCreditCardYear($year)
    {
        return $this->setData(self::CREDITCARD_YEAR, $year);
    }

    /**
     * @return \SubscribePro\Service\Address\AddressInterface
     */
    public function getBillingAddress()
    {
        return $this->getData(self::BILLING_ADDRESS);
    }

    /**
     * @param \SubscribePro\Service\Address\AddressInterface $billingAddress
     * @return $this
     */
    public function setBillingAddress(AddressInterface $billingAddress)
    {
        return $this->setData(self::BILLING_ADDRESS, $billingAddress);
    }

     /**
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->getData(self::FIRST_NAME);
    }

     /**
     * @return string|null
     */
    public function getLastName()
    {
        return $this->getData(self::LAST_NAME);
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
    public function getAddress()
    {
        return $this->getData(self::ADDRESS);
    }

     /**
     * @return string|null
     */
    public function getCity()
    {
        return $this->getData(self::CITY);
    }

     /**
     * @return string|null
     */
    public function getState()
    {
        return $this->getData(self::STATE);
    }

     /**
     * @return string|null
     */
    public function getZip()
    {
        return $this->getData(self::ZIP);
    }

     /**
     * @return string|null
     */
    public function getCountry()
    {
        return $this->getData(self::COUNTRY);
    }

     /**
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->getData(self::PHONE_NUMBER);
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

    //@codeCoverageIgnoreEnd
}
