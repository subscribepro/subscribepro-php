<?php

namespace SubscribePro\Tests\Tools;

use SubscribePro\Tools\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Tools\Config
     */
    protected $configTool;

    /**
     * @var \SubscribePro\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClientMock;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->getMockBuilder('SubscribePro\Http')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $this->configTool = new Config($this->httpClientMock);
    }

    /**
     * @param array $responseData
     * @param array $expectedResult
     * @dataProvider loadDataProvider
     */
    public function testLoad($responseData, $expectedResult)
    {
        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with('/services/v2/config.json')
            ->willReturn($responseData);

        $this->assertEquals($expectedResult, $this->configTool->load());
    }

    /**
     * @return array
     */
    public function loadDataProvider()
    {
        return [
            'Not valid response' => [
                'resultData' => ['some_key' => 'some_value'],
                'expectedResult' => [],
            ],
            'Valid response' => [
                'resultData' => [Config::API_NAME_CONFIG => ['config_option' => 'option_value']],
                'expectedResult' => ['config_option' => 'option_value'],
            ],
        ];
    }
}
