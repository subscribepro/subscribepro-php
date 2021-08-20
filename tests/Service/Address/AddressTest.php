<?php

namespace SubscribePro\Tests\Service\Address;

use SubscribePro\Service\Address\Address;
use SubscribePro\Service\Address\AddressInterface;

class AddressTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Address\AddressInterface
     */
    protected $address;

    protected function setUp()
    {
        $this->address = new Address();
    }

    /**
     * @param array $data
     * @param array $expectedData
     * @dataProvider getFormDataProvider
     */
    public function testGetFormData($data, $expectedData)
    {
        $this->address->importData($data);
        $this->assertEquals($expectedData, $this->address->getFormData());
    }

    /**
     * @return array
     */
    public function getFormDataProvider()
    {
        return [
            'New address' => [
                'data' => [
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
                    AddressInterface::STREET3 => 'street3',
                ],
                'expectedData' => [
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
                    AddressInterface::STREET3 => 'street3',
                ],
            ],
            'Not new address' => [
                'data' => [
                    AddressInterface::ID => 111,
                    AddressInterface::CITY => 'city',
                    AddressInterface::COUNTRY => 'country',
                    AddressInterface::COMPANY => 'company',
                    AddressInterface::FIRST_NAME => 'first name',
                    AddressInterface::LAST_NAME => 'last name',
                    AddressInterface::MIDDLE_NAME => 'middle name',
                    AddressInterface::CUSTOMER_ID => '111',
                    AddressInterface::MAGENTO_ADDRESS_ID => '23',
                    AddressInterface::PHONE => 'phone',
                    AddressInterface::POSTCODE => 'postcode',
                    AddressInterface::REGION => 'region',
                    AddressInterface::CREATED => '2016-12-12',
                    AddressInterface::STREET1 => 'street1',
                    AddressInterface::STREET2 => 'street2',
                    AddressInterface::STREET3 => 'street3',
                ],
                'expectedData' => [
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
                    AddressInterface::STREET3 => 'street3',
                ],
            ],
        ];
    }

    /**
     * @param array $data
     * @dataProvider getAsChildFormDataProvider
     */
    public function testGetAsChildFormData($data)
    {
        $expectedData = [
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
            AddressInterface::STREET3 => 'street3',
        ];

        $this->address->importData($data);
        $this->assertEquals($expectedData, $this->address->getFormData());
    }

    /**
     * @return array
     */
    public function getAsChildFormDataProvider()
    {
        return [
            'New address' => [
                'data' => [
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
                    AddressInterface::STREET3 => 'street3',
                ],
            ],
            'Not new address' => [
                'data' => [
                    AddressInterface::ID => 111,
                    AddressInterface::CITY => 'city',
                    AddressInterface::COUNTRY => 'country',
                    AddressInterface::COMPANY => 'company',
                    AddressInterface::FIRST_NAME => 'first name',
                    AddressInterface::LAST_NAME => 'last name',
                    AddressInterface::MIDDLE_NAME => 'middle name',
                    AddressInterface::CUSTOMER_ID => '111',
                    AddressInterface::MAGENTO_ADDRESS_ID => '23',
                    AddressInterface::PHONE => 'phone',
                    AddressInterface::POSTCODE => 'postcode',
                    AddressInterface::REGION => 'region',
                    AddressInterface::CREATED => '2016-12-12',
                    AddressInterface::STREET1 => 'street1',
                    AddressInterface::STREET2 => 'street2',
                    AddressInterface::STREET3 => 'street3',
                ],
            ],
        ];
    }
}
