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

    protected function setUp(): void
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
            TransactionInterface::ID => 123,
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

    public function testFailToVerifyProfileIfNotValid()
    {
        $this->expectExceptionMessage('Not all required fields are set.');
        $this->expectException(\SubscribePro\Exception\EntityInvalidDataException::class);
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
        $paymentProfileId = 5555;
        $formData = [TransactionInterface::AMOUNT => '1111'];
        $expectedImportData = [TransactionInterface::ID => '32323'];
        $url = "/services/v1/vault/paymentprofiles/{$paymentProfileId}/verify.json";

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('isVerifyDataValid')->willReturn(true);
        $transactionMock->expects($this->once())->method('getVerifyFormData')->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($expectedImportData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $expectedImportData]);

        $this->assertSame(
            $transactionMock,
            $this->transactionService->verifyProfile($paymentProfileId, $transactionMock)
        );
    }

    public function testAuthorizeByProfile()
    {
        $paymentProfileId = 4451;
        $authorizeData = ['profile_id' => $paymentProfileId];
        $formData = [TransactionInterface::AMOUNT => '1230'];
        $expectedImportData = [TransactionInterface::ID => '111'];
        $url = "/services/v1/vault/paymentprofiles/{$paymentProfileId}/authorize.json";

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('getFormData')->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($expectedImportData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $expectedImportData]);

        $this->assertSame(
            $transactionMock,
            $this->transactionService->authorizeByProfile($authorizeData, $transactionMock)
        );
    }

    public function testPurchaseByProfile()
    {
        $paymentProfileId = 1234;
        $authorizeData = ['profile_id' => $paymentProfileId];
        $formData = [TransactionInterface::AMOUNT => '1230'];
        $expectedImportData = [TransactionInterface::ID => '111'];
        $url = "/services/v1/vault/paymentprofiles/{$paymentProfileId}/purchase.json";

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('getFormData')->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($expectedImportData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $expectedImportData]);

        $this->assertSame(
            $transactionMock,
            $this->transactionService->purchaseByProfile($authorizeData, $transactionMock)
        );
    }

    public function testFailToAuthorizeByTokenIfNotValidTransaction()
    {
        $this->expectExceptionMessage('Not all required Transaction fields are set.');
        $this->expectException(\SubscribePro\Exception\EntityInvalidDataException::class);
        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isTokenDataValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->transactionService->authorizeByToken('token', $transactionMock, null);
    }

    public function testFailToAuthorizeByTokenWithAddressIfNotValidTransaction()
    {
        $this->expectExceptionMessage('Not all required Transaction fields are set.');
        $this->expectException(\SubscribePro\Exception\EntityInvalidDataException::class);
        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isTokenDataValid')
            ->willReturn(false);
        $addressMock = $this->createAddressMock();
        $addressMock->expects($this->never())->method('isAsChildValid');

        $this->httpClientMock->expects($this->never())->method('post');

        $this->transactionService->authorizeByToken('token', $transactionMock, $addressMock);
    }

    public function testFailToAuthorizeByTokenIfNotValidAddress()
    {
        $this->expectExceptionMessage('Not all required Address fields are set.');
        $this->expectException(\SubscribePro\Exception\EntityInvalidDataException::class);
        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isTokenDataValid')
            ->willReturn(true);

        $addressMock = $this->createAddressMock();
        $addressMock->expects($this->once())
            ->method('isAsChildValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->transactionService->authorizeByToken('token', $transactionMock, $addressMock);
    }

    public function testAuthorizeByToken()
    {
        $resultData = [TransactionInterface::ID => '111'];
        $formData = [TransactionInterface::AMOUNT => '1230'];
        $token = 'simple-token';
        $url = '/services/v1/vault/tokens/simple-token/authorize.json';

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('isTokenDataValid')->willReturn(true);
        $transactionMock->expects($this->once())
            ->method('getTokenFormData')
            ->with(null)
            ->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($resultData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $resultData]);

        $this->assertSame(
            $transactionMock,
            $this->transactionService->authorizeByToken($token, $transactionMock, null)
        );
    }

    public function testAuthorizeByTokenWithAddress()
    {
        $resultData = [TransactionInterface::ID => '123'];
        $formData = [TransactionInterface::AMOUNT => '52525'];
        $token = 'token1';
        $url = '/services/v1/vault/tokens/token1/authorize.json';

        $addressMock = $this->createAddressMock();
        $addressMock->expects($this->once())->method('isAsChildValid')->willReturn(true);

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('isTokenDataValid')->willReturn(true);
        $transactionMock->expects($this->once())
            ->method('getTokenFormData')
            ->with($addressMock)
            ->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($resultData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $resultData]);

        $this->assertSame(
            $transactionMock,
            $this->transactionService->authorizeByToken($token, $transactionMock, $addressMock)
        );
    }

    public function testFailToPurchaseByTokenIfNotValidTransaction()
    {
        $this->expectExceptionMessage('Not all required Transaction fields are set.');
        $this->expectException(\SubscribePro\Exception\EntityInvalidDataException::class);
        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isTokenDataValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->transactionService->purchaseByToken('token', $transactionMock, null);
    }

    public function testFailToPurchaseByTokenWithAddressIfNotValidTransaction()
    {
        $this->expectExceptionMessage('Not all required Transaction fields are set.');
        $this->expectException(\SubscribePro\Exception\EntityInvalidDataException::class);
        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isTokenDataValid')
            ->willReturn(false);
        $addressMock = $this->createAddressMock();
        $addressMock->expects($this->never())->method('isAsChildValid');

        $this->httpClientMock->expects($this->never())->method('post');

        $this->transactionService->purchaseByToken('token', $transactionMock, $addressMock);
    }

    public function testFailToPurchaseByTokenIfNotValidAddress()
    {
        $this->expectException(\SubscribePro\Exception\EntityInvalidDataException::class);
        $this->expectExceptionMessage('Not all required Address fields are set.');
        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isTokenDataValid')
            ->willReturn(true);

        $addressMock = $this->createAddressMock();
        $addressMock->expects($this->once())
            ->method('isAsChildValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->transactionService->purchaseByToken('token', $transactionMock, $addressMock);
    }

    public function testPurchaseByToken()
    {
        $resultData = [TransactionInterface::ID => '444'];
        $formData = [TransactionInterface::AMOUNT => '222'];
        $token = 'simple-token';
        $url = "/services/v1/vault/tokens/{$token}/purchase.json";

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('isTokenDataValid')->willReturn(true);
        $transactionMock->expects($this->once())
            ->method('getTokenFormData')
            ->with(null)
            ->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($resultData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $resultData]);

        $this->assertSame(
            $transactionMock,
            $this->transactionService->purchaseByToken($token, $transactionMock, null)
        );
    }

    public function testPurchaseByTokenWithAddress()
    {
        $resultData = [TransactionInterface::ID => '44124'];
        $formData = [TransactionInterface::AMOUNT => '12312'];
        $token = 'token2';
        $url = "/services/v1/vault/tokens/{$token}/purchase.json";

        $addressMock = $this->createAddressMock();
        $addressMock->expects($this->once())->method('isAsChildValid')->willReturn(true);

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())->method('isTokenDataValid')->willReturn(true);
        $transactionMock->expects($this->once())
            ->method('getTokenFormData')
            ->with($addressMock)
            ->willReturn($formData);
        $transactionMock->expects($this->once())
            ->method('importData')
            ->with($resultData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [TransactionService::API_NAME_TRANSACTION => $formData])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $resultData]);

        $this->assertSame(
            $transactionMock,
            $this->transactionService->purchaseByToken($token, $transactionMock, $addressMock)
        );
    }

    public function testFailToCaptureIfNotValid()
    {
        $this->expectExceptionMessage('Currency code not specified for given amount.');
        $this->expectException(\SubscribePro\Exception\EntityInvalidDataException::class);
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

        $this->assertSame(
            $transactionMock,
            $this->transactionService->capture($transactionId, $transactionMock)
        );
    }

    public function testFailToCreditIfNotValid()
    {
        $this->expectExceptionMessage('Currency code not specified for given amount.');
        $this->expectException(\SubscribePro\Exception\EntityInvalidDataException::class);
        $transactionId = 212;

        $transactionMock = $this->createTransactionMock();
        $transactionMock->expects($this->once())
            ->method('isServiceDataValid')
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())->method('post');

        $this->transactionService->credit($transactionId, $transactionMock);
    }

    public function testCredit()
    {
        $transactionId = 121;
        $resultData = [TransactionInterface::ID => $transactionId];

        $transactionMock = $this->createTransactionMock();
        $this->transactionFactoryMock->expects($this->once())
            ->method('create')
            ->with($resultData)
            ->willReturn($transactionMock);

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with("/services/v1/vault/transactions/{$transactionId}/credit.json", [])
            ->willReturn([TransactionService::API_NAME_TRANSACTION => $resultData]);

        $this->assertSame($transactionMock, $this->transactionService->credit($transactionId));
    }

    public function testCreditWithTransaction()
    {
        $transactionId = 123456;
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

        $this->assertSame(
            $transactionMock,
            $this->transactionService->credit($transactionId, $transactionMock)
        );
    }

    public function testVoid()
    {
        $itemId = 12321;
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
