<?php

namespace SubscribePro\Utils;

use SubscribePro\Service\Subscription\SubscriptionInterface;

class SubscriptionUtils
{

    /**
     * @param array $subscriptions
     *
     * @return array
     */
    public static function filterAndSortSubscriptionListForDisplay(array $subscriptions)
    {
        $filteredList = self::filterSubscriptionListForDisplay($subscriptions);
        $filteredAndSortedList = self::sortSubscriptionListForDisplay($filteredList);

        return $filteredAndSortedList;
    }

    /**
     * @param array $subscriptions
     *
     * @return array
     */
    public static function sortSubscriptionListForDisplay(array $subscriptions)
    {
        usort($subscriptions, '\\SubscribePro\\Utils\\SubscriptionUtils::compareSubscriptions');

        return $subscriptions;
    }

    /**
     * @param array $subscriptions
     *
     * @return array
     */
    public static function filterSubscriptionListForDisplay(array $subscriptions)
    {
        return array_filter($subscriptions, '\\SubscribePro\\Utils\\SubscriptionUtils::matchesShouldDisplayFilter');
    }

    /**
     * @param SubscriptionInterface $subscription
     * @return bool
     */
    protected static function matchesShouldDisplayFilter(\SubscribePro\Service\Subscription\SubscriptionInterface $subscription)
    {
        // Ignore cancelled subscriptions
        if ($subscription->getStatus() == 'Cancelled') {
            return false;
        }
        // Ignore expired subscriptions
        if ($subscription->getStatus() == 'Expired') {
            return false;
        }

        return true;
    }

    /**
     * @param SubscriptionInterface $a
     * @param SubscriptionInterface $b
     * @return int
     */
    protected static function compareSubscriptions(\SubscribePro\Service\Subscription\SubscriptionInterface $a, \SubscribePro\Service\Subscription\SubscriptionInterface $b)
    {
        // Compare Status - Failed or Retry always comes first
        if (($a->getStatus() == 'Failed' || $a->getStatus() == 'Retry') && $b->getStatus() != 'Failed' && $b->getStatus() != 'Retry') {
            return -1;
        }
        else if (($b->getStatus() == 'Failed' || $b->getStatus() == 'Retry') && $a->getStatus() != 'Failed' && $a->getStatus() != 'Retry') {
            return 1;
        }

        // Compare Status - Paused always comes last
        if ($a->getStatus() == 'Paused' && $b->getStatus() != 'Paused') {
            return 1;
        }
        else if ($b->getStatus() == 'Paused' && $a->getStatus() != 'Paused') {
            return -1;
        }

        // Compare by next order date reversed
        $dateResult = (0 - strcmp($a->getNextOrderDate(), $b->getNextOrderDate()));
        if($dateResult != 0) {
            return $dateResult;
        }

        // Compare by shipping address
        $shippingAddressResult = strcmp($a->getShippingAddressId(), $b->getShippingAddressId());
        if($shippingAddressResult != 0) {
            return $shippingAddressResult;
        }

        // Otherwise, consider them to match
        return 0;
    }

}
