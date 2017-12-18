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

    /**
     * @return array
     */
    public function filterSubscriptionsDataProvider()
    {
        return [
            '0 Subscriptions' => [
                'subscriptions' => [],
                'expectedSubscriptions' => [],
            ],
            '1 Subscription | Filtered to 0 (Cancelled)' => [
                'subscriptions' => [
                    [ SubscriptionInterface::ID => 1, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_CANCELLED],
                ],
                'expectedSubscriptions' => [],
            ],
            'Multiple Subscriptions | All Statuses Filtered' => [
                'subscriptions' => [
                    [ SubscriptionInterface::ID => 1, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_ACTIVE],
                    [ SubscriptionInterface::ID => 2, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_FAILED],
                    [ SubscriptionInterface::ID => 3, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_RETRY],
                    [ SubscriptionInterface::ID => 4, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_PAUSED],
                    [ SubscriptionInterface::ID => 5, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_CANCELLED],
                    [ SubscriptionInterface::ID => 6, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_EXPIRED],
                ],
                'expectedSubscriptions' => [1, 2, 3, 4],
            ],
        ];
    }

    /**
     * @return array
     */
    public function sortSubscriptionsDataProvider()
    {
        return [
            'Sort Failed and Retry First | All Same Next Order Date and Shipping Address' => [
                'subscriptions' => [
                    [ SubscriptionInterface::ID => 1, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_ACTIVE, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                    [ SubscriptionInterface::ID => 2, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_FAILED, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                    [ SubscriptionInterface::ID => 3, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_RETRY, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                    [ SubscriptionInterface::ID => 4, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_CANCELLED, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                    [ SubscriptionInterface::ID => 5, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_EXPIRED, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                ],
                'expectedSubscriptions' => [3, 2, 1, 4, 5],
            ],
            'Sort Paused to the End | All Same Next Order Date and Shipping Address' => [
                'subscriptions' => [
                    [ SubscriptionInterface::ID => 1, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_ACTIVE, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                    [ SubscriptionInterface::ID => 2, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_PAUSED, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                    [ SubscriptionInterface::ID => 3, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_CANCELLED, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                    [ SubscriptionInterface::ID => 4, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_EXPIRED, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                ],
                'expectedSubscriptions' => [1, 3, 4, 2],
            ],
            'Sort Next Order Date | All Active Subscriptions, Same Shipping Address' => [
                'subscriptions' => [
                    [ SubscriptionInterface::ID => 1, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_ACTIVE, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                    [ SubscriptionInterface::ID => 2, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_ACTIVE, SubscriptionInterface::NEXT_ORDER_DATE => '2018-12-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                    [ SubscriptionInterface::ID => 3, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_ACTIVE, SubscriptionInterface::NEXT_ORDER_DATE => '2016-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                    [ SubscriptionInterface::ID => 4, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_ACTIVE, SubscriptionInterface::NEXT_ORDER_DATE => '2018-05-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                ],
                'expectedSubscriptions' => [2, 4, 1, 3],
            ],
            'Sort Shipping Address | All Active Subscriptions, Same Next Order Date' => [
                'subscriptions' => [
                    [ SubscriptionInterface::ID => 1, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_ACTIVE, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                    [ SubscriptionInterface::ID => 2, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_ACTIVE, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 3],
                    [ SubscriptionInterface::ID => 3, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_ACTIVE, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 1],
                    [ SubscriptionInterface::ID => 4, SubscriptionInterface::STATUS => SubscriptionInterface::STATUS_ACTIVE, SubscriptionInterface::NEXT_ORDER_DATE => '2017-01-01', SubscriptionInterface::SHIPPING_ADDRESS_ID => 2],
                ],
                'expectedSubscriptions' => [1, 3, 4, 2],
            ],
        ];
    }

    /**
     * @param array $subscriptionData
     * @param array $expectedResult
     * @dataProvider filterSubscriptionsDataProvider
     */
    public function testFilterSubscriptions($subscriptionData, $expectedResult)
    {
        $subscriptions = $this->createSubscriptionsFromTestData($subscriptionData);
        $subscriptionUtils = new SubscriptionUtils();
        $filterResults = $subscriptionUtils->filterSubscriptionListForDisplay($subscriptions);

        $this->assertSame($this->getSubscriptionIdsFromList($filterResults), $expectedResult);
    }

    /**
     * @param array $subscriptionData
     * @param array $expectedResult
     * @dataProvider sortSubscriptionsDataProvider
     */
    public function testSortSubscriptions($subscriptionData, $expectedResult)
    {
        $subscriptions = $this->createSubscriptionsFromTestData($subscriptionData);
        $subscriptionUtils = new SubscriptionUtils();
        $filterResults = $subscriptionUtils->sortSubscriptionListForDisplay($subscriptions);

        $this->assertSame($this->getSubscriptionIdsFromList($filterResults), $expectedResult);
    }

    /**
     * @param array $subscriptionData
     * @return array
     */
    protected function createSubscriptionsFromTestData($subscriptionData)
    {
        $paymentProfileMock = $this->getMockBuilder('SubscribePro\Service\PaymentProfile\PaymentProfileInterface')->getMock();
        $shippingAddressMock = $this->getMockBuilder('SubscribePro\Service\Address\AddressInterface')->getMock();

        $subscriptions = [];
        foreach ($subscriptionData as $data) {
            if (!isset($subscriptionData[SubscriptionInterface::SHIPPING_ADDRESS_ID])) {
                $data[SubscriptionInterface::SHIPPING_ADDRESS] = $shippingAddressMock;
            }
            $data[SubscriptionInterface::PAYMENT_PROFILE] = $paymentProfileMock;

            $subscriptions[] = new Subscription($data);
        }

        return $subscriptions;
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
