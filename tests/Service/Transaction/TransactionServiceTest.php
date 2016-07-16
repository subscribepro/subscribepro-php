<?php

namespace SubscribePro\Tests\Service\Transaction;

use SubscribePro\Service\Transaction\TransactionInterface;
use SubscribePro\Service\Transaction\TransactionService;

class TransactionServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Transaction\TransactionService
     */
    protected $transactionService;

    /**
     * @var \SubscribePro\Service\DataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $transactionFactoryMock;

    /**
     * @var \SubscribePro\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClientMock;

    protected function setUp()
    {
        $this->httpClientMock = $this->getMockBuilder('SubscribePro\Http')
            ->disableOriginalConstructor()
            ->getMock();

        $this->transactionFactoryMock = $this->getMockBuilder('SubscribePro\Service\DataFactoryInterface')->getMock();

        $this->transactionService = new TransactionService($this->httpClientMock, $this->transactionFactoryMock);
    }

    public function testCreateTransaction()
    {
        $transactionMock = $this->createTransactionMock();
        $transactionData = [
            TransactionInterface::ID => 123
        ];

        $this->transactionFactoryMock->expects($this->once())
            ->method('create')
            ->with($transactionData)
            ->willReturn($transactionMock);

        $this->assertSame($transactionMock, $this->transactionService->createTransaction($transactionData));
    }

    public function testLoadTransaction()
    {
        $itemId = 111;
        $itemData = [TransactionInterface::ID => $itemId];
        $transactionMock = $this->createTransactionMock();

        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with("/services/v1/vault/transactions/{$itemId}.json")
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $itemData]);

        $this->transactionFactoryMock->expects($this->once())
            ->method('create')
            ->with($itemData)
            ->willReturn($transactionMock);

        $this->assertSame($transactionMock, $this->transactionService->loadTransaction($itemId));
    }

    /**
     * @expectedException \SubscribePro\Exception\EntityInvalidDataException
     * @expectedExceptionMessage Not all required fields are set.
     */
    public function testFailToVerifyProfileIfNotValid()
    {
        $paymentProfileId = 1234;

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isVerifyDataValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->transactionService->verifyProfile($paymentProfileId, $transactionMock);
    }

    public function testVerifyProfile()
    {
        $paymentProfileId = 1234;
        $formData = [TransactionInterface::AMOUNT => '1230'];
        $expectedImportData = [TransactionInterface::ID => '111'];

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('isVerifyDataValid')->willReturn(true);
        $transactionMock->expects($this->once())->method('getVerifyFormData')->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($expectedImportData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with("/services/v1/vault/paymentprofiles/{$paymentProfileId}/verify.json", [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $expectedImportData]);

        $this->assertSame($transactionMock, $this->transactionService->verifyProfile($paymentProfileId, $transactionMock));
    }

    /**
     * @expectedException \SubscribePro\Exception\EntityInvalidDataException
     * @expectedExceptionMessage Not all required fields are set.
     */
    public function testFailToAuthorizeByProfileIfNotValid()
    {
        $paymentProfileId = 1234;

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->transactionService->authorizeByProfile($paymentProfileId, $transactionMock);
    }

    public function testAuthorizeByProfile()
    {
        $paymentProfileId = 1234;
        $formData = [TransactionInterface::AMOUNT => '1230'];
        $expectedImportData = [TransactionInterface::ID => '111'];

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('isValid')->willReturn(true);
        $transactionMock->expects($this->once())->method('getFormData')->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($expectedImportData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with("/services/v1/vault/paymentprofiles/{$paymentProfileId}/authorize.json", [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $expectedImportData]);

        $this->assertSame($transactionMock, $this->transactionService->authorizeByProfile($paymentProfileId, $transactionMock));
    }

    /**
     * @expectedException \SubscribePro\Exception\EntityInvalidDataException
     * @expectedExceptionMessage Not all required fields are set.
     */
    public function testFailToPurchaseByProfileIfNotValid()
    {
        $paymentProfileId = 1234;

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->transactionService->purchaseByProfile($paymentProfileId, $transactionMock);
    }

    public function testPurchaseByProfile()
    {
        $paymentProfileId = 1234;
        $formData = [TransactionInterface::AMOUNT => '1230'];
        $expectedImportData = [TransactionInterface::ID => '111'];

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('isValid')->willReturn(true);
        $transactionMock->expects($this->once())->method('getFormData')->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($expectedImportData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with("/services/v1/vault/paymentprofiles/{$paymentProfileId}/purchase.json", [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $expectedImportData]);

        $this->assertSame($transactionMock, $this->transactionService->purchaseByProfile($paymentProfileId, $transactionMock));
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $addressMock
     * @param \PHPUnit_Framework_MockObject_MockObject|null $addressParameter
     * @param string $token
     * @param bool $isValidTransaction
     * @param bool $isValidAddress
     * @param string $expectedMessage
     * @dataProvider failToAuthorizeByTokenIfNotValidDataProvider
     * @expectedException \SubscribePro\Exception\EntityInvalidDataException
     */
    public function testFailToAuthorizeByTokenIfNotValid($addressMock, $addressParameter, $token, $isValidTransaction, $isValidAddress, $expectedMessage)
    {
        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isTokenDataValid')
            ->willReturn($isValidTransaction);

        $addressMock->expects($this->any())
            ->method('isAsChildValid')
            ->willReturn($isValidAddress);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->expectExceptionMessage($expectedMessage);

        $this->transactionService->authorizeByToken($token, $transactionMock, $addressParameter);
    }

    /**
     * @return array
     */
    public function failToAuthorizeByTokenIfNotValidDataProvider()
    {
        $address1Mock = $this->createAddressMock();
        $address2Mock = $this->createAddressMock();
        $address3Mock = $this->createAddressMock();

        return [
            'Not valid transaction without address' => [
                'addressMock' => $address1Mock,
                'addressParameter' => null,
                'token' => 'token',
                'isValidTransaction' => false,
                'isValidAddress' => false,
                'expectedMessage' => 'Not all required Transaction fields are set.',
            ],
            'Not valid transaction with address' => [
                'addressMock' => $address2Mock,
                'addressParameter' => $address2Mock,
                'token' => 'token',
                'isValidTransaction' => false,
                'isValidAddress' => true,
                'expectedMessage' => 'Not all required Transaction fields are set.',
            ],
            'Not valid address' => [
                'addressMock' => $address3Mock,
                'addressParameter' => $address3Mock,
                'token' => 'token',
                'isValidTransaction' => true,
                'isValidAddress' => false,
                'expectedMessage' => 'Not all required Address fields are set.',
            ],
        ];
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $addressMock
     * @param \PHPUnit_Framework_MockObject_MockObject|null $addressParameter
     * @param array $formData
     * @param string $token
     * @param string $url
     * @param array $resultData
     * @dataProvider authorizeByTokenDataProvider
     */
    public function testAuthorizeByToken($addressMock, $addressParameter, $formData, $token, $url, $resultData)
    {
        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('isTokenDataValid')->willReturn(true);
        $transactionMock->expects($this->once())
            ->method('getTokenFormData')
            ->with($addressParameter)
            ->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($resultData)
            ->willReturnSelf();

        $addressMock->expects($this->any())->method('isAsChildValid')->willReturn(true);

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $resultData]);

        $this->assertSame($transactionMock, $this->transactionService->authorizeByToken($token, $transactionMock, $addressParameter));
    }

    /**
     * @return array
     */
    public function authorizeByTokenDataProvider()
    {
        $address1Mock = $this->createAddressMock();
        $address2Mock = $this->createAddressMock();

        return [
            'Authorize without address' => [
                'addressMock' => $address1Mock,
                'addressParameter' => null,
                'formData' => [TransactionInterface::AMOUNT => '1230'],
                'token' => 'token',
                'url' => '/services/v1/vault/tokens/token/authorize.json',
                'resultData' => [TransactionInterface::ID => '111'],
            ],
            'Authorize with address' => [
                'addressMock' => $address2Mock,
                'addressParameter' => $address2Mock,
                'formData' => [TransactionInterface::AMOUNT => '1230'],
                'token' => 'token',
                'url' => '/services/v1/vault/tokens/token/authorize.json',
                'resultData' => [TransactionInterface::ID => '111'],
            ],
        ];
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $addressMock
     * @param \PHPUnit_Framework_MockObject_MockObject|null $addressParameter
     * @param string $token
     * @param bool $isValidTransaction
     * @param bool $isValidAddress
     * @param string $expectedMessage
     * @dataProvider failToPurchaseByTokenIfNotValidDataProvider
     * @expectedException \SubscribePro\Exception\EntityInvalidDataException
     */
    public function testFailToPurchaseByTokenIfNotValid($addressMock, $addressParameter, $token, $isValidTransaction, $isValidAddress, $expectedMessage)
    {
        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isTokenDataValid')
            ->willReturn($isValidTransaction);

        $addressMock->expects($this->any())
            ->method('isAsChildValid')
            ->willReturn($isValidAddress);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->expectExceptionMessage($expectedMessage);

        $this->transactionService->purchaseByToken($token, $transactionMock, $addressParameter);
    }

    /**
     * @return array
     */
    public function failToPurchaseByTokenIfNotValidDataProvider()
    {
        $address1Mock = $this->createAddressMock();
        $address2Mock = $this->createAddressMock();
        $address3Mock = $this->createAddressMock();

        return [
            'Not valid transaction without address' => [
                'addressMock' => $address1Mock,
                'addressParameter' => null,
                'token' => 'token',
                'isValidTransaction' => false,
                'isValidAddress' => false,
                'expectedMessage' => 'Not all required Transaction fields are set.',
            ],
            'Not valid transaction with address' => [
                'addressMock' => $address2Mock,
                'addressParameter' => $address2Mock,
                'token' => 'token',
                'isValidTransaction' => false,
                'isValidAddress' => true,
                'expectedMessage' => 'Not all required Transaction fields are set.',
            ],
            'Not valid address' => [
                'addressMock' => $address3Mock,
                'addressParameter' => $address3Mock,
                'token' => 'token',
                'isValidTransaction' => true,
                'isValidAddress' => false,
                'expectedMessage' => 'Not all required Address fields are set.',
            ],
        ];
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $addressMock
     * @param \PHPUnit_Framework_MockObject_MockObject|null $addressParameter
     * @param array $formData
     * @param string $token
     * @param string $url
     * @param array $resultData
     * @dataProvider purchaseByTokenDataProvider
     */
    public function testPurchaseByToken($addressMock, $addressParameter, $formData, $token, $url, $resultData)
    {
        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('isTokenDataValid')->willReturn(true);
        $transactionMock->expects($this->once())
            ->method('getTokenFormData')
            ->with($addressParameter)
            ->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($resultData)
            ->willReturnSelf();

        $addressMock->expects($this->any())
            ->method('isAsChildValid')
            ->willReturn(true);

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $resultData]);

        $this->assertSame($transactionMock, $this->transactionService->purchaseByToken($token, $transactionMock, $addressParameter));
    }

    /**
     * @return array
     */
    public function purchaseByTokenDataProvider()
    {
        $address1Mock = $this->createAddressMock();
        $address2Mock = $this->createAddressMock();

        return [
            'Purchase without address' => [
                'addressMock' => $address1Mock,
                'addressParameter' => null,
                'formData' => [TransactionInterface::AMOUNT => '1230'],
                'token' => 'token',
                'url' => '/services/v1/vault/tokens/token/purchase.json',
                'resultData' => [TransactionInterface::ID => '111'],
            ],
            'Purchase with address' => [
                'addressMock' => $address2Mock,
                'addressParameter' => $address2Mock,
                'formData' => [TransactionInterface::AMOUNT => '1111'],
                'token' => 'token',
                'url' => '/services/v1/vault/tokens/token/purchase.json',
                'resultData' => [TransactionInterface::ID => '222'],
            ],
        ];
    }

    /**
     * @expectedException \SubscribePro\Exception\EntityInvalidDataException
     * @expectedExceptionMessage Currency code not specified for given amount.
     */
    public function testFailToCaptureIfNotValid()
    {
        $transactionId = '121';

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isServiceDataValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->transactionService->capture($transactionId, $transactionMock);
    }

    public function testCapture()
    {
        $transactionId = '121';
        $url = "/services/v1/vault/transactions/{$transactionId}/capture.json";
        $resultData = [TransactionInterface::ID => $transactionId];

        $transactionMock = $this->createTransactionMock();
        $this->transactionFactoryMock->expects($this->once())
            ->method('create')
            ->with($resultData)
            ->willReturn($transactionMock);

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $resultData]);

        $this->assertSame($transactionMock, $this->transactionService->capture($transactionId));
    }

    public function testCaptureWithTransaction()
    {
        $transactionId = '121';
        $url = "/services/v1/vault/transactions/{$transactionId}/capture.json";
        $formData = [TransactionInterface::AMOUNT => '1230'];
        $resultData = [TransactionInterface::ID => $transactionId];

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('isServiceDataValid')->willReturn(true);
        $transactionMock->expects($this->once())->method('getServiceFormData')->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($resultData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $resultData]);

        $this->assertSame($transactionMock, $this->transactionService->capture($transactionId, $transactionMock));
    }

    /**
     * @expectedException \SubscribePro\Exception\EntityInvalidDataException
     * @expectedExceptionMessage Currency code not specified for given amount.
     */
    public function testFailToCreditIfNotValid()
    {
        $transactionId = '121';

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isServiceDataValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->transactionService->credit($transactionId, $transactionMock);
    }

    public function testCredit()
    {
        $transactionId = '121';
        $url = "/services/v1/vault/transactions/{$transactionId}/credit.json";
        $resultData = [TransactionInterface::ID => $transactionId];

        $transactionMock = $this->createTransactionMock();
        $this->transactionFactoryMock->expects($this->once())
            ->method('create')
            ->with($resultData)
            ->willReturn($transactionMock);

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $resultData]);

        $this->assertSame($transactionMock, $this->transactionService->credit($transactionId));
    }

    public function testCreditWithTransaction()
    {
        $transactionId = '121';
        $url = "/services/v1/vault/transactions/{$transactionId}/credit.json";
        $formData = [TransactionInterface::AMOUNT => '1230'];
        $resultData = [TransactionInterface::ID => $transactionId];

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('isServiceDataValid')->willReturn(true);
        $transactionMock->expects($this->once())->method('getServiceFormData')->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($resultData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $resultData]);

        $this->assertSame($transactionMock, $this->transactionService->credit($transactionId, $transactionMock));
    }

    public function testVoid()
    {
        $itemId = 111;
        $resultData = [TransactionInterface::ID => $itemId];

        $transactionMock = $this->createTransactionMock();
        $this->transactionFactoryMock->expects($this->once())
            ->method('create')
            ->with($resultData)
            ->willReturn($transactionMock);

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with("/services/v1/vault/transactions/{$itemId}/void.json")
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $resultData]);

        $this->assertSame($transactionMock, $this->transactionService->void($itemId));
    }

    /**
     * @return \SubscribePro\Service\Transaction\TransactionInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createTransactionMock()
    {
        return $this->getMockBuilder('SubscribePro\Service\Transaction\TransactionInterface')->getMock();
    }

    /**
     * @return \SubscribePro\Service\Address\AddressInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createAddressMock()
    {
        return $this->getMockBuilder('SubscribePro\Service\Address\AddressInterface')->getMock();
    }
}
