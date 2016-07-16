<?php

namespace SubscribePro\Tests\Service\PaymentProfile;

use SubscribePro\Service\Address\AddressInterface;
use SubscribePro\Service\PaymentProfile\PaymentProfile;
use SubscribePro\Service\PaymentProfile\PaymentProfileInterface;

class PaymentProfileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     */
    protected $paymentProfile;

    /**
     * @var \SubscribePro\Service\Address\Address|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $billingAddressMock;

    protected function setUp()
    {
        $this->billingAddressMock = $this->getMockBuilder('SubscribePro\Service\Address\Address')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentProfile = new PaymentProfile([
            PaymentProfileInterface::BILLING_ADDRESS => $this->billingAddressMock
        ]);
    }

    /**
     * @param array $data
     * @param array $billingData
     * @param array $expectedData
     * @dataProvider importDataDataProvider
     */
    public function testImportData($data, $billingData, $expectedData)
    {
        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('importData')
            ->with($billingData)
            ->willReturnSelf();

        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn($billingData);

        $this->paymentProfile->importData($data);
        $this->assertEquals($expectedData, $this->paymentProfile->toArray());
    }

    /**
     * @return array
     */
    public function importDataDataProvider()
    {
        return [
            'Empty data' => [
                'data' => [],
                'billingData' => [],
                'expectedData' => [
                    PaymentProfileInterface::BILLING_ADDRESS => []
                ]
            ],
            'Billing address data is not array' => [
                'data' => [
                    PaymentProfileInterface::BILLING_ADDRESS => 'invalid'
                ],
                'billingData' => [],
                'expectedData' => [
                    PaymentProfileInterface::BILLING_ADDRESS => []
                ]
            ],
            'Billing address data is an array' => [
                'data' => [
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::CITY => 'city'
                    ]
                ],
                'billingData' => [AddressInterface::CITY => 'city'],
                'expectedData' => [
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::CITY => 'city'
                    ]
                ]
            ],
        ];
    }

    public function testImportDataWithAddressInstance()
    {
        $data = [
            PaymentProfileInterface::ID => 111,
            PaymentProfileInterface::BILLING_ADDRESS => $this->billingAddressMock
        ];
        $billingData = [AddressInterface::CITY => 'city'];
        $expectedData = [
            PaymentProfileInterface::ID => 111,
            PaymentProfileInterface::BILLING_ADDRESS => $billingData
        ];

        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn($billingData);

        $this->paymentProfile->importData($data);
        $this->assertEquals($expectedData, $this->paymentProfile->toArray());
    }

    public function testToArray()
    {
        $billingData = ['test_key' => 'test_value'];

        $expectedData = [
            PaymentProfileInterface::ID => 111,
            PaymentProfileInterface::CUSTOMER_ID => 123,
            PaymentProfileInterface::BILLING_ADDRESS => $billingData
        ];

        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn($billingData);

        $this->paymentProfile->setId(111);
        $this->paymentProfile->setCustomerId(123);

        $this->assertEquals($expectedData, $this->paymentProfile->toArray());
    }

    /**
     * @param array $data
     * @param array $billingData
     * @param bool $isValid
     * @param bool $isAsChildValid
     * @dataProvider isValidDataProvider
     */
    public function testIsValid($data, $billingData, $isValid, $isAsChildValid)
    {
        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('importData')
            ->with($billingData)
            ->willReturnSelf();

        if (null === $isAsChildValid) {
            $this->billingAddressMock->expects($this->never())->method('isAsChildValid');
        } else {
            $this->billingAddressMock->expects($this->atLeastOnce())
                ->method('isAsChildValid')
                ->willReturn($isAsChildValid);
        }

        $this->paymentProfile->importData($data);
        $this->assertEquals($isValid, $this->paymentProfile->isValid());
    }

    /**
     * @return array
     */
    public function isValidDataProvider()
    {
        return [
            'Not valid: new: empty data' => [
                'data' => [],
                'billingData' => [],
                'isValid' => false,
                'isAsChildValid' => null
            ],
            'Not valid: new: empty billing address' => [
                'data' => [
                    PaymentProfileInterface::CUSTOMER_ID => 11,
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2020',
                    PaymentProfileInterface::BILLING_ADDRESS => []
                ],
                'billingData' => [],
                'isValid' => false,
                'isAsChildValid' => false
            ],
            'Not valid: new: without customer ID' => [
                'data' => [
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2020',
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::FIRST_NAME => 'name',
                        AddressInterface::LAST_NAME => 'surname',
                    ]
                ],
                'billingData' => [
                    AddressInterface::FIRST_NAME => 'name',
                    AddressInterface::LAST_NAME => 'surname',
                ],
                'isValid' => false,
                'isAsChildValid' => null
            ],
            'Not valid: new: without credit card number' => [
                'data' => [
                    PaymentProfileInterface::CUSTOMER_ID => 11,
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2020',
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::FIRST_NAME => 'name',
                        AddressInterface::LAST_NAME => 'surname',
                    ]
                ],
                'billingData' => [
                    AddressInterface::FIRST_NAME => 'name',
                    AddressInterface::LAST_NAME => 'surname',
                ],
                'isValid' => false,
                'isAsChildValid' => null
            ],
            'Valid: new: with customer ID' => [
                'data' => [
                    PaymentProfileInterface::CUSTOMER_ID => 11,
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2020',
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::FIRST_NAME => 'name',
                        AddressInterface::LAST_NAME => 'surname',
                    ]
                ],
                'billingData' => [
                    AddressInterface::FIRST_NAME => 'name',
                    AddressInterface::LAST_NAME => 'surname',
                ],
                'isValid' => true,
                'isAsChildValid' => true
            ],
            'Valid: new: with magento customer ID' => [
                'data' => [
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => 11,
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2020',
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::FIRST_NAME => 'name',
                        AddressInterface::LAST_NAME => 'surname',
                    ]
                ],
                'billingData' => [
                    AddressInterface::FIRST_NAME => 'name',
                    AddressInterface::LAST_NAME => 'surname',
                ],
                'isValid' => true,
                'isAsChildValid' => true
            ],
            'Valid: not new' => [
                'data' => [
                    PaymentProfileInterface::ID => 11,
                ],
                'billingData' => [],
                'isValid' => true,
                'isAsChildValid' => true
            ],
            'Not valid: billing address not valid' => [
                'data' => [
                    PaymentProfileInterface::ID => 11,
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::FIRST_NAME => 'name',
                        AddressInterface::LAST_NAME => 'surname',
                    ]
                ],
                'billingData' => [
                    AddressInterface::FIRST_NAME => 'name',
                    AddressInterface::LAST_NAME => 'surname',
                ],
                'isValid' => false,
                'isAsChildValid' => false
            ],
        ];
    }

    /**
     * @param array $data
     * @param bool $isValid
     * @dataProvider isTokenDataValidDataProvider
     */
    public function testIsTokenDataValid($data, $isValid)
    {
        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('importData')
            ->with([])
            ->willReturnSelf();

        $this->paymentProfile->importData($data);
        $this->assertEquals($isValid, $this->paymentProfile->isTokenDataValid());
    }

    /**
     * @return array
     */
    public function isTokenDataValidDataProvider()
    {
        return [
            'Not valid: empty data' => [
                'data' => [],
                'isValid' => false
            ],
            'Valid: with customer ID' => [
                'data' => [
                    PaymentProfileInterface::CUSTOMER_ID => 11,
                ],
                'isValid' => true
            ],
            'Valid: with magento customer ID' => [
                'data' => [
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => 11,
                ],
                'isValid' => true
            ],
        ];
    }

    /**
     * @param array $data
     * @param array $billingData
     * @param bool $isValid
     * @param bool $isAsChildValid
     * @dataProvider isThirdPartyDataValidDataProvider
     */
    public function testIsThirdPartyDataValid($data, $billingData, $isValid, $isAsChildValid)
    {
        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('importData')
            ->with($billingData)
            ->willReturnSelf();

        if (null === $isAsChildValid) {
            $this->billingAddressMock->expects($this->never())->method('isAsChildValid');
        } else {
            $this->billingAddressMock->expects($this->atLeastOnce())
                ->method('isAsChildValid')
                ->willReturn($isAsChildValid);
        }

        $this->paymentProfile->importData($data);
        $this->assertEquals($isValid, $this->paymentProfile->isThirdPartyDataValid());
    }

    /**
     * @return array
     */
    public function isThirdPartyDataValidDataProvider()
    {
        return [
            'Not valid: without third party token' => [
                'data' => [
                    PaymentProfileInterface::CUSTOMER_ID => 11,
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::CITY => 'city'
                    ],
                ],
                'billingData' => [
                    AddressInterface::CITY => 'city'
                ],
                'isValid' => false,
                'isAsChildValid' => null
            ],
            'Not valid: without customer ID' => [
                'data' => [
                    PaymentProfileInterface::THIRD_PARTY_PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::CITY => 'city'
                    ],
                ],
                'billingData' => [
                    AddressInterface::CITY => 'city'
                ],
                'isValid' => false,
                'isAsChildValid' => null
            ],
            'Not valid: billing address is not valid' => [
                'data' => [
                    PaymentProfileInterface::CUSTOMER_ID => 11,
                    PaymentProfileInterface::THIRD_PARTY_PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::CITY => 'city'
                    ],
                ],
                'billingData' => [
                    AddressInterface::CITY => 'city'
                ],
                'isValid' => false,
                'isAsChildValid' => false
            ],
            'Valid' => [
                'data' => [
                    PaymentProfileInterface::CUSTOMER_ID => 11,
                    PaymentProfileInterface::THIRD_PARTY_PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::CITY => 'city'
                    ],
                ],
                'billingData' => [
                    AddressInterface::CITY => 'city'
                ],
                'isValid' => true,
                'isAsChildValid' => true
            ],
        ];
    }

    /**
     * @param bool $isNew
     * @param array $data
     * @param array $billingData
     * @param array $expectedData
     * @param array $billingFormData
     * @dataProvider getFormDataProvider
     */
    public function testGetFormData($isNew, $data, $billingData, $expectedData, $billingFormData)
    {
        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('importData')
            ->with($billingData)
            ->willReturnSelf();

        $this->billingAddressMock->expects($this->once())
            ->method('getAsChildFormData')
            ->with($isNew)
            ->willReturn($billingFormData);

        $this->paymentProfile->importData($data);
        $this->assertEquals($expectedData, $this->paymentProfile->getFormData());
    }

    /**
     * @return array
     */
    public function getFormDataProvider()
    {
        return [
            'New profile without address' => [
                'isNew' => true,
                'data' => [
                    PaymentProfileInterface::CUSTOMER_ID => '123',
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => '124',
                    PaymentProfileInterface::CUSTOMER_EMAIL => '125',
                    PaymentProfileInterface::CREDITCARD_TYPE => 'visa',
                    PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
                    PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '411',
                    PaymentProfileInterface::CREDITCARD_LAST_DIGITS => '111111',
                    PaymentProfileInterface::CREDITCARD_VERIFICATION_VALUE => 123,
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                    PaymentProfileInterface::GATEWAY => 'gateway',
                    PaymentProfileInterface::PAYMENT_METHOD_TYPE => 'card',
                    PaymentProfileInterface::PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::PAYMENT_VAULT => 'vault',
                    PaymentProfileInterface::THIRD_PARTY_VAULT_TYPE => 'type',
                    PaymentProfileInterface::THIRD_PARTY_PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::STATUS => 'success',
                    PaymentProfileInterface::CREATED => '2016-12-12',
                    PaymentProfileInterface::UPDATED => '2016-12-12',
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::MAGENTO_ADDRESS_ID => '23',
                    ]
                ],
                'billingData' => [AddressInterface::MAGENTO_ADDRESS_ID => '23'],
                'expectedData' => [
                    PaymentProfileInterface::CUSTOMER_ID => '123',
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => '124',
                    PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
                    PaymentProfileInterface::CREDITCARD_VERIFICATION_VALUE => 123,
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                ],
                'billingFormData' => []
            ],
            'New profile with address' => [
                'isNew' => true,
                'data' => [
                    PaymentProfileInterface::CUSTOMER_ID => '123',
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => '124',
                    PaymentProfileInterface::CUSTOMER_EMAIL => '125',
                    PaymentProfileInterface::CREDITCARD_TYPE => 'visa',
                    PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
                    PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '411',
                    PaymentProfileInterface::CREDITCARD_LAST_DIGITS => '111111',
                    PaymentProfileInterface::CREDITCARD_VERIFICATION_VALUE => 123,
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                    PaymentProfileInterface::GATEWAY => 'gateway',
                    PaymentProfileInterface::PAYMENT_METHOD_TYPE => 'card',
                    PaymentProfileInterface::PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::PAYMENT_VAULT => 'vault',
                    PaymentProfileInterface::THIRD_PARTY_VAULT_TYPE => 'type',
                    PaymentProfileInterface::THIRD_PARTY_PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::STATUS => 'success',
                    PaymentProfileInterface::CREATED => '2016-12-12',
                    PaymentProfileInterface::UPDATED => '2016-12-12',
                    PaymentProfileInterface::BILLING_ADDRESS => [
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
                    ]
                ],
                'billingData' => [
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
                ],
                'expectedData' => [
                    PaymentProfileInterface::CUSTOMER_ID => '123',
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => '124',
                    PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
                    PaymentProfileInterface::CREDITCARD_VERIFICATION_VALUE => 123,
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::CITY => 'city',
                        AddressInterface::COUNTRY => 'country'
                    ]
                ],
                'billingFormData' => [
                    AddressInterface::CITY => 'city',
                    AddressInterface::COUNTRY => 'country'
                ]
            ],
            'Not new profile with address' => [
                'isNew' => false,
                'data' => [
                    PaymentProfileInterface::ID => 555,
                    PaymentProfileInterface::CUSTOMER_ID => '123',
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => '124',
                    PaymentProfileInterface::CUSTOMER_EMAIL => '125',
                    PaymentProfileInterface::CREDITCARD_TYPE => 'visa',
                    PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
                    PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '411',
                    PaymentProfileInterface::CREDITCARD_LAST_DIGITS => '111111',
                    PaymentProfileInterface::CREDITCARD_VERIFICATION_VALUE => 123,
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                    PaymentProfileInterface::GATEWAY => 'gateway',
                    PaymentProfileInterface::PAYMENT_METHOD_TYPE => 'card',
                    PaymentProfileInterface::PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::PAYMENT_VAULT => 'vault',
                    PaymentProfileInterface::THIRD_PARTY_VAULT_TYPE => 'type',
                    PaymentProfileInterface::THIRD_PARTY_PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::STATUS => 'success',
                    PaymentProfileInterface::CREATED => '2016-12-12',
                    PaymentProfileInterface::UPDATED => '2016-12-12',
                    PaymentProfileInterface::BILLING_ADDRESS => [
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
                    ]
                ],
                'billingData' => [
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
                ],
                'expectedData' => [
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                    PaymentProfileInterface::BILLING_ADDRESS => [
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
                    ]
                ],
                'billingFormData' => [
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
                    AddressInterface::STREET2 => 'street2'
                ]
            ],
        ];
    }

    /**
     * @param bool $isNew
     * @param array $data
     * @param array $billingData
     * @param array $expectedData
     * @param array $billingFormData
     * @dataProvider getTokenFormDataProvider
     */
    public function testGetTokenFormData($isNew, $data, $billingData, $expectedData, $billingFormData)
    {
        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('importData')
            ->with($billingData)
            ->willReturnSelf();

        $this->billingAddressMock->expects($this->once())
            ->method('getAsChildFormData')
            ->with($isNew)
            ->willReturn($billingFormData);

        $this->paymentProfile->importData($data);
        $this->assertEquals($expectedData, $this->paymentProfile->getTokenFormData());
    }

    /**
     * @return array
     */
    public function getTokenFormDataProvider()
    {
        return [
            'Without address' => [
                'isNew' => true,
                'data' => [
                    PaymentProfileInterface::CUSTOMER_ID => '123',
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => '124',
                    PaymentProfileInterface::CUSTOMER_EMAIL => '125',
                    PaymentProfileInterface::CREDITCARD_TYPE => 'visa',
                    PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
                    PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '411',
                    PaymentProfileInterface::CREDITCARD_LAST_DIGITS => '111111',
                    PaymentProfileInterface::CREDITCARD_VERIFICATION_VALUE => 123,
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                    PaymentProfileInterface::GATEWAY => 'gateway',
                    PaymentProfileInterface::PAYMENT_METHOD_TYPE => 'card',
                    PaymentProfileInterface::PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::PAYMENT_VAULT => 'vault',
                    PaymentProfileInterface::THIRD_PARTY_VAULT_TYPE => 'type',
                    PaymentProfileInterface::THIRD_PARTY_PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::STATUS => 'success',
                    PaymentProfileInterface::CREATED => '2016-12-12',
                    PaymentProfileInterface::UPDATED => '2016-12-12',
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::MAGENTO_ADDRESS_ID => '23',
                    ]
                ],
                'billingData' => [AddressInterface::MAGENTO_ADDRESS_ID => '23'],
                'expectedData' => [
                    PaymentProfileInterface::CUSTOMER_ID => '123',
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => '124',
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                ],
                'billingFormData' => []
            ],
            'With address' => [
                'isNew' => true,
                'data' => [
                    PaymentProfileInterface::CUSTOMER_ID => '123',
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => '124',
                    PaymentProfileInterface::CUSTOMER_EMAIL => '125',
                    PaymentProfileInterface::CREDITCARD_TYPE => 'visa',
                    PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
                    PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '411',
                    PaymentProfileInterface::CREDITCARD_LAST_DIGITS => '111111',
                    PaymentProfileInterface::CREDITCARD_VERIFICATION_VALUE => 123,
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                    PaymentProfileInterface::GATEWAY => 'gateway',
                    PaymentProfileInterface::PAYMENT_METHOD_TYPE => 'card',
                    PaymentProfileInterface::PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::PAYMENT_VAULT => 'vault',
                    PaymentProfileInterface::THIRD_PARTY_VAULT_TYPE => 'type',
                    PaymentProfileInterface::THIRD_PARTY_PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::STATUS => 'success',
                    PaymentProfileInterface::CREATED => '2016-12-12',
                    PaymentProfileInterface::UPDATED => '2016-12-12',
                    PaymentProfileInterface::BILLING_ADDRESS => [
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
                    ]
                ],
                'billingData' => [
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
                ],
                'expectedData' => [
                    PaymentProfileInterface::CUSTOMER_ID => '123',
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => '124',
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                    PaymentProfileInterface::BILLING_ADDRESS => [
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
                    ]
                ],
                'billingFormData' => [
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
                ]
            ],
        ];
    }

    /**
     * @param bool $isNew
     * @param array $data
     * @param array $billingData
     * @param array $expectedData
     * @param array $billingFormData
     * @dataProvider getThirdPartyTokenFormDataProvider
     */
    public function testGetThirdPartyTokenFormData($isNew, $data, $billingData, $expectedData, $billingFormData)
    {
        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('importData')
            ->with($billingData)
            ->willReturnSelf();

        $this->billingAddressMock->expects($this->once())
            ->method('getAsChildFormData')
            ->with($isNew)
            ->willReturn($billingFormData);

        $this->paymentProfile->importData($data);
        $this->assertEquals($expectedData, $this->paymentProfile->getThirdPartyTokenFormData());
    }

    /**
     * @return array
     */
    public function getThirdPartyTokenFormDataProvider()
    {
        return [
            'Without address' => [
                'isNew' => true,
                'data' => [
                    PaymentProfileInterface::CUSTOMER_ID => '123',
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => '124',
                    PaymentProfileInterface::CUSTOMER_EMAIL => '125',
                    PaymentProfileInterface::CREDITCARD_TYPE => 'visa',
                    PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
                    PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '411',
                    PaymentProfileInterface::CREDITCARD_LAST_DIGITS => '111111',
                    PaymentProfileInterface::CREDITCARD_VERIFICATION_VALUE => 123,
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                    PaymentProfileInterface::GATEWAY => 'gateway',
                    PaymentProfileInterface::PAYMENT_METHOD_TYPE => 'card',
                    PaymentProfileInterface::PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::PAYMENT_VAULT => 'vault',
                    PaymentProfileInterface::THIRD_PARTY_VAULT_TYPE => 'type',
                    PaymentProfileInterface::THIRD_PARTY_PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::STATUS => 'success',
                    PaymentProfileInterface::CREATED => '2016-12-12',
                    PaymentProfileInterface::UPDATED => '2016-12-12',
                    PaymentProfileInterface::BILLING_ADDRESS => [
                        AddressInterface::MAGENTO_ADDRESS_ID => '23',
                    ]
                ],
                'billingData' => [
                    AddressInterface::MAGENTO_ADDRESS_ID => '23',
                ],
                'expectedData' => [
                    PaymentProfileInterface::CUSTOMER_ID => '123',
                    PaymentProfileInterface::THIRD_PARTY_VAULT_TYPE => 'type',
                    PaymentProfileInterface::THIRD_PARTY_PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::CREDITCARD_TYPE => 'visa',
                    PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '411',
                    PaymentProfileInterface::CREDITCARD_LAST_DIGITS => '111111',
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                ],
                'billingFormData' => []
            ],
            'With address' => [
                'isNew' => true,
                'data' => [
                    PaymentProfileInterface::CUSTOMER_ID => '123',
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => '124',
                    PaymentProfileInterface::CUSTOMER_EMAIL => '125',
                    PaymentProfileInterface::CREDITCARD_TYPE => 'visa',
                    PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
                    PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '411',
                    PaymentProfileInterface::CREDITCARD_LAST_DIGITS => '111111',
                    PaymentProfileInterface::CREDITCARD_VERIFICATION_VALUE => 123,
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                    PaymentProfileInterface::GATEWAY => 'gateway',
                    PaymentProfileInterface::PAYMENT_METHOD_TYPE => 'card',
                    PaymentProfileInterface::PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::PAYMENT_VAULT => 'vault',
                    PaymentProfileInterface::THIRD_PARTY_VAULT_TYPE => 'type',
                    PaymentProfileInterface::THIRD_PARTY_PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::STATUS => 'success',
                    PaymentProfileInterface::CREATED => '2016-12-12',
                    PaymentProfileInterface::UPDATED => '2016-12-12',
                    PaymentProfileInterface::BILLING_ADDRESS => [
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
                    ]
                ],
                'billingData' => [
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
                ],
                'expectedData' => [
                    PaymentProfileInterface::CUSTOMER_ID => '123',
                    PaymentProfileInterface::THIRD_PARTY_VAULT_TYPE => 'type',
                    PaymentProfileInterface::THIRD_PARTY_PAYMENT_TOKEN => 'token',
                    PaymentProfileInterface::CREDITCARD_TYPE => 'visa',
                    PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '411',
                    PaymentProfileInterface::CREDITCARD_LAST_DIGITS => '111111',
                    PaymentProfileInterface::CREDITCARD_MONTH => '04',
                    PaymentProfileInterface::CREDITCARD_YEAR => '2018',
                    PaymentProfileInterface::BILLING_ADDRESS => [
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
                    ]
                ],
                'billingFormData' => [
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
                ]
            ],
        ];
    }
}
