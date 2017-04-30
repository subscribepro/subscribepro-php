<?php

namespace SubscribePro\Tests\Service\Customer;

use SubscribePro\Service\Customer\CustomerInterface;
use SubscribePro\Service\Customer\CustomerService;

class CustomerServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Customer\CustomerService
     */
    protected $customerService;

    /**
     * @var \SubscribePro\Service\DataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $customerFactoryMock;

    /**
     * @var \SubscribePro\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClientMock;

    protected function setUp()
    {
        $this->httpClientMock = $this->getMockBuilder('SubscribePro\Http')
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerFactoryMock = $this->getMockBuilder('SubscribePro\Service\DataFactoryInterface')->getMock();

        $this->customerService = new CustomerService($this->httpClientMock, $this->customerFactoryMock);
    }

    public function testCreateCustomer()
    {
        $customerMock = $this->createCustomerMock();
        $customerData = [
            CustomerInterface::EMAIL => 'email@example.com',
            CustomerInterface::ID => 123,
        ];

        $this->customerFactoryMock->expects($this->once())
            ->method('create')
            ->with($customerData)
            ->willReturn($customerMock);

        $this->assertSame($customerMock, $this->customerService->createCustomer($customerData));
    }

    /**
     * @param string $url
     * @param string $itemId
     * @param bool $isNew
     * @param array $formData
     * @param array $resultData
     * @dataProvider saveCustomerDataProvider
     */
    public function testSaveCustomer($url, $itemId, $isNew, $formData, $resultData)
    {
        $customerMock = $this->createCustomerMock();
        $customerMock->expects($this->once())->method('isNew')->willReturn($isNew);
        $customerMock->expects($this->once())->method('getFormData')->willReturn($formData);
        $customerMock->expects($this->any())->method('getId')->willReturn($itemId);
        $customerMock->expects($this->once())
            ->method('importData')
            ->with($resultData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [CustomerService::API_NAME_CUSTOMER => $formData])
            ->willReturn([CustomerService::API_NAME_CUSTOMER => $resultData]);

        $this->assertSame($customerMock, $this->customerService->saveCustomer($customerMock));
    }

    /**
     * @return array
     */
    public function saveCustomerDataProvider()
    {
        return [
            'Save new customer' => [
                'url' => '/services/v2/customer.json',
                'itemId' => null,
                'isNew' => true,
                'formData' => [CustomerInterface::EMAIL => 'email@example.com'],
                'resultData' => [CustomerInterface::ID => 11],
            ],
            'Update existing customer' => [
                'url' => '/services/v2/customers/22.json',
                'itemId' => 22,
                'isNew' => false,
                'formData' => [CustomerInterface::EMAIL => 'email@example.com'],
                'resultData' => [CustomerInterface::ID => 22],
            ],
        ];
    }

    public function testLoadCustomer()
    {
        $itemId = 111;
        $itemData = [
            CustomerInterface::ID => $itemId,
            CustomerInterface::EMAIL => 'email@example.com',
        ];
        $customerMock = $this->createCustomerMock();

        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with("/services/v2/customers/{$itemId}.json")
            ->willReturn([CustomerService::API_NAME_CUSTOMER => $itemData]);

        $this->customerFactoryMock->expects($this->once())
            ->method('create')
            ->with($itemData)
            ->willReturn($customerMock);

        $this->assertSame($customerMock, $this->customerService->loadCustomer($itemId));
    }

    /**
     * @expectedException \SubscribePro\Exception\InvalidArgumentException
     * @expectedExceptionMessageRegExp /Only \[[a-z,_ ]+\] query filters are allowed./
     */
    public function testFailToLoadCustomersIfFilterIsNotValid()
    {
        $filters = ['invalid_key' => 'value'];

        $this->httpClientMock->expects($this->never())->method('get');

        $this->customerService->loadCustomers($filters);
    }

    /**
     * @param array $filters
     * @param array $itemsData
     * @dataProvider loadCustomersDataProvider
     */
    public function testLoadCustomers($filters, $itemsData)
    {
        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with('/services/v2/customers.json', $filters)
            ->willReturn([CustomerService::API_NAME_CUSTOMERS => $itemsData]);

        $customers = [];
        $customerFactoryMap = [];
        foreach ($itemsData as $itemData) {
            $customer = $this->createCustomerMock();
            $customerFactoryMap[] = [$itemData, $customer];
            $customers[] = $customer;
        }
        $this->customerFactoryMock->expects($this->exactly(count($itemsData)))
            ->method('create')
            ->willReturnMap($customerFactoryMap);

        $this->assertSame($customers, $this->customerService->loadCustomers($filters));
    }

    /**
     * @return array
     */
    public function loadCustomersDataProvider()
    {
        return [
            'Loading without filter' => [
                'filters' => [],
                'itemsData' => [[CustomerInterface::ID => 111], [CustomerInterface::ID => 222]],
            ],
            'Loading by first name' => [
                'filters' => [CustomerInterface::FIRST_NAME => 'John'],
                'itemsData' => [[CustomerInterface::ID => 333, CustomerInterface::FIRST_NAME => 'John']],
            ],
        ];
    }

    /**
     * @return \SubscribePro\Service\Customer\CustomerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createCustomerMock()
    {
        return $this->getMockBuilder('SubscribePro\Service\Customer\CustomerInterface')->getMock();
    }
}
