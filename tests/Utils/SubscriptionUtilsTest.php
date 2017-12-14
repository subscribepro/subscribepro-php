<?php

namespace SubscribePro\Tests\Utils;

use SubscribePro\Utils\SubscriptionUtils;
use SubscribePro\Service\Subscription\Subscription;
use SubscribePro\Service\Subscription\SubscriptionInterface;

class SubscriptionUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Subscription\SubscriptionInterface
     */
    protected $subscriptions;

    protected function setUp()
    {

        $this->paymentProfileMock = $this->getMockBuilder('SubscribePro\Service\PaymentProfile\PaymentProfileInterface')->getMock();
        $this->shippingAddressMock = $this->getMockBuilder('SubscribePro\Service\Address\AddressInterface')->getMock();
        // $this->shippingAddressMock->setId(1);

        $this->subscriptions = [
            new Subscription([
                    SubscriptionInterface::SHIPPING_ADDRESS => $this->shippingAddressMock,
                    SubscriptionInterface::PAYMENT_PROFILE => $this->paymentProfileMock,
                    SubscriptionInterface::NEXT_ORDER_DATE => '2017-12-31',
                    SubscriptionInterface::ID => 1,
                    SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_ACTIVE,
            ]),
            new Subscription([
                    SubscriptionInterface::SHIPPING_ADDRESS => $this->shippingAddressMock,
                    SubscriptionInterface::PAYMENT_PROFILE => $this->paymentProfileMock,
                    SubscriptionInterface::NEXT_ORDER_DATE => '2018-01-01',
                    SubscriptionInterface::ID => 2,
                    SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_CANCELLED,
            ]),
            new Subscription([
                    SubscriptionInterface::SHIPPING_ADDRESS => $this->shippingAddressMock,
                    SubscriptionInterface::PAYMENT_PROFILE => $this->paymentProfileMock,
                    SubscriptionInterface::NEXT_ORDER_DATE => '2017-11-30',
                    SubscriptionInterface::ID => 3,
                    SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_EXPIRED,
            ]),
            new Subscription([
                    SubscriptionInterface::SHIPPING_ADDRESS => $this->shippingAddressMock,
                    SubscriptionInterface::PAYMENT_PROFILE => $this->paymentProfileMock,
                    SubscriptionInterface::NEXT_ORDER_DATE => '2017-12-31',
                    SubscriptionInterface::ID => 4,
                    SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_FAILED,
            ]),
            new Subscription([
                    SubscriptionInterface::SHIPPING_ADDRESS => $this->shippingAddressMock,
                    SubscriptionInterface::PAYMENT_PROFILE => $this->paymentProfileMock,
                    SubscriptionInterface::NEXT_ORDER_DATE => '2017-12-31',
                    SubscriptionInterface::ID => 5,
                    SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_PAUSED,
            ]),
        ];

        $this->subscriptionUtils = new SubscriptionUtils;
    }

    /**
     * @param array $expectedResult
     * @dataProvider sortSubscriptionsDataProvider
     */
    public function testSortSubscriptions($expectedResult)
    {
        $sortResults = $this->subscriptionUtils->sortSubscriptionListForDisplay($this->subscriptions);

        $this->assertSame($this->getSubscriptionIdsFromList($sortResults), $expectedResult);
    }

    /**
     * @return array
     */
    public function sortSubscriptionsDataProvider()
    {
        return [
            'Subscriptions sorted' => [
                'expectedResult' => [
                    // Failed is first
                    4,
                    // Next order date is compared in reverse
                    2,
                    1,
                    3,
                    // Paused is last
                    5,
                ],
            ],
        ];
    }

    /**
     * @param array $expectedResult
     * @dataProvider filterSubscriptionsDataProvider
     */
    public function testFilterSubscriptions($expectedResult)
    {
        $filterResults = $this->subscriptionUtils->filterSubscriptionListForDisplay($this->subscriptions);

        $this->assertSame($this->getSubscriptionIdsFromList($filterResults), $expectedResult);
    }

    public function filterSubscriptionsDataProvider()
    {
        return [
            'Subscriptions filtered' => [
                'expectedResult' => [
                    // No cancelled or expired subscriptions
                    1,
                    4,
                    5,
                ],
            ],
        ];
    }

    /**
     * @param array $expectedResult
     * @dataProvider filterAndSortSubscriptionsDataProvider
     */
    public function testFilterAndSortSubscriptions($expectedResult)
    {
        $filterResults = $this->subscriptionUtils->filterAndSortSubscriptionListForDisplay($this->subscriptions);

        $this->assertSame($this->getSubscriptionIdsFromList($filterResults), $expectedResult);
    }

    public function filterAndSortSubscriptionsDataProvider()
    {
        return [
            'Subscriptions filtered' => [
                'expectedResult' => [
                    // No cancelled or expired subscriptions
                    // Failed is first
                    4,
                    // Next order date is compared in reverse
                    1,
                    // Paused is last
                    5,
                ],
            ],
        ];
    }

    /**
     * @param array $subscriptions
     * @return array
     */
    protected function getSubscriptionIdsFromList($subscriptions)
    {
        $ids = [];
        foreach ($subscriptions as $subscription) {
            $ids[] = $subscription->getId();
        }
        return $ids;
    }
}
