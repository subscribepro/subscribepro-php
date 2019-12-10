<?php

namespace SubscribePro\Tests\Service\Subscription;

use SubscribePro\Service\Subscription\SubscriptionInterface;
use SubscribePro\Service\Subscription\SubscriptionService;

class SubscriptionServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Subscription\SubscriptionService
     */
    protected $subscriptionService;

    /**
     * @var \SubscribePro\Service\DataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subscriptionFactoryMock;

    /**
     * @var \SubscribePro\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClientMock;

    protected function setUp()
    {
        $this->httpClientMock = $this->getMockBuilder('SubscribePro\Http')
            ->disableOriginalConstructor()
            ->getMock();

        $this->subscriptionFactoryMock = $this->getMockBuilder('SubscribePro\Service\DataFactoryInterface')->getMock();

        $this->subscriptionService = new SubscriptionService($this->httpClientMock, $this->subscriptionFactoryMock);
    }

    public function testCreateSubscription()
    {
        $subscriptionMock = $this->createSubscriptionMock();
        $subscriptionData = [SubscriptionInterface::QTY => 555];

        $this->subscriptionFactoryMock->expects($this->once())
            ->method('create')
            ->with($subscriptionData)
            ->willReturn($subscriptionMock);

        $this->assertSame($subscriptionMock, $this->subscriptionService->createSubscription($subscriptionData));
    }

    /**
     * @param string $url
     * @param string $itemId
     * @param bool $isNew
     * @param array $formData
     * @param array $resultData
     * @dataProvider saveSubscriptionDataProvider
     */
    public function testSaveSubscription($url, $itemId, $isNew, $formData, $resultData)
    {
        $subscriptionMock = $this->createSubscriptionMock();
        $subscriptionMock->expects($this->once())->method('isNew')->willReturn($isNew);
        $subscriptionMock->expects($this->once())->method('getFormData')->willReturn($formData);
        $subscriptionMock->expects($this->any())->method('getId')->willReturn($itemId);
        $subscriptionMock->expects($this->once())
            ->method('importData')
            ->with($resultData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [SubscriptionService::API_NAME_SUBSCRIPTION => $formData])
            ->willReturn([SubscriptionService::API_NAME_SUBSCRIPTION => $resultData]);

        $this->assertSame($subscriptionMock, $this->subscriptionService->saveSubscription($subscriptionMock));
    }

    /**
     * @return array
     */
    public function saveSubscriptionDataProvider()
    {
        return [
            'Save new subscription' => [
                'url' => '/services/v2/subscription.json',
                'itemId' => null,
                'isNew' => true,
                'formData' => [SubscriptionInterface::QTY => '123'],
                'resultData' => [SubscriptionInterface::ID => 11],
            ],
            'Update existing subscription' => [
                'url' => '/services/v2/subscriptions/22.json',
                'itemId' => 22,
                'isNew' => false,
                'formData' => [SubscriptionInterface::QTY => '123'],
                'resultData' => [SubscriptionInterface::ID => 22],
            ],
        ];
    }

    public function testLoadSubscription()
    {
        $itemId = 111;
        $subscriptionMock = $this->createSubscriptionMock();
        $itemData = [SubscriptionInterface::QTY => 123];

        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with("/services/v2/subscriptions/{$itemId}.json")
            ->willReturn([SubscriptionService::API_NAME_SUBSCRIPTION => $itemData]);

        $this->subscriptionFactoryMock->expects($this->once())
            ->method('create')
            ->with($itemData)
            ->willReturn($subscriptionMock);

        $this->assertSame($subscriptionMock, $this->subscriptionService->loadSubscription($itemId));
    }

    /**
     * @param int $customerId
     * @param array $filters
     * @param array $itemsData
     * @dataProvider loadSubscriptionsDataProvider
     */
    public function testLoadSubscriptions($customerId, $filters, $itemsData)
    {
        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with('/services/v2/subscriptions.json', $filters)
            ->willReturn([SubscriptionService::API_NAME_SUBSCRIPTIONS => $itemsData]);

        $subscriptions = [];
        $subscriptionFactoryMap = [];
        foreach ($itemsData as $itemData) {
            $subscription = $this->createSubscriptionMock();
            $subscriptionFactoryMap[] = [$itemData, $subscription];
            $subscriptions[] = $subscription;
        }
        $this->subscriptionFactoryMock->expects($this->exactly(count($itemsData)))
            ->method('create')
            ->willReturnMap($subscriptionFactoryMap);

        $this->assertSame($subscriptions, $this->subscriptionService->loadSubscriptions($customerId));
    }

    /**
     * @return array
     */
    public function loadSubscriptionsDataProvider()
    {
        return [
            'Loading without filter' => [
                'customerId' => null,
                'filters' => ['count' => 25],
                'itemsData' => [[SubscriptionInterface::ID => 111], [SubscriptionInterface::ID => 222]]
            ],
            'Loading by customer ID' => [
                'customerId' => 122,
                'filters' => [SubscriptionInterface::CUSTOMER_ID => 122, 'count' => 25],
                'itemsData' => [[SubscriptionInterface::CUSTOMER_ID => 122, SubscriptionInterface::ID => 333]]
            ],
        ];
    }

    public function testCancelSubscription()
    {
        $subscriptionId = 17;
        
        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with("/services/v2/subscriptions/{$subscriptionId}/cancel.json");
        
        $this->subscriptionService->cancelSubscription($subscriptionId);
    }

    public function testPauseSubscription()
    {
        $subscriptionId = 23;
        
        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with("/services/v2/subscriptions/{$subscriptionId}/pause.json");
        
        $this->subscriptionService->pauseSubscription($subscriptionId);
    }

    public function testRestartSubscription()
    {
        $subscriptionId = 41;
        
        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with("/services/v2/subscriptions/{$subscriptionId}/restart.json");
        
        $this->subscriptionService->restartSubscription($subscriptionId);
    }

    public function testSkipSubscription()
    {
        $subscriptionId = 3;
        
        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with("/services/v2/subscriptions/{$subscriptionId}/skip.json");
        
        $this->subscriptionService->skipSubscription($subscriptionId);
    }

    /**
     * @return \SubscribePro\Service\Subscription\SubscriptionInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createSubscriptionMock()
    {
        return $this->getMockBuilder('SubscribePro\Service\Subscription\SubscriptionInterface')->getMock();
    }
}
