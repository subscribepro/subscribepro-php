<?php

namespace SubscribePro\Tests\Service\Address;

use SubscribePro\Service\Address\AddressInterface;
use SubscribePro\Service\Address\AddressService;

class AddressServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Address\AddressService
     */
    protected $addressService;

    /**
     * @var \SubscribePro\Service\DataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $addressFactoryMock;

    /**
     * @var \SubscribePro\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClientMock;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->getMockBuilder('SubscribePro\Http')
            ->disableOriginalConstructor()
            ->getMock();

        $this->addressFactoryMock = $this->getMockBuilder('SubscribePro\Service\DataFactoryInterface')->getMock();

        $this->addressService = new AddressService($this->httpClientMock, $this->addressFactoryMock);
    }

    public function testCreateAddress()
    {
        $addressData = [
            AddressInterface::CITY => 'city',
            AddressInterface::ID => 555,
        ];
        $addressMock = $this->createAddressMock();

        $this->addressFactoryMock->expects($this->once())
            ->method('create')
            ->with($addressData)
            ->willReturn($addressMock);

        $this->assertSame($addressMock, $this->addressService->createAddress($addressData));
    }

    /**
     * @param string $url
     * @param string $itemId
     * @param bool   $isNew
     * @param array  $formData
     * @param array  $resultData
     * @dataProvider saveAddressDataProvider
     */
    public function testSaveAddress($url, $itemId, $isNew, $formData, $resultData)
    {
        $addressMock = $this->createAddressMock();
        $addressMock->expects($this->once())->method('isNew')->willReturn($isNew);
        $addressMock->expects($this->once())->method('getFormData')->willReturn($formData);
        $addressMock->expects($this->any())->method('getId')->willReturn($itemId);
        $addressMock->expects($this->once())
            ->method('importData')
            ->with($resultData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [AddressService::API_NAME_ADDRESS => $formData])
            ->willReturn([AddressService::API_NAME_ADDRESS => $resultData]);

        $this->assertSame($addressMock, $this->addressService->saveAddress($addressMock));
    }

    /**
     * @return array
     */
    public function saveAddressDataProvider()
    {
        return [
            'Save new address' => [
                'url' => '/services/v2/address.json',
                'itemId' => null,
                'isNew' => true,
                'formData' => [AddressInterface::CITY => 'city one'],
                'resultData' => [AddressInterface::ID => 12],
            ],
            'Update existing address' => [
                'url' => '/services/v2/addresses/11.json',
                'itemId' => 11,
                'isNew' => false,
                'formData' => [AddressInterface::CITY => 'city two'],
                'resultData' => [AddressInterface::ID => 11],
            ],
        ];
    }

    public function testFindOrSaveAddress()
    {
        $url = '/services/v2/address/find-or-create.json';
        $formData = [AddressInterface::CITY => 'city'];
        $expectedImportData = [AddressInterface::ID => '111'];

        $addressMock = $this->createAddressMock();
        $addressMock->expects($this->once())->method('getFormData')->willReturn($formData);
        $addressMock->expects($this->once())
            ->method('importData')
            ->with($expectedImportData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [AddressService::API_NAME_ADDRESS => $formData])
            ->willReturn([AddressService::API_NAME_ADDRESS => $expectedImportData]);

        $this->assertSame($addressMock, $this->addressService->findOrSave($addressMock));
    }

    public function testLoadAddress()
    {
        $itemId = 111;
        $itemData = [
            AddressInterface::ID => $itemId,
            AddressInterface::CITY => 'city',
        ];
        $addressMock = $this->createAddressMock();

        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with("/services/v2/addresses/{$itemId}.json")
            ->willReturn([AddressService::API_NAME_ADDRESS => $itemData]);

        $this->addressFactoryMock->expects($this->once())
            ->method('create')
            ->with($itemData)
            ->willReturn($addressMock);

        $this->assertSame($addressMock, $this->addressService->loadAddress($itemId));
    }

    /**
     * @param int   $customerId
     * @param array $filters
     * @param array $itemsData
     * @dataProvider loadAddressesDataProvider
     */
    public function testLoadAddresses($customerId, $filters, $itemsData)
    {
        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with('/services/v2/addresses.json', $filters)
            ->willReturn([AddressService::API_NAME_ADDRESSES => $itemsData]);

        $addresses = [];
        $addressFactoryMap = [];
        foreach ($itemsData as $itemData) {
            $address = $this->createAddressMock();
            $addressFactoryMap[] = [$itemData, $address];
            $addresses[] = $address;
        }
        $this->addressFactoryMock->expects($this->exactly(count($itemsData)))
            ->method('create')
            ->willReturnMap($addressFactoryMap);

        $this->assertSame($addresses, $this->addressService->loadAddresses($customerId));
    }

    /**
     * @return array
     */
    public function loadAddressesDataProvider()
    {
        return [
            'Loading without filter' => [
                'customerId' => null,
                'filters' => [],
                'itemsData' => [[AddressInterface::ID => 111], [AddressInterface::ID => 222]],
            ],
            'Loading by customer ID' => [
                'customerId' => 123,
                'filters' => [AddressInterface::CUSTOMER_ID => 123],
                'itemsData' => [[AddressInterface::CUSTOMER_ID => 123, AddressInterface::ID => 111]],
            ],
        ];
    }

    /**
     * @return \SubscribePro\Service\Address\AddressInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createAddressMock()
    {
        return $this->getMockBuilder('SubscribePro\Service\Address\AddressInterface')->getMock();
    }
}
