<?php

namespace SubscribePro\Tests\Service\Subscription;

use SubscribePro\Service\Address\AddressInterface;
use SubscribePro\Service\PaymentProfile\PaymentProfileInterface;
use SubscribePro\Service\Subscription\Subscription;
use SubscribePro\Service\Subscription\SubscriptionInterface;

class SubscriptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Subscription\SubscriptionInterface
     */
    protected $subscription;

    /**
     * @var \SubscribePro\Service\PaymentProfile\PaymentProfileInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $paymentProfileMock;

    /**
     * @var \SubscribePro\Service\Address\AddressInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $shippingAddressMock;

    protected function setUp(): void
    {
        $this->paymentProfileMock = $this->createProfileMock();
        $this->shippingAddressMock = $this->createAddressMock();
        
        $this->subscription = new Subscription([
            SubscriptionInterface::SHIPPING_ADDRESS => $this->shippingAddressMock,
            SubscriptionInterface::PAYMENT_PROFILE => $this->paymentProfileMock
        ]);
    }

    /**
     * @param array $data
     * @param array $expectedAddressData
     * @param int $addressId
     * @param array $expectedPaymentProfileData
     * @param int $profileId
     * @param array $expectedData
     * @param boolean $addressProvided
     * @dataProvider importDataDataProvider
     */
    public function testImportData($data, $expectedAddressData, $addressId, $expectedPaymentProfileData, $profileId, $expectedData, $addressProvided)
    {
        $this->paymentProfileMock->expects($this->once())
            ->method('importData')
            ->with($expectedPaymentProfileData)
            ->willReturnSelf();
        $this->paymentProfileMock->expects($this->once())
            ->method('toArray')
            ->willReturn($expectedPaymentProfileData);
        $this->paymentProfileMock->expects($this->any())->method('getId')->willReturn($profileId);
        if ($addressProvided) {
            $this->shippingAddressMock->expects($this->once())
                ->method('importData')
                ->with($expectedAddressData)
                ->willReturnSelf();
            $this->shippingAddressMock->expects($this->once())
                ->method('toArray')
                ->willReturn($expectedAddressData);
        }
        $this->shippingAddressMock->expects($this->any())->method('getId')->willReturn($addressId);
        $this->subscription->importData($data);
        $this->assertEquals($expectedData, $this->subscription->toArray());
    }

    /**
     * @return array
     */
    public function importDataDataProvider()
    {
        return [
            'Empty data' => [
                'data' => [],
                'expectedAddressData' => [],
                'shippingAddressId' => null,
                'expectedPaymentProfileData' => [],
                'paymentProfileId' => null,
                'expectedData' => [
                    SubscriptionInterface::SHIPPING_ADDRESS => [],
                    SubscriptionInterface::PAYMENT_PROFILE => [],
                    SubscriptionInterface::SHIPPING_ADDRESS_ID => null,
                    SubscriptionInterface::PAYMENT_PROFILE_ID => null,
                ],
                'addressProvided' => true,
            ],
            'Shipping address and payment profile data are not array' => [
                'data' => [
                    SubscriptionInterface::SHIPPING_ADDRESS => 'invalid',
                    SubscriptionInterface::PAYMENT_PROFILE => 'invalid'
                ],
                'expectedAddressData' => [],
                'shippingAddressId' => null,
                'expectedPaymentProfileData' => [],
                'paymentProfileId' => null,
                'expectedData' => [
                    SubscriptionInterface::SHIPPING_ADDRESS => [],
                    SubscriptionInterface::PAYMENT_PROFILE => [],
                    SubscriptionInterface::SHIPPING_ADDRESS_ID => null,
                    SubscriptionInterface::PAYMENT_PROFILE_ID => null,
                ],
                'addressProvided' => true,
            ],
            'Shipping address data is array and payment profile data is array' => [
                'data' => [
                    SubscriptionInterface::SHIPPING_ADDRESS => [AddressInterface::CITY => 'city'],
                    SubscriptionInterface::PAYMENT_PROFILE => [PaymentProfileInterface::CREDITCARD_YEAR => 2016],
                ],
                'expectedAddressData' => [AddressInterface::CITY => 'city'],
                'shippingAddressId' => null,
                'expectedPaymentProfileData' => [PaymentProfileInterface::CREDITCARD_YEAR => 2016],
                'paymentProfileId' => null,
                'expectedData' => [
                    SubscriptionInterface::SHIPPING_ADDRESS => [AddressInterface::CITY => 'city'],
                    SubscriptionInterface::PAYMENT_PROFILE => [PaymentProfileInterface::CREDITCARD_YEAR => 2016],
                    SubscriptionInterface::SHIPPING_ADDRESS_ID => null,
                    SubscriptionInterface::PAYMENT_PROFILE_ID => null,
                ],
                'addressProvided' => true,
            ],
            'Shipping address data with ID and payment profile data with ID' => [
                'data' => [
                    SubscriptionInterface::SHIPPING_ADDRESS => [AddressInterface::ID => 112],
                    SubscriptionInterface::PAYMENT_PROFILE => [PaymentProfileInterface::ID => 113],
                ],
                'expectedAddressData' => [AddressInterface::ID => 112],
                'shippingAddressId' => 112,
                'expectedPaymentProfileData' => [PaymentProfileInterface::ID => 113],
                'paymentProfileId' => 113,
                'expectedData' => [
                    SubscriptionInterface::SHIPPING_ADDRESS_ID => 112,
                    SubscriptionInterface::SHIPPING_ADDRESS => [AddressInterface::ID => 112],
                    SubscriptionInterface::PAYMENT_PROFILE => [PaymentProfileInterface::ID => 113],
                    SubscriptionInterface::PAYMENT_PROFILE_ID => 113,
                ],
                'addressProvided' => true,
            ],
            'With shipping_address_id and payment_profile_id' => [
                'data' => [
                    SubscriptionInterface::SHIPPING_ADDRESS_ID => 200,
                    SubscriptionInterface::SHIPPING_ADDRESS => [AddressInterface::ID => 112],
                    SubscriptionInterface::PAYMENT_PROFILE_ID => 300,
                    SubscriptionInterface::PAYMENT_PROFILE => [PaymentProfileInterface::ID => 113],
                ],
                'expectedAddressData' => [AddressInterface::ID => 112],
                'shippingAddressId' => 112,
                'expectedPaymentProfileData' => [PaymentProfileInterface::ID => 113],
                'paymentProfileId' => 113,
                'expectedData' => [
                    SubscriptionInterface::SHIPPING_ADDRESS_ID => 200,
                    SubscriptionInterface::SHIPPING_ADDRESS => [AddressInterface::ID => 112],
                    SubscriptionInterface::PAYMENT_PROFILE => [PaymentProfileInterface::ID => 113],
                    SubscriptionInterface::PAYMENT_PROFILE_ID => 300,
                ],
                'addressProvided' => true,
            ],
            'No shipping address provided' => [
                'data' => [
                    SubscriptionInterface::PAYMENT_PROFILE => [PaymentProfileInterface::CREDITCARD_YEAR => 2016],
                ],
                'expectedAddressData' => null,
                'shippingAddressId' => null,
                'expectedPaymentProfileData' => [PaymentProfileInterface::CREDITCARD_YEAR => 2016],
                'paymentProfileId' => null,
                'expectedData' => [
                    SubscriptionInterface::PAYMENT_PROFILE => [PaymentProfileInterface::CREDITCARD_YEAR => 2016],
                    SubscriptionInterface::PAYMENT_PROFILE_ID => null,
                    SubscriptionInterface::SHIPPING_ADDRESS => null,
                ],
                'addressProvided' => false,
            ]
        ];
    }

    public function testImportDataWithAddressAndPaymentProfileInstances()
    {
        $subscriptionId = 111;
        $addressId = 123;
        $profileId = 131;
        $addressMock = $this->createAddressMock();
        $addressMock->expects($this->once())->method('getId')->willReturn($addressId);
        $profileMock = $this->createProfileMock();
        $profileMock->expects($this->once())->method('getId')->willReturn($profileId);
        $data = [
            SubscriptionInterface::ID => $subscriptionId,
            SubscriptionInterface::SHIPPING_ADDRESS => $addressMock,
            SubscriptionInterface::PAYMENT_PROFILE => $profileMock,
        ];
        
        $this->subscription->importData($data);
        $this->assertEquals($subscriptionId, $this->subscription->getId());
        $this->assertEquals($addressId, $this->subscription->getShippingAddressId());
        $this->assertEquals($profileId, $this->subscription->getPaymentProfileId());
        $this->assertSame($profileMock, $this->subscription->getPaymentProfile());
        $this->assertSame($addressMock, $this->subscription->getShippingAddress());
    }

    public function testToArray()
    {
        $addressData = [
            AddressInterface::CITY => 'city',
            AddressInterface::COMPANY => 'company',
        ];
        $paymentProfileData = [PaymentProfileInterface::CREDITCARD_YEAR => 2016];
        $expectedData = [
            SubscriptionInterface::ID => 111,
            SubscriptionInterface::SHIPPING_ADDRESS => $addressData,
            SubscriptionInterface::PAYMENT_PROFILE => $paymentProfileData,
            SubscriptionInterface::SHIPPING_ADDRESS_ID => null,
            SubscriptionInterface::PAYMENT_PROFILE_ID => null,
        ];

        $this->paymentProfileMock->expects($this->once())
            ->method('toArray')
            ->willReturn($paymentProfileData);
        $this->shippingAddressMock->expects($this->once())
            ->method('toArray')
            ->willReturn($addressData);
        
        $this->subscription->setId(111);
        $this->assertEquals($expectedData, $this->subscription->toArray());
    }

    /**
     * @param array $data
     * @param array $addressData
     * @param array $addressAsChildFormData
     * @param array $expectedData
     * @param bool $isNew
     * @dataProvider getFormDataProvider
     */
    public function testGetFormData($data, $addressData, $addressAsChildFormData, $expectedData, $isNew)
    {
        $this->paymentProfileMock->expects($this->once())
            ->method('importData')
            ->with([])
            ->willReturnSelf();

        $this->shippingAddressMock->expects($this->once())
            ->method('importData')
            ->with($addressData)
            ->willReturnSelf();
        $this->shippingAddressMock->expects($this->any())->method('getId')->willReturn(null);
        $this->shippingAddressMock->expects($this->once())
            ->method('getAsChildFormData')
            ->with($isNew)
            ->willReturn($addressAsChildFormData);
        
        $this->subscription->importData($data);
        $this->assertEquals($expectedData, $this->subscription->getFormData());
    }

    /**
     * @return array
     */
    public function getFormDataProvider()
    {
        return [
            'New subscription with address' => [
                'data' => [
                    SubscriptionInterface::CUSTOMER_ID => '123',
                    SubscriptionInterface::PRODUCT_SKU => 'sku',
                    SubscriptionInterface::SUBSCRIPTION_PRODUCTS => [],
                    SubscriptionInterface::QTY => 123,
                    SubscriptionInterface::USE_FIXED_PRICE => false,
                    SubscriptionInterface::FIXED_PRICE => 222,
                    SubscriptionInterface::INTERVAL => 'monthly',
                    SubscriptionInterface::MAGENTO_STORE_CODE => 'code',
                    SubscriptionInterface::PAYMENT_PROFILE_ID => '333',
                    SubscriptionInterface::PAYMENT_PROFILE => [],
                    SubscriptionInterface::AUTHORIZE_NET_PAYMENT_PROFILE_ID => '313',
                    SubscriptionInterface::CREDITCARD_LAST_DIGITS => '311',
                    SubscriptionInterface::MAGENTO_BILLING_ADDRESS_ID => '5242',
                    SubscriptionInterface::REQUIRES_SHIPPING => true,
                    SubscriptionInterface::SHIPPING_ADDRESS_ID => null,
                    SubscriptionInterface::MAGENTO_SHIPPING_ADDRESS_ID => '123',
                    SubscriptionInterface::MAGENTO_SHIPPING_METHOD_CODE => 'tablerate',
                    SubscriptionInterface::SEND_CUSTOMER_NOTIFICATION_EMAIL => true,
                    SubscriptionInterface::FIRST_ORDER_ALREADY_CREATED => true,
                    SubscriptionInterface::STATUS => 'success',
                    SubscriptionInterface::COUPON_CODE => 'code',
                    SubscriptionInterface::USER_DEFINED_FIELDS => [],
                    SubscriptionInterface::RECURRING_ORDER_COUNT => 123,
                    SubscriptionInterface::LAST_ORDER_DATE => '2016-11-12',
                    SubscriptionInterface::NEXT_ORDER_DATE => '2016-12-11',
                    SubscriptionInterface::EXPIRATION_DATE => '2016-11-11',
                    SubscriptionInterface::RETRY_AFTER => '2017-11-11',
                    SubscriptionInterface::CREATED => '2016-12-12',
                    SubscriptionInterface::UPDATED => '2016-12-12',
                    SubscriptionInterface::CANCELLED => '2016-10-12',
                    SubscriptionInterface::ERROR_TIME => '2014-12-12',
                    SubscriptionInterface::ERROR_CLASS => 'class',
                    SubscriptionInterface::ERROR_CLASS_DESCRIPTION => 'description',
                    SubscriptionInterface::ERROR_TYPE => 'type',
                    SubscriptionInterface::ERROR_MESSAGE => 'message',
                    SubscriptionInterface::FAILED_ORDER_ATTEMPT_COUNT => '2',
                    SubscriptionInterface::SHIPPING_ADDRESS => ['key' => 'value', 'key2' => 'value2']
                ],
                'addressData' => ['key' => 'value', 'key2' => 'value2'],
                'addressAsChildFormData' => ['key' => 'value'],
                'expectedData' => [
                    SubscriptionInterface::CUSTOMER_ID => '123',
                    SubscriptionInterface::PRODUCT_SKU => 'sku',
                    SubscriptionInterface::QTY => 123,
                    SubscriptionInterface::USE_FIXED_PRICE => false,
                    SubscriptionInterface::FIXED_PRICE => 222,
                    SubscriptionInterface::INTERVAL => 'monthly',
                    SubscriptionInterface::MAGENTO_STORE_CODE => 'code',
                    SubscriptionInterface::PAYMENT_PROFILE_ID => '333',
                    SubscriptionInterface::REQUIRES_SHIPPING => true,
                    SubscriptionInterface::MAGENTO_SHIPPING_METHOD_CODE => 'tablerate',
                    SubscriptionInterface::SEND_CUSTOMER_NOTIFICATION_EMAIL => true,
                    SubscriptionInterface::FIRST_ORDER_ALREADY_CREATED => true,
                    SubscriptionInterface::COUPON_CODE => 'code',
                    SubscriptionInterface::USER_DEFINED_FIELDS => [],
                    SubscriptionInterface::EXPIRATION_DATE => '2016-11-11',
                    SubscriptionInterface::NEXT_ORDER_DATE => '2016-12-11',
                    SubscriptionInterface::SHIPPING_ADDRESS => ['key' => 'value']
                ],
                'isNew' => true
            ],
            'New subscription with shipping address ID' => [
                'data' => [
                    SubscriptionInterface::CUSTOMER_ID => '123',
                    SubscriptionInterface::PRODUCT_SKU => 'sku',
                    SubscriptionInterface::SUBSCRIPTION_PRODUCTS => [],
                    SubscriptionInterface::QTY => 123,
                    SubscriptionInterface::USE_FIXED_PRICE => false,
                    SubscriptionInterface::FIXED_PRICE => 222,
                    SubscriptionInterface::INTERVAL => 'monthly',
                    SubscriptionInterface::MAGENTO_STORE_CODE => 'code',
                    SubscriptionInterface::PAYMENT_PROFILE_ID => '333',
                    SubscriptionInterface::PAYMENT_PROFILE => [],
                    SubscriptionInterface::AUTHORIZE_NET_PAYMENT_PROFILE_ID => '313',
                    SubscriptionInterface::CREDITCARD_LAST_DIGITS => '311',
                    SubscriptionInterface::MAGENTO_BILLING_ADDRESS_ID => '5242',
                    SubscriptionInterface::SHIPPING_ADDRESS_ID => null,
                    SubscriptionInterface::REQUIRES_SHIPPING => true,
                    SubscriptionInterface::MAGENTO_SHIPPING_ADDRESS_ID => '123',
                    SubscriptionInterface::MAGENTO_SHIPPING_METHOD_CODE => 'tablerate',
                    SubscriptionInterface::SEND_CUSTOMER_NOTIFICATION_EMAIL => true,
                    SubscriptionInterface::FIRST_ORDER_ALREADY_CREATED => true,
                    SubscriptionInterface::STATUS => 'success',
                    SubscriptionInterface::COUPON_CODE => 'code',
                    SubscriptionInterface::USER_DEFINED_FIELDS => [],
                    SubscriptionInterface::RECURRING_ORDER_COUNT => 123,
                    SubscriptionInterface::LAST_ORDER_DATE => '2016-11-12',
                    SubscriptionInterface::NEXT_ORDER_DATE => '2016-12-11',
                    SubscriptionInterface::EXPIRATION_DATE => '2016-11-11',
                    SubscriptionInterface::RETRY_AFTER => '2017-11-11',
                    SubscriptionInterface::CREATED => '2016-12-12',
                    SubscriptionInterface::UPDATED => '2016-12-12',
                    SubscriptionInterface::CANCELLED => '2016-10-12',
                    SubscriptionInterface::ERROR_TIME => '2014-12-12',
                    SubscriptionInterface::ERROR_CLASS => 'class',
                    SubscriptionInterface::ERROR_CLASS_DESCRIPTION => 'description',
                    SubscriptionInterface::ERROR_TYPE => 'type',
                    SubscriptionInterface::ERROR_MESSAGE => 'message',
                    SubscriptionInterface::FAILED_ORDER_ATTEMPT_COUNT => '2',
                    SubscriptionInterface::SHIPPING_ADDRESS_ID => 242,
                    SubscriptionInterface::SHIPPING_ADDRESS => ['key' => 'value', 'key2' => 'value2']
                ],
                'addressData' => ['key' => 'value', 'key2' => 'value2'],
                'addressAsChildFormData' => ['key' => 'value'],
                'expectedData' => [
                    SubscriptionInterface::CUSTOMER_ID => '123',
                    SubscriptionInterface::PRODUCT_SKU => 'sku',
                    SubscriptionInterface::QTY => 123,
                    SubscriptionInterface::USE_FIXED_PRICE => false,
                    SubscriptionInterface::FIXED_PRICE => 222,
                    SubscriptionInterface::INTERVAL => 'monthly',
                    SubscriptionInterface::MAGENTO_STORE_CODE => 'code',
                    SubscriptionInterface::PAYMENT_PROFILE_ID => '333',
                    SubscriptionInterface::REQUIRES_SHIPPING => true,
                    SubscriptionInterface::MAGENTO_SHIPPING_METHOD_CODE => 'tablerate',
                    SubscriptionInterface::SEND_CUSTOMER_NOTIFICATION_EMAIL => true,
                    SubscriptionInterface::FIRST_ORDER_ALREADY_CREATED => true,
                    SubscriptionInterface::COUPON_CODE => 'code',
                    SubscriptionInterface::USER_DEFINED_FIELDS => [],
                    SubscriptionInterface::EXPIRATION_DATE => '2016-11-11',
                    SubscriptionInterface::NEXT_ORDER_DATE => '2016-12-11',
                    SubscriptionInterface::SHIPPING_ADDRESS_ID => 242,
                ],
                'isNew' => true
            ],
            'Not new subscription with shipping address' => [
                'data' => [
                    SubscriptionInterface::ID => '151',
                    SubscriptionInterface::CUSTOMER_ID => '123',
                    SubscriptionInterface::PRODUCT_SKU => 'sku',
                    SubscriptionInterface::SUBSCRIPTION_PRODUCTS => [],
                    SubscriptionInterface::QTY => 123,
                    SubscriptionInterface::USE_FIXED_PRICE => false,
                    SubscriptionInterface::FIXED_PRICE => 222,
                    SubscriptionInterface::INTERVAL => 'monthly',
                    SubscriptionInterface::MAGENTO_STORE_CODE => 'code',
                    SubscriptionInterface::PAYMENT_PROFILE_ID => '333',
                    SubscriptionInterface::PAYMENT_PROFILE => [],
                    SubscriptionInterface::AUTHORIZE_NET_PAYMENT_PROFILE_ID => '313',
                    SubscriptionInterface::CREDITCARD_LAST_DIGITS => '311',
                    SubscriptionInterface::MAGENTO_BILLING_ADDRESS_ID => '5242',
                    SubscriptionInterface::SHIPPING_ADDRESS_ID => null,
                    SubscriptionInterface::REQUIRES_SHIPPING => true,
                    SubscriptionInterface::MAGENTO_SHIPPING_ADDRESS_ID => '123',
                    SubscriptionInterface::MAGENTO_SHIPPING_METHOD_CODE => 'tablerate',
                    SubscriptionInterface::SEND_CUSTOMER_NOTIFICATION_EMAIL => true,
                    SubscriptionInterface::FIRST_ORDER_ALREADY_CREATED => true,
                    SubscriptionInterface::STATUS => 'success',
                    SubscriptionInterface::COUPON_CODE => 'code',
                    SubscriptionInterface::USER_DEFINED_FIELDS => [],
                    SubscriptionInterface::RECURRING_ORDER_COUNT => 123,
                    SubscriptionInterface::LAST_ORDER_DATE => '2016-11-12',
                    SubscriptionInterface::NEXT_ORDER_DATE => '2016-12-11',
                    SubscriptionInterface::EXPIRATION_DATE => '2016-11-11',
                    SubscriptionInterface::RETRY_AFTER => '2017-11-11',
                    SubscriptionInterface::CREATED => '2016-12-12',
                    SubscriptionInterface::UPDATED => '2016-12-12',
                    SubscriptionInterface::CANCELLED => '2016-10-12',
                    SubscriptionInterface::ERROR_TIME => '2014-12-12',
                    SubscriptionInterface::ERROR_CLASS => 'class',
                    SubscriptionInterface::ERROR_CLASS_DESCRIPTION => 'description',
                    SubscriptionInterface::ERROR_TYPE => 'type',
                    SubscriptionInterface::ERROR_MESSAGE => 'message',
                    SubscriptionInterface::FAILED_ORDER_ATTEMPT_COUNT => '2',
                    SubscriptionInterface::SHIPPING_ADDRESS => ['key' => 'value', 'key2' => 'value2']
                ],
                'addressData' => ['key' => 'value', 'key2' => 'value2'],
                'addressAsChildFormData' => ['key' => 'value'],
                'expectedData' => [
                    SubscriptionInterface::PRODUCT_SKU => 'sku',
                    SubscriptionInterface::QTY => 123,
                    SubscriptionInterface::USE_FIXED_PRICE => false,
                    SubscriptionInterface::FIXED_PRICE => 222,
                    SubscriptionInterface::INTERVAL => 'monthly',
                    SubscriptionInterface::MAGENTO_STORE_CODE => 'code',
                    SubscriptionInterface::PAYMENT_PROFILE_ID => '333',
                    SubscriptionInterface::REQUIRES_SHIPPING => true,
                    SubscriptionInterface::MAGENTO_SHIPPING_METHOD_CODE => 'tablerate',
                    SubscriptionInterface::SEND_CUSTOMER_NOTIFICATION_EMAIL => true,
                    SubscriptionInterface::COUPON_CODE => 'code',
                    SubscriptionInterface::USER_DEFINED_FIELDS => [],
                    SubscriptionInterface::EXPIRATION_DATE => '2016-11-11',
                    SubscriptionInterface::NEXT_ORDER_DATE => '2016-12-11',
                    SubscriptionInterface::SHIPPING_ADDRESS => ['key' => 'value']
                ],
                'isNew' => false
            ],
        ];
    }

    /**
     * @param string $date
     * @param string $format
     * @param string $result
     * @dataProvider dateFormatDataProvider
     */
    public function testDateFormat($date, $format, $result)
    {
        $subscription = new Subscription([
            SubscriptionInterface::NEXT_ORDER_DATE => $date,
            SubscriptionInterface::LAST_ORDER_DATE => $date,
            SubscriptionInterface::EXPIRATION_DATE => $date,
            SubscriptionInterface::SHIPPING_ADDRESS => $this->shippingAddressMock,
            SubscriptionInterface::PAYMENT_PROFILE => $this->paymentProfileMock
        ]);

        $this->assertEquals($result, $subscription->getLastOrderDate($format));
        $this->assertEquals($result, $subscription->getNextOrderDate($format));
        $this->assertEquals($result, $subscription->getExpirationDate($format));

    }

    /**
     * @return array
     */
    public function dateFormatDataProvider()
    {
        return [
            ['2012-01-01', 'Y m d', '2012 01 01'],
            ['2016-01-01', 'Y', '2016'],
            ['2016-01-01', 'M d Y', 'Jan 01 2016']
        ];
    }

    /**
     * @param string $date
     * @param string $format
     * @param string $result
     * @dataProvider datetimeFormatDataProvider
     */
    public function testDatetimeFormat($date, $format, $result)
    {
        $subscription = new Subscription([
            SubscriptionInterface::CREATED => $date,
            SubscriptionInterface::UPDATED => $date,
            SubscriptionInterface::CANCELLED => $date,
            SubscriptionInterface::RETRY_AFTER => $date,
            SubscriptionInterface::ERROR_TIME => $date,
            SubscriptionInterface::SHIPPING_ADDRESS => $this->shippingAddressMock,
            SubscriptionInterface::PAYMENT_PROFILE => $this->paymentProfileMock
        ]);

        $this->assertEquals($result, $subscription->getCreated($format));
        $this->assertEquals($result, $subscription->getUpdated($format));
        $this->assertEquals($result, $subscription->getCancelled($format));
        $this->assertEquals($result, $subscription->getRetryAfter($format));
        $this->assertEquals($result, $subscription->getErrorTime($format));
    }

    /**
     * @return array
     */
    public function datetimeFormatDataProvider()
    {
        return [
            ['2016-01-10T09:03:00+0000', 'Y d m', '2016 10 01'],
            ['2020-12-31T09:03:00+0000', 'Y', '2020'],
            ['2018-05-10T09:03:00+0000', 'M d Y i:s', 'May 10 2018 03:00']
        ];
    }

    /**
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createProfileMock()
    {
        return $this->getMockBuilder('SubscribePro\Service\PaymentProfile\PaymentProfileInterface')->getMock();
    }

    /**
     * @return \SubscribePro\Service\Address\AddressInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createAddressMock()
    {
        return $this->getMockBuilder('SubscribePro\Service\Address\AddressInterface')->getMock();
    }
}
