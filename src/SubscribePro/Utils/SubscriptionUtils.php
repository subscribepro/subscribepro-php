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
     *
     * @return bool
     */
    protected static function matchesShouldDisplayFilter(SubscriptionInterface $subscription)
    {
        // Ignore cancelled subscriptions
        if ('Cancelled' == $subscription->getStatus()) {
            return false;
        }
        // Ignore expired subscriptions
        if ('Expired' == $subscription->getStatus()) {
            return false;
        }

        return true;
    }

    /**
     * @param SubscriptionInterface $a
     * @param SubscriptionInterface $b
     *
     * @return int
     */
    protected static function compareSubscriptions(SubscriptionInterface $a, SubscriptionInterface $b)
    {
        // Compare Status - Failed or Retry always comes first
        if (('Failed' == $a->getStatus() || 'Retry' == $a->getStatus()) && 'Failed' != $b->getStatus() && 'Retry' != $b->getStatus()) {
            return -1;
        } elseif (('Failed' == $b->getStatus() || 'Retry' == $b->getStatus()) && 'Failed' != $a->getStatus() && 'Retry' != $a->getStatus()) {
            return 1;
        }

        // Compare Status - Paused always comes last
        if ('Paused' == $a->getStatus() && 'Paused' != $b->getStatus()) {
            return 1;
        } elseif ('Paused' == $b->getStatus() && 'Paused' != $a->getStatus()) {
            return -1;
        }

        // Compare by next order date reversed
        $dateResult = (0 - strcmp($a->getNextOrderDate(), $b->getNextOrderDate()));
        if (0 != $dateResult) {
            return $dateResult;
        }

        if(!$a->getShippingAddressId() || !$b->getShippingAddressId()) {
            return 0;
        }
        // Compare by shipping address
        $shippingAddressResult = strcmp($a->getShippingAddressId(), $b->getShippingAddressId());
        if (0 != $shippingAddressResult) {
            return $shippingAddressResult;
        }

        // Otherwise, consider them to match
        return 0;
    }
}
