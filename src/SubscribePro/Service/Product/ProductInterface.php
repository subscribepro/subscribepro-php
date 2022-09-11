<?php

namespace SubscribePro\Service\Product;

use SubscribePro\Service\DataInterface;

interface ProductInterface extends DataInterface
{
    /**
     * Data fields
     */
    public const ID = 'id';
    public const SKU = 'sku';
    public const NAME = 'name';
    public const SHOW_ON_UI = 'show_on_ui';
    public const MIN_QTY = 'min_qty';
    public const MAX_QTY = 'max_qty';
    public const PRICE = 'price';
    public const DISCOUNT = 'discount';
    public const IS_DISCOUNT_PERCENTAGE = 'is_discount_percentage';
    public const SUBSCRIPTION_OPTION_MODE = 'subscription_option_mode';
    public const DEFAULT_SUBSCRIPTION_OPTION = 'default_subscription_option';
    public const USE_SCHEDULING_RULE = 'use_scheduling_rule';
    public const SCHEDULING_RULE_ID = 'scheduling_rule_id';
    public const DEFAULT_INTERVAL = 'default_interval';
    public const INTERVALS = 'intervals';
    public const PRODUCT_OPTIONS_MODE = 'product_options_mode';
    public const SHIPPING_MODE = 'shipping_mode';
    public const IS_TRIAL_PRODUCT = 'is_trial_product';
    public const TRIAL_INTERVAL = 'trial_interval';
    public const TRIAL_PRICE = 'trial_price';
    public const TRIAL_FULL_PRODUCT_SKU = 'trial_full_product_sku';
    public const TRIAL_EMAIL_TEMPLATE_CODE = 'trial_email_template_code';
    public const TRIAL_EMAIL_THRESHOLD_DAYS = 'trial_email_threshold_days';
    public const TRIAL_WELCOME_EMAIL_TEMPLATE_CODE = 'trial_welcome_email_template_code';
    public const IS_SUBSCRIPTION_ENABLED = 'is_subscription_enabled';
    public const THUMBNAIL_URL = 'thumbnail_url';
    public const MSRP = 'msrp';
    public const SALE_PRICE = 'sale_price';
    public const IS_ON_SALE = 'is_on_sale';
    public const QTY_IN_STOCK = 'qty_in_stock';
    public const IS_IN_STOCK = 'is_in_stock';
    public const CREATED = 'created';
    public const UPDATED = 'updated';

    /**
     * Subscription option modes
     */
    public const SOM_SUBSCRIPTION_AND_ONETIME_PURCHASE = 'subscription_and_onetime_purchase';
    public const SOM_SUBSCRIPTION_ONLY = 'subscription_only';

    /**
     * Subscription options
     */
    public const SO_ONETIME_PURCHASE = 'onetime_purchase';
    public const SO_SUBSCRIPTION = 'subscription';

    /**
     * Product options modes
     */
    public const POM_PASS_THROUGH = 'pass_through';
    public const POM_NO_OPTIONS = 'no_options';

    /**
     * @return mixed[]
     */
    public function getFormData();

    /**
     * @param int|null $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * @return string|null
     */
    public function getSku();

    /**
     * @param string|null $sku
     *
     * @return $this
     */
    public function setSku($sku);

    /**
     * @return string|null
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * @return bool|null
     */
    public function getShowOnUi();

    /**
     * @param bool|null $showOnUi
     *
     * @return $this
     */
    public function setShowOnUi($showOnUi);

    /**
     * @return int|null
     */
    public function getMinQty();

    /**
     * @param int|null $minQty
     *
     * @return $this
     */
    public function setMinQty($minQty);

    /**
     * @return int|null
     */
    public function getMaxQty();

    /**
     * @param int|null $maxQty
     *
     * @return $this
     */
    public function setMaxQty($maxQty);

    /**
     * @return float|null
     */
    public function getPrice();

    /**
     * @param float|null $price
     *
     * @return $this
     */
    public function setPrice($price);

    /**
     * @return float|null
     */
    public function getDiscount();

    /**
     * @param float|null $discount
     *
     * @return $this
     */
    public function setDiscount($discount);

    /**
     * @return bool|null
     */
    public function getIsDiscountPercentage();

    /**
     * @param bool|null $isDiscountPercentage
     *
     * @return $this
     */
    public function setIsDiscountPercentage($isDiscountPercentage);

    /**
     * @return string|null
     */
    public function getSubscriptionOptionMode();

    /**
     * @param string $subscriptionOptionMode
     *
     * @return $this
     *
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setSubscriptionOptionMode($subscriptionOptionMode);

    /**
     * @return string|null
     */
    public function getDefaultSubscriptionOption();

    /**
     * @param string $defaultSubscriptionOption
     *
     * @return $this
     *
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setDefaultSubscriptionOption($defaultSubscriptionOption);

    /**
     * @return bool|null
     */
    public function getUseSchedulingRule();

    /**
     * @param bool|null $useSchedulingRule
     *
     * @return $this
     */
    public function setUseSchedulingRule($useSchedulingRule);

    /**
     * @return string|null
     */
    public function getSchedulingRuleId();

    /**
     * @param string|null $schedulingRuleId
     *
     * @return $this
     */
    public function setSchedulingRuleId($schedulingRuleId);

    /**
     * @return string|null
     */
    public function getDefaultInterval();

    /**
     * @param string|null $defaultInterval
     *
     * @return $this
     */
    public function setDefaultInterval($defaultInterval);

    /**
     * @return mixed[]|null
     */
    public function getIntervals();

    /**
     * @param array|null $intervals
     *
     * @return $this
     */
    public function setIntervals($intervals);

    /**
     * @return string|null
     */
    public function getProductOptionsMode();

    /**
     * @param string $productOptionsMode
     *
     * @return $this
     *
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setProductOptionsMode($productOptionsMode);

    /**
     * @return string|null
     */
    public function getShippingMode();

    /**
     * @param string|null $shippingMode
     *
     * @return $this
     */
    public function setShippingMode($shippingMode);

    /**
     * @return bool|null
     */
    public function getIsTrialProduct();

    /**
     * @param bool|null $isTrialProduct
     *
     * @return $this
     */
    public function setIsTrialProduct($isTrialProduct);

    /**
     * @return string|null
     */
    public function getTrialInterval();

    /**
     * @param string|null $trialInterval
     *
     * @return $this
     */
    public function setTrialInterval($trialInterval);

    /**
     * @return float|null
     */
    public function getTrialPrice();

    /**
     * @param float|null $trialPrice
     *
     * @return $this
     */
    public function setTrialPrice($trialPrice);

    /**
     * @return string|null
     */
    public function getTrialFullProductSku();

    /**
     * @param string|null $trialFullProductSku
     *
     * @return $this
     */
    public function setTrialFullProductSku($trialFullProductSku);

    /**
     * @return string|null
     */
    public function getTrialEmailTemplateCode();

    /**
     * @param string|null $trialEmailTemplateCode
     *
     * @return $this
     */
    public function setTrialEmailTemplateCode($trialEmailTemplateCode);

    /**
     * @return int|null
     */
    public function getTrialEmailThresholdDays();

    /**
     * @param int|null $trialEmailThresholdDays
     *
     * @return $this
     */
    public function setTrialEmailThresholdDays($trialEmailThresholdDays);

    /**
     * @return string|null
     */
    public function getTrialWelcomeEmailTemplateCode();

    /**
     * @param string|null $trialWelcomeEmailTemplateCode
     *
     * @return $this
     */
    public function setTrialWelcomeEmailTemplateCode($trialWelcomeEmailTemplateCode);

    /**
     * @return bool
     */
    public function getIsSubscriptionEnabled();

    /**
     * @return string
     */
    public function getThumbnailUrl();

    /**
     * @param string|null $thumbnailUrl
     *
     * @return $this
     */
    public function setThumbnailUrl($thumbnailUrl);

    /**
     * @return float
     */
    public function getMSRP();

    /**
     * @param float|null $msrp
     *
     * @return $this
     */
    public function setMSRP($msrp);

    /**
     * @return float
     */
    public function getSalePrice();

    /**
     * @param float|null $sale_price
     *
     * @return $this
     */
    public function setSalePrice($sale_price);

    /**
     * @return bool
     */
    public function getIsOnSale();

    /**
     * @param bool|null $is_on_sale
     *
     * @return $this
     */
    public function setIsOnSale($is_on_sale);

    /**
     * @return int
     */
    public function getQtyInStock();

    /**
     * @param int|null $qty_in_stock
     *
     * @return $this
     */
    public function setQtyInStock($qty_in_stock);

    /**
     * @return bool
     */
    public function getIsInStock();

    /**
     * @param bool|null $is_in_stock
     *
     * @return $this
     */
    public function setIsInStock($is_in_stock);

    /**
     * @param string|null $format
     *
     * @return string|null
     */
    public function getCreated($format = null);

    /**
     * @param string|null $format
     *
     * @return string|null
     */
    public function getUpdated($format = null);
}
