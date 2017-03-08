<?php

namespace SubscribePro\Tests\Service\PaymentProfile;

use SubscribePro\Service\PaymentProfile\PaymentProfileInterface;
use SubscribePro\Service\PaymentProfile\PaymentProfileService;

class PaymentProfileServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\PaymentProfile\PaymentProfileService
     */
    protected $paymentProfileService;

    /**
     * @var \SubscribePro\Service\DataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $paymentProfileFactoryMock;

    /**
     * @var \SubscribePro\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClientMock;

    protected function setUp()
    {
        $this->httpClientMock = $this->getMockBuilder('SubscribePro\Http')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentProfileFactoryMock = $this->getMockBuilder('SubscribePro\Service\DataFactoryInterface')->getMock();

        $this->paymentProfileService = new PaymentProfileService($this->httpClientMock, $this->paymentProfileFactoryMock);
    }

    public function testCreateProfile()
    {
        $paymentProfileData = [
            PaymentProfileInterface::ID => 123,
            PaymentProfileInterface::CREDITCARD_NUMBER => '4111 1111 1111 1111',
        ];
        $profileMock = $this->createProfileMock();

        $this->paymentProfileFactoryMock->expects($this->once())
            ->method('create')
            ->with($paymentProfileData)
            ->willReturn($profileMock);

        $this->assertSame($profileMock, $this->paymentProfileService->createProfile($paymentProfileData));
    }

    /**
     * @expectedException \SubscribePro\Exception\EntityInvalidDataException
     * @expectedExceptionMessage Not all required fields are set.
     */
    public function testFailToSaveProfileIfNotValid()
    {
        $paymentProfileMock = $this->createProfileMock();
        $paymentProfileMock->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())->method('post');
        $this->httpClientMock->expects($this->never())->method('put');
        
        $this->paymentProfileService->saveProfile($paymentProfileMock);
    }

    /**
     * @param string $url
     * @param string $itemId
     * @param bool $isNew
     * @param string $method
     * @param array $formData
     * @param array $resultData
     * @dataProvider saveProfileDataProvider
     */
    public function testSaveProfile($url, $itemId, $isNew, $method, $formData, $resultData)
    {
        $profileMock = $this->createProfileMock();
        $profileMock->expects($this->once())->method('isValid')->willReturn(true);
        $profileMock->expects($this->once())->method('isNew')->willReturn($isNew);
        $profileMock->expects($this->once())->method('getFormData')->willReturn($formData);
        $profileMock->expects($this->any())->method('getId')->willReturn($itemId);
        $profileMock->expects($this->once())
            ->method('importData')
            ->with($resultData)
            ->willReturn($profileMock);

        $this->httpClientMock->expects($this->once())
            ->method($method)
            ->with($url, [PaymentProfileService::API_NAME_PROFILE => $formData])
            ->willReturn([PaymentProfileService::API_NAME_PROFILE => $resultData]);

        $this->assertSame($profileMock, $this->paymentProfileService->saveProfile($profileMock));
    }

    /**
     * @return array
     */
    public function saveProfileDataProvider()
    {
        return [
            'Save new profile' => [
                'url' => '/services/v2/vault/paymentprofile/creditcard.json',
                'itemId' => null,
                'isNew' => true,
                'method' => 'post',
                'formData' => [PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '123'],
                'resultData' => [PaymentProfileInterface::ID => '111'],
            ],
            'Update existing profile' => [
                'url' => '/services/v1/vault/paymentprofiles/22.json',
                'itemId' => 22,
                'isNew' => false,
                'method' => 'put',
                'formData' => [PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '521'],
                'resultData' => [PaymentProfileInterface::ID => '22'],
            ],
        ];
    }

    public function testLoadProfile()
    {
        $itemId = 512;
        $itemData = [PaymentProfileInterface::ID => $itemId];
        $paymentProfileMock = $this->createProfileMock();

        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with("/services/v1/vault/paymentprofiles/{$itemId}.json")
            ->willReturn([PaymentProfileService::API_NAME_PROFILE => $itemData]);

        $this->paymentProfileFactoryMock->expects($this->once())
            ->method('create')
            ->with($itemData)
            ->willReturn($paymentProfileMock);

        $this->assertSame($paymentProfileMock, $this->paymentProfileService->loadProfile($itemId));
    }

    public function testLoadProfileByToken()
    {
        $token = 'my_token';
        $itemData = [PaymentProfileInterface::PAYMENT_TOKEN => $token];
        $paymentProfileMock = $this->createProfileMock();

        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with("/services/v1/vault/tokens/{$token}/paymentprofile.json")
            ->willReturn([PaymentProfileService::API_NAME_PROFILE => $itemData]);

        $this->paymentProfileFactoryMock->expects($this->once())
            ->method('create')
            ->with($itemData)
            ->willReturn($paymentProfileMock);

        $this->assertSame($paymentProfileMock, $this->paymentProfileService->loadProfileByToken($token));
    }

    public function testRedactProfile()
    {
        $itemId = 12341;
        $itemData = [PaymentProfileInterface::ID => $itemId];
        $paymentProfileMock = $this->createProfileMock();

        $this->httpClientMock->expects($this->once())
            ->method('put')
            ->with("/services/v1/vault/paymentprofiles/{$itemId}/redact.json")
            ->willReturn([PaymentProfileService::API_NAME_PROFILE => $itemData]);

        $this->paymentProfileFactoryMock->expects($this->once())
            ->method('create')
            ->with($itemData)
            ->willReturn($paymentProfileMock);

        $this->assertSame($paymentProfileMock, $this->paymentProfileService->redactProfile($itemId));
    }

    /**
     * @expectedException \SubscribePro\Exception\InvalidArgumentException
     * @expectedExceptionMessageRegExp /Only \[[a-z,_ ]+\] query filters are allowed./
     */
    public function testFailToLoadProfilesIfFilterIsNotValid()
    {
        $filters = ['invalid_key' => 'value'];

        $this->httpClientMock->expects($this->never())->method('get');
        
        $this->paymentProfileService->loadProfiles($filters);
    }

    /**
     * @param array $filters
     * @param array $itemsData
     * @dataProvider loadProfilesDataProvider
     */
    public function testLoadProfiles($filters, $itemsData)
    {
        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with('/services/v1/vault/paymentprofiles.json', $filters)
            ->willReturn([PaymentProfileService::API_NAME_PROFILES => $itemsData]);

        $profiles = [];
        $paymentProfileFactoryMap = [];
        foreach ($itemsData as $itemData) {
            $paymentProfile = $this->createProfileMock();
            $paymentProfileFactoryMap[] = [$itemData, $paymentProfile];
            $profiles[] = $paymentProfile;
        }
        $this->paymentProfileFactoryMock->expects($this->exactly(count($itemsData)))
            ->method('create')
            ->willReturnMap($paymentProfileFactoryMap);

        $this->assertSame($profiles, $this->paymentProfileService->loadProfiles($filters));
    }

    /**
     * @return array
     */
    public function loadProfilesDataProvider()
    {
        return [
            'Loading without filter' => [
                'filters' => [],
                'itemsData' => [
                    [PaymentProfileInterface::ID => 111],
                    [PaymentProfileInterface::ID => 222]
                ],
            ],
            'Loading by magento_customer_id' => [
                'filters' => [PaymentProfileInterface::MAGENTO_CUSTOMER_ID => 123],
                'itemsData' => [[
                    PaymentProfileInterface::ID => 333,
                    PaymentProfileInterface::MAGENTO_CUSTOMER_ID => 123
                ]],
            ],
        ];
    }

    /**
     * @expectedException \SubscribePro\Exception\EntityInvalidDataException
     * @expectedExceptionMessage Not all required fields are set.
     */
    public function testFailToSaveThirdPartyTokenIfProfileIsNotValid()
    {
        $paymentProfileMock = $this->createProfileMock();
        $paymentProfileMock->expects($this->once())
            ->method('isThirdPartyDataValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->paymentProfileService->saveThirdPartyToken($paymentProfileMock);
    }

    public function testSaveThirdPartyToken()
    {
        $formData = [PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '123'];
        $expectedImportData = [PaymentProfileInterface::ID => '111'];
        $url = '/services/v2/paymentprofile/third-party-token.json';

        $paymentProfileMock = $this->createProfileMock();
        $paymentProfileMock->expects($this->once())->method('isThirdPartyDataValid')->willReturn(true);
        $paymentProfileMock->expects($this->once())->method('getThirdPartyTokenFormData')->willReturn($formData);
        $paymentProfileMock->expects($this->once())
            ->method('importData')
            ->with($expectedImportData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [PaymentProfileService::API_NAME_PROFILE => $formData])
            ->willReturn([PaymentProfileService::API_NAME_PROFILE => $expectedImportData]);

        $this->assertSame(
            $paymentProfileMock,
            $this->paymentProfileService->saveThirdPartyToken($paymentProfileMock)
        );
    }

    /**
     * @expectedException \SubscribePro\Exception\EntityInvalidDataException
     * @expectedExceptionMessage Not all required fields are set.
     */
    public function testFailToSaveTokenIfProfileIsNotValid()
    {
        $token = 'token-value';
        $paymentProfileMock = $this->createProfileMock();
        $paymentProfileMock->expects($this->once())
            ->method('isTokenDataValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())
            ->method('post');

        $this->paymentProfileService->saveToken($token, $paymentProfileMock);
    }

    public function testSaveToken()
    {
        $token = 'custom_token';
        $formData = [PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '123'];
        $expectedImportData = [PaymentProfileInterface::ID => '111'];
        $url = "/services/v1/vault/tokens/{$token}/store.json";

        $paymentProfileMock = $this->createProfileMock();
        $paymentProfileMock->expects($this->once())->method('isTokenDataValid')->willReturn(true);
        $paymentProfileMock->expects($this->once())->method('getTokenFormData')->willReturn($formData);
        $paymentProfileMock->expects($this->once())
            ->method('importData')
            ->with($expectedImportData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [PaymentProfileService::API_NAME_PROFILE => $formData])
            ->willReturn([PaymentProfileService::API_NAME_PROFILE => $expectedImportData]);

        $this->assertSame(
            $paymentProfileMock,
            $this->paymentProfileService->saveToken($token, $paymentProfileMock)
        );
    }

    /**
     * @expectedException \SubscribePro\Exception\EntityInvalidDataException
     * @expectedExceptionMessage Not all required fields are set.
     */
    public function testFailToVerifyAndSaveTokenIfProfileIsNotValid()
    {
        $token = 'token';
        
        $paymentProfileMock = $this->createProfileMock();
        $paymentProfileMock->expects($this->once())
            ->method('isTokenDataValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->paymentProfileService->verifyAndSaveToken($token, $paymentProfileMock);
    }

    public function testVerifyAndSaveToken()
    {
        $token = 'test-token';
        $formData = [PaymentProfileInterface::CREDITCARD_FIRST_DIGITS => '123'];
        $expectedImportData = [PaymentProfileInterface::ID => '111'];
        $url = "/services/v1/vault/tokens/{$token}/verifyandstore.json";

        $paymentProfileMock = $this->createProfileMock();
        $paymentProfileMock->expects($this->once())->method('isTokenDataValid')->willReturn(true);
        $paymentProfileMock->expects($this->once())->method('getTokenFormData')->willReturn($formData);
        $paymentProfileMock->expects($this->once())
            ->method('importData')
            ->with($expectedImportData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [PaymentProfileService::API_NAME_PROFILE => $formData])
            ->willReturn([PaymentProfileService::API_NAME_PROFILE => $expectedImportData]);

        $this->assertSame(
            $paymentProfileMock,
            $this->paymentProfileService->verifyAndSaveToken($token, $paymentProfileMock)
        );
    }

    /**
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createProfileMock()
    {
        return $this->getMockBuilder('SubscribePro\Service\PaymentProfile\PaymentProfileInterface')->getMock();
    }
}
