<?php

namespace SubscribePro\Tests\Service\Transaction;

use SubscribePro\Service\Address\Address;
use SubscribePro\Service\Address\AddressInterface;
use SubscribePro\Service\Transaction\Transaction;
use SubscribePro\Service\Transaction\TransactionInterface;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Transaction\TransactionInterface
     */
    protected $transaction;

    protected function setUp()
    {
        $this->transaction = new Transaction();
    }

    /**
     * @param array $data
     * @param bool $isValid
     * @dataProvider isValidDataProvider
     */
    public function testIsValid($data, $isValid)
    {
        $this->transaction->importData($data);
        $this->assertEquals($isValid, $this->transaction->isValid());
    }

    /**
     * @return array
     */
    public function isValidDataProvider()
    {
        return $this->getIsValidData();
    }

    /**
     * @param array $data
     * @param bool $isValid
     * @dataProvider isVerifyDataValidDataProvider
     */
    public function testIsVerifyDataValid($data, $isValid)
    {
        $this->transaction->importData($data);
        $this->assertEquals($isValid, $this->transaction->isVerifyDataValid());
    }

    /**
     * @return array
     */
    public function isVerifyDataValidDataProvider()
    {
        return [
            'Not valid: empty data' => [
                'data' => [],
                'isValid' => false
            ],
            'Valid' => [
                'data' => [
                    TransactionInterface::CURRENCY_CODE => 11,
                ],
                'isValid' => true
            ],
        ];
    }

    /**
     * @param array $data
     * @param bool $isValid
     * @dataProvider isServiceDataValidDataProvider
     */
    public function testIsServiceDataValid($data, $isValid)
    {
        $this->transaction->importData($data);
        $this->assertEquals($isValid, $this->transaction->isServiceDataValid());
    }

    /**
     * @return array
     */
    public function isServiceDataValidDataProvider()
    {
        return $this->getIsValidData();
    }

    /**
     * @param array $data
     * @param bool $isValid
     * @dataProvider isTokenDataValidDataProvider
     */
    public function testIsTokenDataValid($data, $isValid)
    {
        $this->transaction->importData($data);
        $this->assertEquals($isValid, $this->transaction->isTokenDataValid());
    }

    /**
     * @return array
     */
    public function isTokenDataValidDataProvider()
    {
        return $this->getIsValidData();
    }

    private function getIsValidData()
    {
        return [
            'Not valid: empty data' => [
                'data' => [],
                'isValid' => false
            ],
            'Not valid: without currency code' => [
                'data' => [
                    TransactionInterface::AMOUNT => 11,
                ],
                'isValid' => false
            ],
            'Not valid: without amount' => [
                'data' => [
                    TransactionInterface::CURRENCY_CODE => 'UAH',
                ],
                'isValid' => false
            ],
            'Valid' => [
                'data' => [
                    TransactionInterface::CURRENCY_CODE => 'UAH',
                    TransactionInterface::AMOUNT => 123,
                ],
                'isValid' => true
            ],
        ];   
    }

    public function testGetFormData()
    {
        $data = [
            TransactionInterface::TOKEN => 'token',
            TransactionInterface::GATEWAY_SPECIFIC_RESPONSE => 'response',
            TransactionInterface::GATEWAY_TYPE => 'type',
            TransactionInterface::AUTHORIZE_NET_RESPONSE_REASON_CODE => 'code',
            TransactionInterface::SUBSCRIBE_PRO_ERROR_DESCRIPTION => 'description',
            TransactionInterface::CREDITCARD_TYPE => 'type',
            TransactionInterface::CREDITCARD_LAST_DIGITS => '1111',
            TransactionInterface::CREDITCARD_FIRST_DIGITS => '411111',
            TransactionInterface::CREDITCARD_MONTH => '04',
            TransactionInterface::CREDITCARD_YEAR => '2019',
            TransactionInterface::BILLING_ADDRESS => '123',
            TransactionInterface::UNIQUE_ID => '123456789',
            TransactionInterface::REF_PAYMENT_PROFILE_ID => '414',
            TransactionInterface::REF_TRANSACTION_ID => '2323',
            TransactionInterface::REF_GATEWAY_ID => '525',
            TransactionInterface::REF_TOKEN => 'token',
            TransactionInterface::TYPE => 'type',
            TransactionInterface::AMOUNT => 'amount',
            TransactionInterface::CURRENCY_CODE => 'currency code',
            TransactionInterface::STATE => 'state',
            TransactionInterface::GATEWAY_TRANSACTION_ID => '124',
            TransactionInterface::EMAIL => 'email@example.com',
            TransactionInterface::ORDER_ID => '123',
            TransactionInterface::IP => '0.0.0.0',
            TransactionInterface::RESPONSE_MESSAGE => 'message',
            TransactionInterface::ERROR_CODE => 'error_code',
            TransactionInterface::ERROR_DETAIL => 'detail',
            TransactionInterface::CVV_CODE => 'cvv_code',
            TransactionInterface::CVV_MESSAGE => 'cvv_message',
            TransactionInterface::AVS_CODE => 'avs_code',
            TransactionInterface::AVS_MESSAGE => 'avs_message',
            TransactionInterface::SUBSCRIBE_PRO_ERROR_CLASS => 'class',
            TransactionInterface::SUBSCRIBE_PRO_ERROR_TYPE => 'type',
            TransactionInterface::CREATED => '2016-12-12',
        ];
        $expectedData = [
            TransactionInterface::AMOUNT => 'amount',
            TransactionInterface::CURRENCY_CODE => 'currency code',
            TransactionInterface::EMAIL => 'email@example.com',
            TransactionInterface::ORDER_ID => '123',
            TransactionInterface::IP => '0.0.0.0',
            TransactionInterface::UNIQUE_ID => '123456789'
        ];

        $this->transaction->importData($data);
        $this->assertEquals($expectedData, $this->transaction->getFormData());
    }
    
    public function testGetVerifyFormData()
    {
        $data = [
            TransactionInterface::TOKEN => 'token',
            TransactionInterface::GATEWAY_SPECIFIC_RESPONSE => 'response',
            TransactionInterface::GATEWAY_TYPE => 'type',
            TransactionInterface::AUTHORIZE_NET_RESPONSE_REASON_CODE => 'code',
            TransactionInterface::SUBSCRIBE_PRO_ERROR_DESCRIPTION => 'description',
            TransactionInterface::CREDITCARD_TYPE => 'type',
            TransactionInterface::CREDITCARD_LAST_DIGITS => '1111',
            TransactionInterface::CREDITCARD_FIRST_DIGITS => '411111',
            TransactionInterface::CREDITCARD_MONTH => '04',
            TransactionInterface::CREDITCARD_YEAR => '2019',
            TransactionInterface::BILLING_ADDRESS => '123',
            TransactionInterface::UNIQUE_ID => '987654321',
            TransactionInterface::REF_PAYMENT_PROFILE_ID => '414',
            TransactionInterface::REF_TRANSACTION_ID => '2323',
            TransactionInterface::REF_GATEWAY_ID => '525',
            TransactionInterface::REF_TOKEN => 'token',
            TransactionInterface::TYPE => 'type',
            TransactionInterface::AMOUNT => 'amount',
            TransactionInterface::CURRENCY_CODE => 'currency code',
            TransactionInterface::STATE => 'state',
            TransactionInterface::GATEWAY_TRANSACTION_ID => '124',
            TransactionInterface::EMAIL => 'email@example.com',
            TransactionInterface::ORDER_ID => '123',
            TransactionInterface::IP => '0.0.0.0',
            TransactionInterface::RESPONSE_MESSAGE => 'message',
            TransactionInterface::ERROR_CODE => 'error_code',
            TransactionInterface::ERROR_DETAIL => 'detail',
            TransactionInterface::CVV_CODE => 'cvv_code',
            TransactionInterface::CVV_MESSAGE => 'cvv_message',
            TransactionInterface::AVS_CODE => 'avs_code',
            TransactionInterface::AVS_MESSAGE => 'avs_message',
            TransactionInterface::SUBSCRIBE_PRO_ERROR_CLASS => 'class',
            TransactionInterface::SUBSCRIBE_PRO_ERROR_TYPE => 'type',
            TransactionInterface::CREATED => '2016-12-12',
        ];
        $expectedData = [
            TransactionInterface::CURRENCY_CODE => 'currency code',
            TransactionInterface::EMAIL => 'email@example.com',
            TransactionInterface::ORDER_ID => '123',
            TransactionInterface::IP => '0.0.0.0'
        ];

        $this->transaction->importData($data);
        $this->assertEquals($expectedData, $this->transaction->getVerifyFormData());
    }
    
    public function testGetServiceFormData()
    {
        $data = [
            TransactionInterface::TOKEN => 'token',
            TransactionInterface::GATEWAY_SPECIFIC_RESPONSE => 'response',
            TransactionInterface::GATEWAY_TYPE => 'type',
            TransactionInterface::AUTHORIZE_NET_RESPONSE_REASON_CODE => 'code',
            TransactionInterface::SUBSCRIBE_PRO_ERROR_DESCRIPTION => 'description',
            TransactionInterface::CREDITCARD_TYPE => 'type',
            TransactionInterface::CREDITCARD_LAST_DIGITS => '1111',
            TransactionInterface::CREDITCARD_FIRST_DIGITS => '411111',
            TransactionInterface::CREDITCARD_MONTH => '04',
            TransactionInterface::CREDITCARD_YEAR => '2019',
            TransactionInterface::BILLING_ADDRESS => '123',
            TransactionInterface::UNIQUE_ID => '918273645',
            TransactionInterface::REF_PAYMENT_PROFILE_ID => '414',
            TransactionInterface::REF_TRANSACTION_ID => '2323',
            TransactionInterface::REF_GATEWAY_ID => '525',
            TransactionInterface::REF_TOKEN => 'token',
            TransactionInterface::TYPE => 'type',
            TransactionInterface::AMOUNT => 'amount',
            TransactionInterface::CURRENCY_CODE => 'currency code',
            TransactionInterface::STATE => 'state',
            TransactionInterface::GATEWAY_TRANSACTION_ID => '124',
            TransactionInterface::EMAIL => 'email@example.com',
            TransactionInterface::ORDER_ID => '123',
            TransactionInterface::IP => '0.0.0.0',
            TransactionInterface::RESPONSE_MESSAGE => 'message',
            TransactionInterface::ERROR_CODE => 'error_code',
            TransactionInterface::ERROR_DETAIL => 'detail',
            TransactionInterface::CVV_CODE => 'cvv_code',
            TransactionInterface::CVV_MESSAGE => 'cvv_message',
            TransactionInterface::AVS_CODE => 'avs_code',
            TransactionInterface::AVS_MESSAGE => 'avs_message',
            TransactionInterface::SUBSCRIBE_PRO_ERROR_CLASS => 'class',
            TransactionInterface::SUBSCRIBE_PRO_ERROR_TYPE => 'type',
            TransactionInterface::CREATED => '2016-12-12',
        ];
        $expectedData = [
            TransactionInterface::AMOUNT => 'amount',
            TransactionInterface::CURRENCY_CODE => 'currency code',
        ];

        $this->transaction->importData($data);
        $this->assertEquals($expectedData, $this->transaction->getServiceFormData());
    }

    /**
     * @param \SubscribePro\Service\Address\AddressInterface|null $address
     * @param array $expectedData
     * @dataProvider getTokenFormDataTest
     */
    public function testGetTokenFormData($address, $expectedData)
    {
        $data = [
            TransactionInterface::TOKEN => 'token',
            TransactionInterface::GATEWAY_SPECIFIC_RESPONSE => 'response',
            TransactionInterface::GATEWAY_TYPE => 'type',
            TransactionInterface::AUTHORIZE_NET_RESPONSE_REASON_CODE => 'code',
            TransactionInterface::SUBSCRIBE_PRO_ERROR_DESCRIPTION => 'description',
            TransactionInterface::CREDITCARD_TYPE => 'type',
            TransactionInterface::CREDITCARD_LAST_DIGITS => '1111',
            TransactionInterface::CREDITCARD_FIRST_DIGITS => '411111',
            TransactionInterface::CREDITCARD_MONTH => '04',
            TransactionInterface::CREDITCARD_YEAR => '2019',
            TransactionInterface::BILLING_ADDRESS => '123',
            TransactionInterface::UNIQUE_ID => '129834765',
            TransactionInterface::REF_PAYMENT_PROFILE_ID => '414',
            TransactionInterface::REF_TRANSACTION_ID => '2323',
            TransactionInterface::REF_GATEWAY_ID => '525',
            TransactionInterface::REF_TOKEN => 'token',
            TransactionInterface::TYPE => 'type',
            TransactionInterface::AMOUNT => 'amount',
            TransactionInterface::CURRENCY_CODE => 'currency code',
            TransactionInterface::STATE => 'state',
            TransactionInterface::GATEWAY_TRANSACTION_ID => '124',
            TransactionInterface::EMAIL => 'email@example.com',
            TransactionInterface::ORDER_ID => '123',
            TransactionInterface::IP => '0.0.0.0',
            TransactionInterface::RESPONSE_MESSAGE => 'message',
            TransactionInterface::ERROR_CODE => 'error_code',
            TransactionInterface::ERROR_DETAIL => 'detail',
            TransactionInterface::CVV_CODE => 'cvv_code',
            TransactionInterface::CVV_MESSAGE => 'cvv_message',
            TransactionInterface::AVS_CODE => 'avs_code',
            TransactionInterface::AVS_MESSAGE => 'avs_message',
            TransactionInterface::SUBSCRIBE_PRO_ERROR_CLASS => 'class',
            TransactionInterface::SUBSCRIBE_PRO_ERROR_TYPE => 'type',
            TransactionInterface::CREATED => '2016-12-12',
        ];

        $this->transaction->importData($data);
        $this->assertEquals($expectedData, $this->transaction->getTokenFormData($address));
    }

    public function getTokenFormDataTest()
    {
        return [
            'Without address' => [
                'address' => null,
                'expectedData' => [
                    TransactionInterface::AMOUNT => 'amount',
                    TransactionInterface::CURRENCY_CODE => 'currency code',
                    TransactionInterface::EMAIL => 'email@example.com',
                    TransactionInterface::ORDER_ID => '123',
                    TransactionInterface::IP => '0.0.0.0',
                    TransactionInterface::CREDITCARD_MONTH => '04',
                    TransactionInterface::CREDITCARD_YEAR => '2019',
                    TransactionInterface::UNIQUE_ID => '129834765'
                ]
            ],
            'With address' => [
                'address' => new Address([
                    AddressInterface::CITY => 'city',
                    AddressInterface::COUNTRY => 'country',
                    AddressInterface::COMPANY => 'company',
                    AddressInterface::FIRST_NAME => 'first name',
                    AddressInterface::LAST_NAME => 'last name',
                    AddressInterface::MAGENTO_ADDRESS_ID => '23',
                    AddressInterface::MIDDLE_NAME => 'middle name',
                    AddressInterface::PHONE => 'phone',
                    AddressInterface::POSTCODE => 'postcode',
                    AddressInterface::REGION => 'region',
                    AddressInterface::CREATED => '2016-12-12',
                    AddressInterface::STREET1 => 'street1',
                    AddressInterface::STREET2 => 'street2',
                ]),
                'expectedData' => [
                    TransactionInterface::AMOUNT => 'amount',
                    TransactionInterface::CURRENCY_CODE => 'currency code',
                    TransactionInterface::EMAIL => 'email@example.com',
                    TransactionInterface::ORDER_ID => '123',
                    TransactionInterface::IP => '0.0.0.0',
                    TransactionInterface::CREDITCARD_MONTH => '04',
                    TransactionInterface::CREDITCARD_YEAR => '2019',
                    TransactionInterface::UNIQUE_ID => '129834765',
                    TransactionInterface::BILLING_ADDRESS => [
                        AddressInterface::CITY => 'city',
                        AddressInterface::COUNTRY => 'country',
                        AddressInterface::COMPANY => 'company',
                        AddressInterface::FIRST_NAME => 'first name',
                        AddressInterface::LAST_NAME => 'last name',
                        AddressInterface::MIDDLE_NAME => 'middle name',
                        AddressInterface::PHONE => 'phone',
                        AddressInterface::POSTCODE => 'postcode',
                        AddressInterface::REGION => 'region',
                        AddressInterface::STREET1 => 'street1',
                        AddressInterface::STREET2 => 'street2',
                    ],
                ]
            ]    
        ];
    }
}
