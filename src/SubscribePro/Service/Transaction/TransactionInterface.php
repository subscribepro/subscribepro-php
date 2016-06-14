<?php

namespace SubscribePro\Service\Transaction;

use SubscribePro\Service\DataInterface;
use SubscribePro\Service\Address\AddressInterface;

interface TransactionInterface extends DataInterface
{
    /**
     * Data fields
     */
    const ID = 'id';
    const GATEWAY_SPECIFIC_RESPONSE = 'gateway_specific_response';
    const GATEWAY_TYPE = 'gateway_type';
    const AUTHORIZE_NET_RESPONSE_REASON_CODE = 'authorize_net_response_reason_code';
    const SUBSCRIBE_PRO_ERROR_DESCRIPTION = 'subscribe_pro_error_description';
    const CREDITCARD_TYPE = 'creditcard_type';
    const CREDITCARD_LAST_DIGITS = 'creditcard_last_digits';
    const CREDITCARD_FIRST_DIGITS = 'creditcard_first_digits';
    const CREDITCARD_MONTH = 'creditcard_month';
    const CREDITCARD_YEAR = 'creditcard_year';
    const BILLING_ADDRESS = 'billing_address';
    const REF_PAYMENT_PROFILE_ID = 'ref_payment_profile_id';
    const REF_TRANSACTION_ID = 'ref_transaction_id';
    const REF_GATEWAY_ID = 'ref_gateway_id';
    const REF_TOKEN = 'ref_token';
    const TOKEN = 'token';
    const TYPE = 'type';
    const AMOUNT = 'amount';
    const CURRENCY_CODE = 'currency_code';
    const STATE = 'state';
    const GATEWAY_TRANSACTION_ID = 'gateway_transaction_id';
    const EMAIL = 'email';
    const ORDER_ID = 'order_id';
    const IP = 'ip';
    const RESPONSE_MESSAGE = 'response_message';
    const ERROR_CODE = 'error_code';
    const ERROR_DETAIL = 'error_detail';
    const CVV_CODE = 'cvv_code';
    const CVV_MESSAGE = 'cvv_message';
    const AVS_CODE = 'avs_code';
    const AVS_MESSAGE = 'avs_message';
    const SUBSCRIBE_PRO_ERROR_CLASS = 'subscribe_pro_error_class';
    const SUBSCRIBE_PRO_ERROR_TYPE = 'subscribe_pro_error_type';
    const CREATED = 'created';

    /**
     * Transaction types
     */
    const TYPE_AUTHORIZATION = 'Authorization';
    const TYPE_PURCHASE = 'Purchase';
    const TYPE_CAPTURE = 'Capture';
    const TYPE_VOID = 'Void';
    const TYPE_CREDIT = 'Credit';
    const TYPE_VERIFICATION = 'Verification';

    /**
     * Transaction states
     */
    const STATE_SUCCEEDED = 'succeeded';
    const STATE_FAILED = 'failed';
    const STATE_GATEWAY_PROCESSING_FAILED = 'gateway_processing_failed';
    const STATE_GATEWAY_PROCESSING_RESULT_UNKNOWN = 'gateway_processing_result_unknown';


    /**
     * @return array
     */
    public function getFormData();

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return array
     */
    public function getVerifyFormData();

    /**
     * @return bool
     */
    public function isVerifyDataValid();

    /**
     * @return array
     */
    public function getServiceFormData();

    /**
     * @return bool
     */
    public function isServiceDataValid();

    /**
     * @param \SubscribePro\Service\Address\AddressInterface $address
     * @return array
     */
    public function getTokenFormData(AddressInterface $address = null);

    /**
     * @return bool
     */
    public function isTokenDataValid();

    /**
     * @return int|null
     */
    public function getAmount();

    /**
     * @param int $amount
     * @return $this
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setAmount($amount);

    /**
     * @return string|null
     */
    public function getCurrencyCode();

    /**
     * @param string $currencyCode
     * @return $this
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setCurrencyCode($currencyCode);

    /**
     * @return int|null
     */
    public function getOrderId();

    /**
     * @param int $orderId
     * @return $this
     */
    public function setOrderId($orderId);

    /**
     * @return string|null
     */
    public function getIp();

    /**
     * @param string $ip
     * @return $this
     */
    public function setIp($ip);

    /**
     * @return string|null
     */
    public function getEmail();

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * @return array
     */
    public function getGatewaySpecificResponse();

    /**
     * @return string|null
     */
    public function getGatewayType();

    /**
     * @return string|null
     */
    public function getAuthorizeNetResponseReasonCode();

    /**
     * @return string|null
     */
    public function getSubscribeProErrorDescription();

    /**
     * @return string|null
     */
    public function getCreditcardType();

    /**
     * @return string|null
     */
    public function getCreditcardLastDigits();

    /**
     * @return string|null
     */
    public function getCreditcardFirstDigits();

    /**
     * @return string|null
     */
    public function getCreditcardMonth();

    /**
     * @param string $creditcardMonth
     * @return $this
     */
    public function setCreditcardMonth($creditcardMonth);

    /**
     * @return string|null
     */
    public function getCreditcardYear();

    /**
     * @param string $creditcardYear
     * @return $this
     */
    public function setCreditcardYear($creditcardYear);

    /**
     * @return int|null
     */
    public function getRefPaymentProfileId();

    /**
     * @return int|null
     */
    public function getRefTransactionId();

    /**
     * @return int|null
     */
    public function getRefGatewayId();

    /**
     * @return string|null
     */
    public function getRefToken();

    /**
     * @return string|null
     */
    public function getToken();

    /**
     * Transaction type: Purchase, Authorization, Capture, Void, Credit or Verification
     *
     * @return string|null
     */
    public function getType();

    /**
     * Transaction state: 'succeeded', 'gateway_processing_failed', 'failed', 'gateway_processing_result_unknown' or other
     *
     * @return string|null
     */
    public function getState();

    /**
     * @return string|null
     */
    public function getGatewayTransactionId();

    /**
     * @return string|null
     */
    public function getResponseMessage();

    /**
     * @return string|null
     */
    public function getErrorCode();

    /**
     * @return string|null
     */
    public function getErrorDetail();

    /**
     * @return string|null
     */
    public function getCvvCode();

    /**
     * @return string|null
     */
    public function getCvvMessage();

    /**
     * @return string|null
     */
    public function getAvsCode();

    /**
     * @return string|null
     */
    public function getAvsMessage();

    /**
     * @return string|null
     */
    public function getSubscribeProErrorClass();

    /**
     * @return string|null
     */
    public function getSubscribeProErrorType();

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getCreated($format = null);
}
