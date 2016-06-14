<?php

namespace SubscribePro\Service\Transaction;

use SubscribePro\Service\DataObject;
use SubscribePro\Service\Address\AddressInterface;
use SubscribePro\Exception\InvalidArgumentException;

class Transaction extends DataObject implements TransactionInterface
{
    /**
     * @var string
     */
    protected $idField = self::ID;

    /**
     * @var array
     */
    protected $creatingFields = [
        self::AMOUNT => true,
        self::CURRENCY_CODE => true,
        self::ORDER_ID => false,
        self::IP => false,
        self::EMAIL => false
    ];

    /**
     * @var array
     */
    protected $createTokenFields = [
        self::AMOUNT => true,
        self::CURRENCY_CODE => true,
        self::ORDER_ID => false,
        self::IP => false,
        self::EMAIL => false,
        self::CREDITCARD_MONTH => false,
        self::CREDITCARD_YEAR => false
    ];

    /**
     * @var array
     */
    protected $verifyFields = [
        self::CURRENCY_CODE => true,
        self::ORDER_ID => false,
        self::IP => false,
        self::EMAIL => false
    ];

    /**
     * @var array
     */
    protected $serviceFields = [
        self::AMOUNT => true,
        self::CURRENCY_CODE => true
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
     * @return array
     */
    public function getVerifyFormData()
    {
        return array_intersect_key($this->data, $this->verifyFields);
    }

    /**
     * @return bool
     */
    public function isVerifyDataValid()
    {
        return $this->checkRequiredFields($this->verifyFields);
    }

    /**
     * @param \SubscribePro\Service\Address\AddressInterface $address
     * @return array
     */
    public function getTokenFormData(AddressInterface $address = null)
    {
        $tokenFormData = array_intersect_key($this->data, $this->createTokenFields);
        if ($address) {
            $tokenFormData[self::BILLING_ADDRESS] = $address->getAsChildFormData(true);
        }
        return $tokenFormData;
    }

    /**
     * @return bool
     */
    public function isTokenDataValid()
    {
        return $this->checkRequiredFields($this->createTokenFields);
    }

    /**
     * @return array
     */
    public function getServiceFormData()
    {
        return array_intersect_key($this->data, $this->serviceFields);
    }

    /**
     * @return bool
     */
    public function isServiceDataValid()
    {
        return $this->checkRequiredFields($this->serviceFields);
    }

    /**
     * @return int|null
     */
    public function getAmount()
    {
        return $this->getData(self::AMOUNT);
    }

    /**
     * @param int $amount
     * @return $this
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setAmount($amount)
    {
        if (!is_numeric($amount) || $amount <= 0) {
            throw new InvalidArgumentException('The amount should be always given as an integer number of cents. ');
        }
        return $this->setData(self::AMOUNT, $amount);
    }

    /**
     * @return string|null
     */
    public function getCurrencyCode()
    {
        return $this->getData(self::CURRENCY_CODE);
    }

    /**
     * @param string $currencyCode
     * @return $this
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setCurrencyCode($currencyCode)
    {
        if (strlen($currencyCode) != 3) {
            throw new InvalidArgumentException('Currency code should consist of 3 letters.');
        }
        return $this->setData(self::CURRENCY_CODE, $currencyCode);
    }

    /**
     * @return int|null
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @param int $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * @return string|null
     */
    public function getIp()
    {
        return $this->getData(self::IP);
    }

    /**
     * @param string $ip
     * @return $this
     */
    public function setIp($ip)
    {
        return $this->setData(self::IP, $ip);
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @return array
     */
    public function getGatewaySpecificResponse()
    {
        return $this->getData(self::GATEWAY_SPECIFIC_RESPONSE, []);
    }

    /**
     * @return string|null
     */
    public function getGatewayType()
    {
        return $this->getData(self::GATEWAY_TYPE);
    }

    /**
     * @return string|null
     */
    public function getAuthorizeNetResponseReasonCode()
    {
        return $this->getData(self::AUTHORIZE_NET_RESPONSE_REASON_CODE);
    }

    /**
     * @return string|null
     */
    public function getSubscribeProErrorDescription()
    {
        return $this->getData(self::SUBSCRIBE_PRO_ERROR_DESCRIPTION);
    }

    /**
     * @return string|null
     */
    public function getCreditcardType()
    {
        return $this->getData(self::CREDITCARD_TYPE);
    }

    /**
     * @return string|null
     */
    public function getCreditcardLastDigits()
    {
        return $this->getData(self::CREDITCARD_LAST_DIGITS);
    }

    /**
     * @return string|null
     */
    public function getCreditcardFirstDigits()
    {
        return $this->getData(self::CREDITCARD_FIRST_DIGITS);
    }

    /**
     * @return string|null
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
     * @return string|null
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
     * @return int|null
     */
    public function getRefPaymentProfileId()
    {
        return $this->getData(self::REF_PAYMENT_PROFILE_ID);
    }

    /**
     * @return int|null
     */
    public function getRefTransactionId()
    {
        return $this->getData(self::REF_TRANSACTION_ID);
    }

    /**
     * @return int|null
     */
    public function getRefGatewayId()
    {
        return $this->getData(self::REF_GATEWAY_ID);
    }

    /**
     * @return string|null
     */
    public function getRefToken()
    {
        return $this->getData(self::REF_TOKEN);
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->getData(self::TOKEN);
    }

    /**
     * Transaction type: Purchase, Authorization, Capture, Void, Credit or Verification
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Transaction state: 'succeeded', 'gateway_processing_failed', 'failed', 'gateway_processing_result_unknown' or other
     *
     * @return string|null
     */
    public function getState()
    {
        return $this->getData(self::STATE);
    }

    /**
     * @return string|null
     */
    public function getGatewayTransactionId()
    {
        return $this->getData(self::GATEWAY_TRANSACTION_ID);
    }

    /**
     * @return string|null
     */
    public function getResponseMessage()
    {
        return $this->getData(self::RESPONSE_MESSAGE);
    }

    /**
     * @return string|null
     */
    public function getErrorCode()
    {
        return $this->getData(self::ERROR_CODE);
    }

    /**
     * @return string|null
     */
    public function getErrorDetail()
    {
        return $this->getData(self::ERROR_DETAIL);
    }

    /**
     * @return string|null
     */
    public function getCvvCode()
    {
        return $this->getData(self::CVV_CODE);
    }

    /**
     * @return string|null
     */
    public function getCvvMessage()
    {
        return $this->getData(self::CVV_MESSAGE);
    }

    /**
     * @return string|null
     */
    public function getAvsCode()
    {
        return $this->getData(self::AVS_CODE);
    }

    /**
     * @return string|null
     */
    public function getAvsMessage()
    {
        return $this->getData(self::AVS_MESSAGE);
    }

    /**
     * @return string|null
     */
    public function getSubscribeProErrorClass()
    {
        return $this->getData(self::SUBSCRIBE_PRO_ERROR_CLASS);
    }

    /**
     * @return string|null
     */
    public function getSubscribeProErrorType()
    {
        return $this->getData(self::SUBSCRIBE_PRO_ERROR_TYPE);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getCreated($format = null)
    {
        return $this->getDatetimeData(self::CREATED, $format);
    }
}
