<?php

namespace SubscribePro\Tests\Service\Token;

use SubscribePro\Service\Token\TokenInterface;
use SubscribePro\Service\Token\TokenService;

class TokenServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Token\TokenService
     */
    protected $tokenService;

    /**
     * @var \SubscribePro\Service\DataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $tokenFactoryMock;

    /**
     * @var \SubscribePro\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClientMock;

    protected function setUp()
    {
        $this->httpClientMock = $this->getMockBuilder('SubscribePro\Http')
            ->disableOriginalConstructor()
            ->getMock();

        $this->tokenFactoryMock = $this->getMockBuilder('SubscribePro\Service\DataFactoryInterface')->getMock();

        $this->tokenService = new TokenService($this->httpClientMock, $this->tokenFactoryMock);
    }

    public function testCreateToken()
    {
        $tokenMock = $this->createTokenMock();
        $tokenData = [
            TokenInterface::CITY => 'city',
            TokenInterface::TOKEN => 'token',
        ];

        $this->tokenFactoryMock->expects($this->once())
            ->method('create')
            ->with($tokenData)
            ->willReturn($tokenMock);

        $this->assertSame($tokenMock, $this->tokenService->createToken($tokenData));
    }

    /**
     * @expectedException \SubscribePro\Exception\EntityInvalidDataException
     * @expectedExceptionMessage Not all required fields are set.
     */
    public function testFailToSaveTokenIfTokenIsNotValid()
    {
        $tokenMock = $this->createTokenMock();
        $tokenMock->expects($this->once())
            ->method('isValid')
            ->willReturn(false);
        
        $this->httpClientMock->expects($this->never())->method('post');

        $this->tokenService->saveToken($tokenMock);
    }

    public function testSaveToken()
    {
        $url = '/services/v1/vault/token.json';
        $formData = [TokenInterface::CITY => 'city'];
        $expectedImportData = [TokenInterface::TOKEN => 'token'];

        $tokenMock = $this->createTokenMock();
        $tokenMock->expects($this->once())->method('isValid')->willReturn(true);
        $tokenMock->expects($this->once())->method('getFormData')->willReturn($formData);
        $tokenMock->expects($this->once())
            ->method('importData')
            ->with($expectedImportData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [TokenService::API_NAME_TOKEN => $formData])
            ->willReturn([TokenService::API_NAME_TOKEN => $expectedImportData]);

        $this->assertSame($tokenMock, $this->tokenService->saveToken($tokenMock));
    }

    public function testLoadToken()
    {
        $token = 'token-value';
        $itemData = [
            TokenInterface::TOKEN => 'token',
            TokenInterface::CITY => 'city',
        ];
        $tokenMock = $this->createTokenMock();

        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with("/services/v1/vault/tokens/{$token}.json")
            ->willReturn([TokenService::API_NAME_TOKEN => $itemData]);

        $this->tokenFactoryMock->expects($this->once())
            ->method('create')
            ->with($itemData)
            ->willReturn($tokenMock);

        $this->assertSame($tokenMock, $this->tokenService->loadToken($token));
    }

    /**
     * @return \SubscribePro\Service\Token\TokenInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createTokenMock()
    {
        return $this->getMockBuilder('SubscribePro\Service\Token\TokenInterface')->getMock();
    }
}
