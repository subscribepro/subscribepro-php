<?php

namespace SubscribePro\Service\PaymentProfile;

use SubscribePro\Service\DataObject;
use SubscribePro\Service\Address\AddressInterface;
use SubscribePro\Exception\InvalidArgumentException;

class PaymentProfile extends DataObject implements PaymentProfileInterface
{
    /**
     * @var string
     */
    protected $idField = self::ID;

    /**
     * @var array
     */
    protected $creatingFields = [
        self::CUSTOMER_ID => false,
        self::MAGENTO_CUSTOMER_ID => false,
        self::CREDITCARD_NUMBER => true,
        self::CREDITCARD_VERIFICATION_VALUE => false,
        self::CREDITCARD_MONTH => true,
        self::CREDITCARD_YEAR => true,
        self::BILLING_ADDRESS => true
    ];

    /**
     * @var array
     */
    protected $updatingFields = [
        self::CREDITCARD_MONTH => false,
        self::CREDITCARD_YEAR => false,
        self::BILLING_ADDRESS => false
    ];

    /**
     * @var array
     */
    protected $savingTokenFields = [
        self::CUSTOMER_ID => false,
        self::MAGENTO_CUSTOMER_ID => false,
        self::CREDITCARD_MONTH => false,
        self::CREDITCARD_YEAR => false,
        self::BILLING_ADDRESS => false
    ];

    /**
     * @var array
     */
    protected $savingThirdPartyTokenFields = [
        self::CUSTOMER_ID => true,
        self::THIRD_PARTY_VAULT_TYPE => false,
        self::THIRD_PARTY_PAYMENT_TOKEN => true,
        self::CREDITCARD_TYPE => false,
        self::CREDITCARD_LAST_DIGITS => false,
        self::CREDITCARD_FIRST_DIGITS => false,
        self::CREDITCARD_MONTH => false,
        self::CREDITCARD_YEAR => false,
        self::BILLING_ADDRESS => true
    ];

    /**
     * @var array
     */
    protected $creditcardTypes = [
        self::CC_TYPE_VISA,
        self::CC_TYPE_MASTER,
        self::CC_TYPE_AMERICAN_EXPRESS,
        self::CC_TYPE_DISCOVER,
        self::CC_TYPE_JCB,
        self::CC_TYPE_DINERS_CLUB,
        self::CC_TYPE_DANKORT
    ];

    /**
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        $data[self::BILLING_ADDRESS] = $this->getBillingAddress()->toArray();
        return $data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function importData(array $data = [])
    {
        if (!isset($data[self::BILLING_ADDRESS]) || !$data[self::BILLING_ADDRESS] instanceof AddressInterface) {
            $billingAddressData = isset($data[self::BILLING_ADDRESS]) && is_array($data[self::BILLING_ADDRESS]) ? $data[self::BILLING_ADDRESS] : [];
            $data[self::BILLING_ADDRESS] = $this->getBillingAddress()->importData($billingAddressData);
        }
            
        return parent::importData($data);
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
        $isCustomerDataValid = $this->isNew() ? ($this->getCustomerId() || $this->getMagentoCustomerId()) : true;
        return $isCustomerDataValid
            && $this->checkRequiredFields($this->getFormFields())
            && $this->getBillingAddress()->isAsChildValid($this->isNew());
    }

    /**
     * @return array
     */
    public function getFormData()
    {
        $formData = array_intersect_key($this->data, $this->getFormFields());
        return $this->updateBillingFormData($formData);
    }

    /**
     * @return array
     */
    public function getTokenFormData()
    {
        $tokenFormData = array_intersect_key($this->data, $this->savingTokenFields);
        return $this->updateBillingFormData($tokenFormData);
    }

    /**
     * @return bool
     */
    public function isTokenDataValid()
    {
        return ($this->getCustomerId() || $this->getMagentoCustomerId())
            && $this->checkRequiredFields($this->savingTokenFields);
    }

    /**
     * @return array
     */
    public function getThirdPartyTokenFormData()
    {
        $tokenFormData = array_intersect_key($this->data, $this->savingThirdPartyTokenFields);
        return $this->updateBillingFormData($tokenFormData);
    }

    /**
     * @return bool
     */
    public function isThirdPartyDataValid()
    {
        return $this->checkRequiredFields($this->savingThirdPartyTokenFields);
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
     * @return string|null
     */
    public function getMagentoCustomerId()
    {
        return $this->getData(self::MAGENTO_CUSTOMER_ID);
    }

    /**
     * @param string $magentoCustomerId
     * @return $this
     */
    public function setMagentoCustomerId($magentoCustomerId)
    {
        return $this->setData(self::MAGENTO_CUSTOMER_ID, $magentoCustomerId);
    }

    /**
     * @return string|null
     */
    public function getCustomerEmail()
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * Credit card type: visa, master, american_express, discover, jcb, diners_club or dankort
     *
     * @return string|null
     */
    public function getCreditcardType()
    {
        return $this->getData(self::CREDITCARD_TYPE);
    }

    /**
     * Credit card type: visa, master, american_express, discover, jcb, diners_club or dankort
     *
     * @param string $creditcardType
     * @return $this
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setCreditcardType($creditcardType)
    {
        if (!in_array($creditcardType, $this->creditcardTypes)) {
            throw new InvalidArgumentException('Invalid credit card type. Allowed types: ' . implode(', ', $this->creditcardTypes));
        }

        return $this->setData(self::CREDITCARD_TYPE, $creditcardType);
    }

    /**
     * @return string
     */
    public function getCreditcardNumber()
    {
        return $this->getData(self::CREDITCARD_NUMBER);
    }

    /**
     * @param string $creditcardNumber
     * @return $this
     */
    public function setCreditcardNumber($creditcardNumber)
    {
        return $this->setData(self::CREDITCARD_NUMBER, $creditcardNumber);
    }

    /**
     * @return string
     */
    public function getCreditcardFirstDigits()
    {
        return $this->getData(self::CREDITCARD_FIRST_DIGITS);
    }

    /**
     * @param string $creditcardFirstDigits
     * @return $this
     */
    public function setCreditcardFirstDigits($creditcardFirstDigits)
    {
        return $this->setData(self::CREDITCARD_FIRST_DIGITS, $creditcardFirstDigits);
    }

    /**
     * @return string
     */
    public function getCreditcardLastDigits()
    {
        return $this->getData(self::CREDITCARD_LAST_DIGITS);
    }

    /**
     * @param string $creditcardLastDigits
     * @return $this
     */
    public function setCreditcardLastDigits($creditcardLastDigits)
    {
        return $this->setData(self::CREDITCARD_LAST_DIGITS, $creditcardLastDigits);
    }

    /**
     * @return string|null
     */
    public function getCreditcardVerificationValue()
    {
        return $this->getData(self::CREDITCARD_VERIFICATION_VALUE);
    }

    /**
     * @param string $creditcardVerificationValue
     * @return $this
     */
    public function setCreditcardVerificationValue($creditcardVerificationValue)
    {
        return $this->setData(self::CREDITCARD_VERIFICATION_VALUE, $creditcardVerificationValue);
    }

    /**
     * @return string
     */
    public function getCreditcardMonth()
    {
        return $this->getData(self::CREDITCARD_MONTH);
    }

    /**
     * @param string $creditcardMonth
     * @return $this
     */
    public function setCreditcardMonth($creditcardMonth)
    {
        return $this->setData(self::CREDITCARD_MONTH, $creditcardMonth);
    }

    /**
     * @return string
     */
    public function getCreditcardYear()
    {
        return $this->getData(self::CREDITCARD_YEAR);
    }

    /**
     * @param string $creditcardYear
     * @return $this
     */
    public function setCreditcardYear($creditcardYear)
    {
        return $this->setData(self::CREDITCARD_YEAR, $creditcardYear);
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
     * @return string
     */
    public function getGateway()
    {
        return $this->getData(self::GATEWAY);
    }

    /**
     * Payment method type: credit_card or third_party_token
     *
     * @return string|null
     */
    public function getPaymentMethodType()
    {
        return $this->getData(self::PAYMENT_METHOD_TYPE);
    }

    /**
     * @return string|null
     */
    public function getPaymentToken()
    {
        return $this->getData(self::PAYMENT_TOKEN);
    }

    /**
     * @return string|null
     */
    public function getPaymentVault()
    {
        return $this->getData(self::PAYMENT_VAULT);
    }

    /**
     * @return string|null
     */
    public function getThirdPartyVaultType()
    {
        return $this->getData(self::THIRD_PARTY_VAULT_TYPE);
    }

    /**
     * @param string $thirdPartyVaultType
     * @return $this
     */
    public function setThirdPartyVaultType($thirdPartyVaultType)
    {
        return $this->setData(self::THIRD_PARTY_VAULT_TYPE, $thirdPartyVaultType);
    }

    /**
     * @return string|null
     */
    public function getThirdPartyPaymentToken()
    {
        return $this->getData(self::THIRD_PARTY_PAYMENT_TOKEN);
    }

    /**
     * @param string $thirdPartyPaymentToken
     * @return $this
     */
    public function setThirdPartyPaymentToken($thirdPartyPaymentToken)
    {
        return $this->setData(self::THIRD_PARTY_PAYMENT_TOKEN, $thirdPartyPaymentToken);
    }

    /**
     * Current status of the payment profile: retained or redacted
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @param null $format
     * @return string
     */
    public function getCreated($format = null)
    {
        return $this->getDatetimeData(self::CREATED, $format);
    }

    /**
     * @param null $format
     * @return string
     */
    public function getUpdated($format = null)
    {
        return $this->getDatetimeData(self::UPDATED, $format);
    }
}
