<?php

namespace SubscribePro\Service\Transaction;

use SubscribePro\Service\Address\AddressInterface;
use SubscribePro\Service\DataInterface;

interface TransactionInterface extends DataInterface
{
    /**
     * Data fields
     */
    public const ID = 'id';
    public const GATEWAY_SPECIFIC_RESPONSE = 'gateway_specific_response';
    public const GATEWAY_SPECIFIC_FIELDS = 'gateway_specific_fields';
    public const GATEWAY_TYPE = 'gateway_type';
    public const USE_THREE_DS = 'use_three_ds';
    public const BROWSER_INFO = 'browser_info';
    public const THREE_DS_TYPE = 'three_ds_type';
    public const THREE_DS_REDIRECT_URL = 'three_ds_redirect_url';
    public const AUTHORIZE_NET_RESPONSE_REASON_CODE = 'authorize_net_response_reason_code';
    public const SUBSCRIBE_PRO_ERROR_DESCRIPTION = 'subscribe_pro_error_description';
    public const CREDITCARD_TYPE = 'creditcard_type';
    public const CREDITCARD_LAST_DIGITS = 'creditcard_last_digits';
    public const CREDITCARD_FIRST_DIGITS = 'creditcard_first_digits';
    public const CREDITCARD_MONTH = 'creditcard_month';
    public const CREDITCARD_YEAR = 'creditcard_year';
    public const BILLING_ADDRESS = 'billing_address';
    public const UNIQUE_ID = 'unique_id';
    public const SUBSCRIBE_PRO_ORDER_TOKEN = 'subscribe_pro_order_token';
    public const REF_PAYMENT_PROFILE_ID = 'ref_payment_profile_id';
    public const REF_TRANSACTION_ID = 'ref_transaction_id';
    public const REF_GATEWAY_ID = 'ref_gateway_id';
    public const REF_TOKEN = 'ref_token';
    public const PAYMENT_TOKEN = 'payment_token';
    public const TOKEN = 'token';
    public const TYPE = 'type';
    public const AMOUNT = 'amount';
    public const CURRENCY_CODE = 'currency_code';
    public const STATE = 'state';
    public const GATEWAY_TRANSACTION_ID = 'gateway_transaction_id';
    public const EMAIL = 'email';
    public const ORDER_ID = 'order_id';
    public const IP = 'ip';
    public const RESPONSE_MESSAGE = 'response_message';
    public const ERROR_CODE = 'error_code';
    public const ERROR_DETAIL = 'error_detail';
    public const CVV_CODE = 'cvv_code';
    public const CVV_MESSAGE = 'cvv_message';
    public const AVS_CODE = 'avs_code';
    public const AVS_MESSAGE = 'avs_message';
    public const SUBSCRIBE_PRO_ERROR_CLASS = 'subscribe_pro_error_class';
    public const SUBSCRIBE_PRO_ERROR_TYPE = 'subscribe_pro_error_type';
    public const CREATED = 'created';

    /**
     * Transaction types
     */
    public const TYPE_AUTHORIZATION = 'Authorization';
    public const TYPE_PURCHASE = 'Purchase';
    public const TYPE_CAPTURE = 'Capture';
    public const TYPE_VOID = 'Void';
    public const TYPE_CREDIT = 'Credit';
    public const TYPE_VERIFICATION = 'Verification';

    /**
     * Transaction states
     */
    public const STATE_SUCCEEDED = 'succeeded';
    public const STATE_PENDING = 'pending';
    public const STATE_FAILED = 'failed';
    public const STATE_GATEWAY_PROCESSING_FAILED = 'gateway_processing_failed';
    public const STATE_GATEWAY_PROCESSING_RESULT_UNKNOWN = 'gateway_processing_result_unknown';

    /**
     * @return mixed[]
     */
    public function getFormData();

    /**
     * @return mixed[]
     */
    public function getVerifyFormData();

    /**
     * @return bool
     */
    public function isVerifyDataValid();

    /**
     * @return mixed[]
     */
    public function getServiceFormData();

    /**
     * @return bool
     */
    public function isServiceDataValid();

    /**
     * @param \SubscribePro\Service\Address\AddressInterface $address
     *
     * @return mixed[]
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
     *
     * @return $this
     *
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setAmount($amount);

    /**
     * @return string|null
     */
    public function getCurrencyCode();

    /**
     * @param string $currencyCode
     *
     * @return $this
     *
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setCurrencyCode($currencyCode);

    /**
     * @return int|null
     */
    public function getOrderId();

    /**
     * @param int $orderId
     *
     * @return $this
     */
    public function setOrderId($orderId);

    /**
     * @return string|null
     */
    public function getIp();

    /**
     * @param string $ip
     *
     * @return $this
     */
    public function setIp($ip);

    /**
     * @return string|null
     */
    public function getEmail();

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email);

    /**
     * @return mixed[]
     */
    public function getGatewaySpecificResponse();

    /**
     * @return string|null
     */
    public function getGatewayType();

    /**
     * @return bool
     */
    public function getUseThreeDs();

    /**
     * @return @return string|null
     */
    public function getBrowserInfo();

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
     *
     * @return $this
     */
    public function setCreditcardMonth($creditcardMonth);

    /**
     * @return string|null
     */
    public function getCreditcardYear();

    /**
     * @param string $creditcardYear
     *
     * @return $this
     */
    public function setCreditcardYear($creditcardYear);

    /**
     * @return int|null
     */
    public function getUniqueId();

    /**
     * @param int $uniqueId
     *
     * @return $this
     */
    public function setUniqueId($uniqueId);

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
    public function getPaymentToken();

    /**
     * @param string $paymentToken
     *
     * @return $this
     */
    public function setPaymentToken($paymentToken);

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
     *
     * @return string|null
     */
    public function getCreated($format = null);
}
