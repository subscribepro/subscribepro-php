<?php

namespace SubscribePro\Tests\Service\Token;

use SubscribePro\Service\Token\Token;
use SubscribePro\Service\Token\TokenInterface;
use SubscribePro\Service\Address\AddressInterface;

class TokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Token\TokenInterface
     */
    protected $token;

     /**
     * @var \SubscribePro\Service\Address\Address|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $billingAddressMock;

    protected function setUp()
    {
        $this->billingAddressMock = $this->getMockBuilder('SubscribePro\Service\Address\Address')
            ->disableOriginalConstructor()
            ->getMock();

        $this->token = new Token([
            TokenInterface::BILLING_ADDRESS => $this->billingAddressMock
        ]);
    }

    /**
     * @param array $data
     * @param bool $isValid
     * @dataProvider isValidDataProvider
     */
    public function testIsValid($data, $isValid, $billingData, $billingDataIsValid)
    {
        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('importData')
            ->with($billingData)
            ->willReturnSelf();

        if (null === $billingDataIsValid) {
            $this->billingAddressMock->expects($this->never())->method('isAsChildValid');
        } else {
            $this->billingAddressMock->expects($this->atLeastOnce())
                ->method('isAsChildValid')
                ->willReturn($billingDataIsValid);
        }

        $this->token->importData($data);
        $this->assertEquals($isValid, $this->token->isValid());
    }

    /**
     * @return array
     */
    public function isValidDataProvider()
    {
        return [
            'Not valid: empty data' => [
                'data' => [],
                'isValid' => false,
                'billingData' => [],
                'billingDataIsValid' => null,
            ],
            'Not valid: without month' => [
                'data' => [
                    TokenInterface::CREDITCARD_NUMBER => 4111111111111111,
                    TokenInterface::CREDITCARD_YEAR => 2024,
                    TokenInterface::BILLING_ADDRESS => [
                        AddressInterface::FIRST_NAME => 'name',
                        AddressInterface::LAST_NAME => 'surname',
                        AddressInterface::STREET1 => 'address',
                        AddressInterface::CITY => 'city',
                        AddressInterface::REGION => 'MD',
                        AddressInterface::POSTCODE => '11111',
                        AddressInterface::COUNTRY => 'country',
                        AddressInterface::PHONE => 'phone',
                    ]
                ],
                'isValid' => false,
                'billingData' => [
                    AddressInterface::FIRST_NAME => 'name',
                    AddressInterface::LAST_NAME => 'surname',
                    AddressInterface::STREET1 => 'address',
                    AddressInterface::CITY => 'city',
                    AddressInterface::REGION => 'MD',
                    AddressInterface::POSTCODE => '11111',
                    AddressInterface::COUNTRY => 'country',
                    AddressInterface::PHONE => 'phone',
                ],
                'billingDataIsValid' => null,
            ],
            'Not valid: without address' => [
                'data' => [
                    TokenInterface::CREDITCARD_NUMBER => 4111111111111111,
                    TokenInterface::CREDITCARD_MONTH => 04,
                    TokenInterface::CREDITCARD_YEAR => 2024,
                ],
                'isValid' => false,
                'billingData' => [],
                'billingDataIsValid' => false
            ],
            'Not valid: without number' => [
                'data' => [
                    TokenInterface::CREDITCARD_MONTH => 04,
                    TokenInterface::CREDITCARD_YEAR => 2024,
                    TokenInterface::BILLING_ADDRESS => [
                        AddressInterface::FIRST_NAME => 'name',
                        AddressInterface::LAST_NAME => 'surname',
                        AddressInterface::STREET1 => 'address',
                        AddressInterface::CITY => 'city',
                        AddressInterface::REGION => 'MD',
                        AddressInterface::POSTCODE => '11111',
                        AddressInterface::COUNTRY => 'country',
                        AddressInterface::PHONE => 'phone',
                    ]
                ],
                'isValid' => false,
                'billingData' => [
                    AddressInterface::FIRST_NAME => 'name',
                    AddressInterface::LAST_NAME => 'surname',
                    AddressInterface::STREET1 => 'address',
                    AddressInterface::CITY => 'city',
                    AddressInterface::REGION => 'MD',
                    AddressInterface::POSTCODE => '11111',
                    AddressInterface::COUNTRY => 'country',
                    AddressInterface::PHONE => 'phone',
                ],
                'billingDataIsValid' => null
            ],
            'Not valid: without first name' => [
                'data' => [
                    TokenInterface::CREDITCARD_NUMBER => 11,
                    TokenInterface::CREDITCARD_MONTH => 04,
                    TokenInterface::CREDITCARD_YEAR => 2024,
                    TokenInterface::BILLING_ADDRESS => [
                        AddressInterface::LAST_NAME => 'surname',
                        AddressInterface::STREET1 => 'address',
                        AddressInterface::CITY => 'city',
                        AddressInterface::REGION => 'MD',
                        AddressInterface::POSTCODE => '11111',
                        AddressInterface::COUNTRY => 'country',
                        AddressInterface::PHONE => 'phone',
                    ]
                ],
                'isValid' => false,
                'billingData' => [
                    AddressInterface::LAST_NAME => 'surname',
                    AddressInterface::STREET1 => 'address',
                    AddressInterface::CITY => 'city',
                    AddressInterface::REGION => 'MD',
                    AddressInterface::POSTCODE => '11111',
                    AddressInterface::COUNTRY => 'country',
                    AddressInterface::PHONE => 'phone',
                ],
                'billingDataIsValid' => false
            ],
            'Valid' => [
                'data' => [
                    TokenInterface::CREDITCARD_NUMBER => 11,
                    TokenInterface::CREDITCARD_MONTH => 04,
                    TokenInterface::CREDITCARD_YEAR => 2024,
                    TokenInterface::BILLING_ADDRESS => [
                        AddressInterface::FIRST_NAME => 'name',
                        AddressInterface::LAST_NAME => 'surname',
                        AddressInterface::STREET1 => 'address',
                        AddressInterface::CITY => 'city',
                        AddressInterface::REGION => 'MD',
                        AddressInterface::POSTCODE => '11111',
                        AddressInterface::COUNTRY => 'country',
                        AddressInterface::PHONE => 'phone',
                    ]
                ],
                'isValid' => true,
                'billingData' => [
                    AddressInterface::FIRST_NAME => 'name',
                    AddressInterface::LAST_NAME => 'surname',
                    AddressInterface::STREET1 => 'address',
                    AddressInterface::CITY => 'city',
                    AddressInterface::REGION => 'MD',
                    AddressInterface::POSTCODE => '11111',
                    AddressInterface::COUNTRY => 'country',
                    AddressInterface::PHONE => 'phone',
                ],
                'billingDataIsValid' => true
            ],
        ];
    }
}
