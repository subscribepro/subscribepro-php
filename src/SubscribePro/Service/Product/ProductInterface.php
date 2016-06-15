<?php

namespace SubscribePro\Service\Product;

use SubscribePro\Service\DataInterface;

interface ProductInterface extends DataInterface
{
    /**
     * Data fields
     */
    const ID = 'id';
    const SKU = 'sku';
    const NAME = 'name';
    const SHOW_ON_UI = 'show_on_ui';
    const MIN_QTY = 'min_qty';
    const MAX_QTY = 'max_qty';
    const PRICE = 'price';
    const DISCOUNT = 'discount';
    const IS_DISCOUNT_PERCENTAGE = 'is_discount_percentage';
    const SUBSCRIPTION_OPTION_MODE = 'subscription_option_mode';
    const DEFAULT_SUBSCRIPTION_OPTION = 'default_subscription_option';
    const DEFAULT_INTERVAL = 'default_interval';
    const INTERVALS = 'intervals';
    const PRODUCT_OPTIONS_MODE = 'product_options_mode';
    const IS_TRIAL_PRODUCT = 'is_trial_product';
    const TRIAL_INTERVAL = 'trial_interval';
    const TRIAL_PRICE = 'trial_price';
    const TRIAL_FULL_PRODUCT_SKU = 'trial_full_product_sku';
    const TRIAL_EMAIL_TEMPLATE_CODE = 'trial_email_template_code';
    const TRIAL_EMAIL_THRESHOLD_DAYS = 'trial_email_threshold_days';
    const TRIAL_WELCOME_EMAIL_TEMPLATE_CODE = 'trial_welcome_email_template_code';
    const IS_SUBSCRIPTION_ENABLED = 'is_subscription_enabled';
    const CREATED = 'created';
    const UPDATED = 'updated';

    /**
     * Subscription option modes
     */
    const SOM_SUBSCRIPTION_AND_ONETIME_PURCHASE = 'subscription_and_onetime_purchase';
    const SOM_SUBSCRIPTION_ONLY = 'subscription_only';

    /**
     * Subscription options
     */
    const SO_ONETIME_PURCHASE = 'onetime_purchase';
    const SO_SUBSCRIPTION = 'subscription';

    /**
     * Product options modes
     */
    const POM_PASS_THROUGH = 'pass_through';
    const POM_NO_OPTIONS = 'no_options';


    /**
     * @return array
     */
    public function getFormData();

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @param int|null $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string|null
     */
    public function getSku();

    /**
     * @param string|null $sku
     * @return $this
     */
    public function setSku($sku);

    /**
     * @return string|null
     */
    public function getName();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return bool|null
     */
    public function getShowOnUi();

    /**
     * @param bool|null $showOnUi
     * @return $this
     */
    public function setShowOnUi($showOnUi);

    /**
     * @return int|null
     */
    public function getMinQty();

    /**
     * @param int|null $minQty
     * @return $this
     */
    public function setMinQty($minQty);

    /**
     * @return int|null
     */
    public function getMaxQty();

    /**
     * @param int|null $maxQty
     * @return $this
     */
    public function setMaxQty($maxQty);

    /**
     * @return float|null
     */
    public function getPrice();

    /**
     * @param float|null $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * @return float|null
     */
    public function getDiscount();

    /**
     * @param float|null $discount
     * @return $this
     */
    public function setDiscount($discount);

    /**
     * @return bool|null
     */
    public function getIsDiscountPercentage();

    /**
     * @param bool|null $isDiscountPercentage
     * @return $this
     */
    public function setIsDiscountPercentage($isDiscountPercentage);

    /**
     * @return string|null
     */
    public function getSubscriptionOptionMode();

    /**
     * @param string $subscriptionOptionMode
     * @return $this
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setSubscriptionOptionMode($subscriptionOptionMode);

    /**
     * @return string|null
     */
    public function getDefaultSubscriptionOption();

    /**
     * @param string $defaultSubscriptionOption
     * @return $this
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setDefaultSubscriptionOption($defaultSubscriptionOption);

    /**
     * @return string|null
     */
    public function getDefaultInterval();

    /**
     * @param string|null $defaultInterval
     * @return $this
     */
    public function setDefaultInterval($defaultInterval);

    /**
     * @return array|null
     */
    public function getIntervals();

    /**
     * @param array|null $intervals
     * @return $this
     */
    public function setIntervals($intervals);

    /**
     * @return string|null
     */
    public function getProductOptionsMode();

    /**
     * @param string $productOptionsMode
     * @return $this
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setProductOptionsMode($productOptionsMode);

    /**
     * @return bool|null
     */
    public function getIsTrialProduct();

    /**
     * @param bool|null $isTrialProduct
     * @return $this
     */
    public function setIsTrialProduct($isTrialProduct);

    /**
     * @return string|null
     */
    public function getTrialInterval();

    /**
     * @param string|null $trialInterval
     * @return $this
     */
    public function setTrialInterval($trialInterval);

    /**
     * @return float|null
     */
    public function getTrialPrice();

    /**
     * @param float|null $trialPrice
     * @return $this
     */
    public function setTrialPrice($trialPrice);

    /**
     * @return string|null
     */
    public function getTrialFullProductSku();

    /**
     * @param string|null $trialFullProductSku
     * @return $this
     */
    public function setTrialFullProductSku($trialFullProductSku);

    /**
     * @return string|null
     */
    public function getTrialEmailTemplateCode();

    /**
     * @param string|null $trialEmailTemplateCode
     * @return $this
     */
    public function setTrialEmailTemplateCode($trialEmailTemplateCode);

    /**
     * @return int|null
     */
    public function getTrialEmailThresholdDays();

    /**
     * @param int|null $trialEmailThresholdDays
     * @return $this
     */
    public function setTrialEmailThresholdDays($trialEmailThresholdDays);

    /**
     * @return string|null
     */
    public function getTrialWelcomeEmailTemplateCode();

    /**
     * @param string|null $trialWelcomeEmailTemplateCode
     * @return $this
     */
    public function setTrialWelcomeEmailTemplateCode($trialWelcomeEmailTemplateCode);

    /**
     * @return bool
     */
    public function getIsSubscriptionEnabled();

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getCreated($format = null);

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getUpdated($format = null);
}
