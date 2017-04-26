<?php

namespace SubscribePro\Tests\Service\Customer;

use SubscribePro\Service\Customer\Customer;
use SubscribePro\Service\Customer\CustomerInterface;

class CustomerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Customer\CustomerInterface
     */
    protected $customer;

    protected function setUp()
    {
        $this->customer = new Customer();
    }

    /**
     * @param array $data
     * @param array $expectedData
     * @dataProvider getFormDataProvider
     */
    public function testGetFormData($data, $expectedData)
    {
        $this->customer->importData($data);
        $this->assertEquals($expectedData, $this->customer->getFormData());
    }

    /**
     * @return array
     */
    public function getFormDataProvider()
    {
        return [
            'New address' => [
                'data' => [
                    CustomerInterface::ACTIVE_SUBSCRIBED_QTY => '123',
                    CustomerInterface::ACTIVE_SUBSCRIPTION_COUNT => '222',
                    CustomerInterface::CREATE_MAGENTO_CUSTOMER => false,
                    CustomerInterface::FIRST_NAME => 'first name',
                    CustomerInterface::LAST_NAME => 'last name',
                    CustomerInterface::MIDDLE_NAME => 'middle name',
                    CustomerInterface::MAGENTO_CUSTOMER_ID => 123,
                    CustomerInterface::MAGENTO_CUSTOMER_GROUP_ID => 321,
                    CustomerInterface::MAGENTO_WEBSITE_ID => 333,
                    CustomerInterface::EMAIL => 'email@example.com',
                    CustomerInterface::CREATED => '2016-12-12',
                    CustomerInterface::UPDATED => '2016-12-12',
                    CustomerInterface::EXTERNAL_VAULT_CUSTOMER_TOKEN => 'token',
                    CustomerInterface::SUBSCRIPTION_COUNT => '123',
                ],
                'expectedData' => [
                    CustomerInterface::FIRST_NAME => 'first name',
                    CustomerInterface::LAST_NAME => 'last name',
                    CustomerInterface::MIDDLE_NAME => 'middle name',
                    CustomerInterface::CREATE_MAGENTO_CUSTOMER => false,
                    CustomerInterface::MAGENTO_CUSTOMER_ID => 123,
                    CustomerInterface::MAGENTO_CUSTOMER_GROUP_ID => 321,
                    CustomerInterface::MAGENTO_WEBSITE_ID => 333,
                    CustomerInterface::EMAIL => 'email@example.com',
                ],
            ],
            'Not new address' => [
                'data' => [
                    CustomerInterface::ID => 444,
                    CustomerInterface::ACTIVE_SUBSCRIBED_QTY => '123',
                    CustomerInterface::ACTIVE_SUBSCRIPTION_COUNT => '222',
                    CustomerInterface::CREATE_MAGENTO_CUSTOMER => false,
                    CustomerInterface::FIRST_NAME => 'first name',
                    CustomerInterface::LAST_NAME => 'last name',
                    CustomerInterface::MIDDLE_NAME => 'middle name',
                    CustomerInterface::MAGENTO_CUSTOMER_ID => 123,
                    CustomerInterface::MAGENTO_CUSTOMER_GROUP_ID => 321,
                    CustomerInterface::MAGENTO_WEBSITE_ID => 333,
                    CustomerInterface::EMAIL => 'email@example.com',
                    CustomerInterface::CREATED => '2016-12-12',
                    CustomerInterface::UPDATED => '2016-12-12',
                    CustomerInterface::EXTERNAL_VAULT_CUSTOMER_TOKEN => 'token',
                    CustomerInterface::SUBSCRIPTION_COUNT => '123',
                ],
                'expectedData' => [
                    CustomerInterface::EXTERNAL_VAULT_CUSTOMER_TOKEN => 'token',
                    CustomerInterface::FIRST_NAME => 'first name',
                    CustomerInterface::LAST_NAME => 'last name',
                    CustomerInterface::MIDDLE_NAME => 'middle name',
                    CustomerInterface::MAGENTO_CUSTOMER_ID => 123,
                    CustomerInterface::MAGENTO_CUSTOMER_GROUP_ID => 321,
                    CustomerInterface::MAGENTO_WEBSITE_ID => 333,
                    CustomerInterface::EMAIL => 'email@example.com',
                ],
            ],
        ];
    }
}
