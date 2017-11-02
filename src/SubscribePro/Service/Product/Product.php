<?php

namespace SubscribePro\Service\Product;

use SubscribePro\Service\DataObject;
use SubscribePro\Exception\InvalidArgumentException;

class Product extends DataObject implements ProductInterface
{
    /**
     * @var string
     */
    protected $idField = self::ID;

    /**
     * @var array
     */
    protected $creatingFields = [
        self::SKU => true,
        self::NAME => true,
        self::PRICE => true,
        self::SHOW_ON_UI => false,
        self::MIN_QTY => false,
        self::MAX_QTY => false,
        self::DISCOUNT => false,
        self::IS_DISCOUNT_PERCENTAGE => false,
        self::SUBSCRIPTION_OPTION_MODE => false,
        self::DEFAULT_SUBSCRIPTION_OPTION => false,
        self::USE_SCHEDULING_RULE => false,
        self::SCHEDULING_RULE_ID => false,
        self::DEFAULT_INTERVAL => false,
        self::INTERVALS => false,
        self::PRODUCT_OPTIONS_MODE => false,
        self::SHIPPING_MODE => false,
        self::IS_TRIAL_PRODUCT => false,
        self::TRIAL_INTERVAL => false,
        self::TRIAL_PRICE => false,
        self::TRIAL_FULL_PRODUCT_SKU => false,
        self::TRIAL_EMAIL_TEMPLATE_CODE => false,
        self::TRIAL_EMAIL_THRESHOLD_DAYS => false,
        self::TRIAL_WELCOME_EMAIL_TEMPLATE_CODE => false,
        self::THUMBNAIL_URL => false,
        self::MSRP => false,
        self::SALE_PRICE => false,
        self::IS_ON_SALE => false,
        self::QTY_IN_STOCK => false,
        self::IS_IN_STOCK => false,
    ];

    /**
     * @var array
     */
    protected $updatingFields = [
        self::SKU => false,
        self::NAME => false,
        self::PRICE => false,
        self::SHOW_ON_UI => false,
        self::MIN_QTY => false,
        self::MAX_QTY => false,
        self::DISCOUNT => false,
        self::IS_DISCOUNT_PERCENTAGE => false,
        self::SUBSCRIPTION_OPTION_MODE => false,
        self::DEFAULT_SUBSCRIPTION_OPTION => false,
        self::USE_SCHEDULING_RULE => false,
        self::SCHEDULING_RULE_ID => false,
        self::DEFAULT_INTERVAL => false,
        self::INTERVALS => false,
        self::SHIPPING_MODE => false,
        self::IS_TRIAL_PRODUCT => false,
        self::TRIAL_INTERVAL => false,
        self::TRIAL_PRICE => false,
        self::TRIAL_FULL_PRODUCT_SKU => false,
        self::TRIAL_EMAIL_TEMPLATE_CODE => false,
        self::TRIAL_EMAIL_THRESHOLD_DAYS => false,
        self::TRIAL_WELCOME_EMAIL_TEMPLATE_CODE => false,
        self::THUMBNAIL_URL => false,
        self::MSRP => false,
        self::SALE_PRICE => false,
        self::IS_ON_SALE => false,
        self::QTY_IN_STOCK => false,
        self::IS_IN_STOCK => false,
    ];

    /**
     * Subscription Option Modes
     *
     * @var array
     */
    protected $subscriptionOptionModes = [
        ProductInterface::SOM_SUBSCRIPTION_ONLY,
        ProductInterface::SOM_SUBSCRIPTION_AND_ONETIME_PURCHASE
    ];

    /**
     * Subscription options
     *
     * @var array
     */
    protected $subscriptionOptions = [
        ProductInterface::SO_SUBSCRIPTION,
        ProductInterface::SO_ONETIME_PURCHASE
    ];

    /**
     * Product Options Modes
     *
     * @var array
     */
    protected $productOptionsModes = [
        ProductInterface::POM_PASS_THROUGH,
        ProductInterface::POM_NO_OPTIONS
    ];

    /**
     * @return mixed[]
     */
    protected function getFormFields()
    {
        return $this->isNew() ? $this->creatingFields : $this->updatingFields;
    }

    /**
     * @return mixed[]
     */
    public function getFormData()
    {
        return array_intersect_key($this->data, $this->getFormFields());
    }

    //@codeCoverageIgnoreStart

    /**
     * @param int|null $id
     * @return $this
     */
    public function setId($id)
    {
        $this->setData($this->idField, $id);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSku()
    {
        return $this->getData(self::SKU);
    }

    /**
     * @param string|null $sku
     * @return $this
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @return bool|null
     */
    public function getShowOnUi()
    {
        return $this->getData(self::SHOW_ON_UI);
    }

    /**
     * @param bool|null $showOnUi
     * @return $this
     */
    public function setShowOnUi($showOnUi)
    {
        return $this->setData(self::SHOW_ON_UI, $showOnUi);
    }

    /**
     * @return int|null
     */
    public function getMinQty()
    {
        return $this->getData(self::MIN_QTY);
    }

    /**
     * @param int|null $minQty
     * @return $this
     */
    public function setMinQty($minQty)
    {
        return $this->setData(self::MIN_QTY, $minQty);
    }

    /**
     * @return int|null
     */
    public function getMaxQty()
    {
        return $this->getData(self::MAX_QTY);
    }

    /**
     * @param int|null $maxQty
     * @return $this
     */
    public function setMaxQty($maxQty)
    {
        return $this->setData(self::MAX_QTY, $maxQty);
    }

    /**
     * @return float|null
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * @param float|null $price
     * @return $this
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * @return float|null
     */
    public function getDiscount()
    {
        return $this->getData(self::DISCOUNT);
    }

    /**
     * @param float|null $discount
     * @return $this
     */
    public function setDiscount($discount)
    {
        return $this->setData(self::DISCOUNT, $discount);
    }

    /**
     * @return bool|null
     */
    public function getIsDiscountPercentage()
    {
        return $this->getData(self::IS_DISCOUNT_PERCENTAGE);
    }

    /**
     * @param bool|null $isDiscountPercentage
     * @return $this
     */
    public function setIsDiscountPercentage($isDiscountPercentage)
    {
        return $this->setData(self::IS_DISCOUNT_PERCENTAGE, $isDiscountPercentage);
    }

    /**
     * Subscription Option Mode: 'subscription_only' or 'subscription_and_onetime_purchase'
     *
     * @return string|null
     */
    public function getSubscriptionOptionMode()
    {
        return $this->getData(self::SUBSCRIPTION_OPTION_MODE);
    }

    /**
     * Subscription Option Mode: 'subscription_only' or 'subscription_and_onetime_purchase'
     *
     * @param string $subscriptionOptionMode
     * @return $this
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setSubscriptionOptionMode($subscriptionOptionMode)
    {
        if (!in_array($subscriptionOptionMode, $this->subscriptionOptionModes)) {
            throw new InvalidArgumentException('Unsupported subscription option mode.');
        }
        return $this->setData(self::SUBSCRIPTION_OPTION_MODE, $subscriptionOptionMode);
    }

    /**
     * Default subscription option: 'subscription' or 'onetime_purchase'
     *
     * @return string|null
     */
    public function getDefaultSubscriptionOption()
    {
        return $this->getData(self::DEFAULT_SUBSCRIPTION_OPTION);
    }

    /**
     * Subscription option: 'subscription' or 'onetime_purchase'
     *
     * @param string $defaultSubscriptionOption
     * @return $this
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setDefaultSubscriptionOption($defaultSubscriptionOption)
    {
        if (!in_array($defaultSubscriptionOption, $this->subscriptionOptions)) {
            throw new InvalidArgumentException('Unsupported subscription option.');
        }
        return $this->setData(self::DEFAULT_SUBSCRIPTION_OPTION, $defaultSubscriptionOption);
    }

	/**
	 * @return bool|null
	 */
	public function getUseSchedulingRule()
	{
		return $this->getData(self::USE_SCHEDULING_RULE);
	}

	/**
	 * @param bool|null $useSchedulingRule
	 * @return $this
	 */
	public function setUseSchedulingRule($useSchedulingRule)
	{
		return $this->setData(self::USE_SCHEDULING_RULE, $useSchedulingRule);
	}

	/**
	 * @return string|null
	 */
	public function getSchedulingRuleId()
	{
		return $this->getData(self::SCHEDULING_RULE_ID);
	}

	/**
	 * @param string|null $schedulingRuleId
	 * @return $this
	 */
	public function setSchedulingRuleId($schedulingRuleId)
	{
		return $this->setData(self::SCHEDULING_RULE_ID, $schedulingRuleId);
	}

	/**
     * Default subscription option: 'subscription' or 'onetime_purchase'
     *
     * @return string|null
     */
    public function getDefaultInterval()
    {
        return $this->getData(self::DEFAULT_INTERVAL);
    }

    /**
     * @param string|null $defaultInterval
     * @return $this
     */
    public function setDefaultInterval($defaultInterval)
    {
        return $this->setData(self::DEFAULT_INTERVAL, $defaultInterval);
    }

    /**
     * @return mixed[]|null
     */
    public function getIntervals()
    {
        return $this->getData(self::INTERVALS);
    }

    /**
     * @param array|null $intervals
     * @return $this
     */
    public function setIntervals($intervals)
    {
        return $this->setData(self::INTERVALS, $intervals);
    }

    /**
     * Product options mode: 'pass_through' or 'no_options'
     *
     * @return string|null
     */
    public function getProductOptionsMode()
    {
        return $this->getData(self::PRODUCT_OPTIONS_MODE);
    }

    /**
     * Product options mode: 'pass_through' or 'no_options'
     *
     * @param string $productOptionsMode
     * @return $this
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function setProductOptionsMode($productOptionsMode)
    {
        if (!in_array($productOptionsMode, $this->productOptionsModes)) {
            throw new InvalidArgumentException('Unsupported product options mode.');
        }
        return $this->setData(self::PRODUCT_OPTIONS_MODE, $productOptionsMode);
    }

    /**
     * @return string|null
     */
    public function getShippingMode()
    {
        return $this->getData(self::SHIPPING_MODE);
    }

    /**
     * @param string|null $shippingMode
     * @return $this
     */
    public function setShippingMode($shippingMode)
    {
        return $this->setData(self::SHIPPING_MODE, $shippingMode);
    }

    /**
     * @return bool|null
     */
    public function getIsTrialProduct()
    {
        return $this->getData(self::IS_TRIAL_PRODUCT);
    }

    /**
     * @param bool|null $isTrialProduct
     * @return $this
     */
    public function setIsTrialProduct($isTrialProduct)
    {
        return $this->setData(self::IS_TRIAL_PRODUCT, $isTrialProduct);
    }

    /**
     * @return string|null
     */
    public function getTrialInterval()
    {
        return $this->getData(self::TRIAL_INTERVAL);
    }

    /**
     * @param string|null $trialInterval
     * @return $this
     */
    public function setTrialInterval($trialInterval)
    {
        return $this->setData(self::TRIAL_INTERVAL, $trialInterval);
    }

    /**
     * @return float|null
     */
    public function getTrialPrice()
    {
        return $this->getData(self::TRIAL_PRICE);
    }

    /**
     * @param float|null $trialPrice
     * @return $this
     */
    public function setTrialPrice($trialPrice)
    {
        return $this->setData(self::TRIAL_PRICE, $trialPrice);
    }

    /**
     * @return string|null
     */
    public function getTrialFullProductSku()
    {
        return $this->getData(self::TRIAL_FULL_PRODUCT_SKU);
    }

    /**
     * @param string|null $trialFullProductSku
     * @return $this
     */
    public function setTrialFullProductSku($trialFullProductSku)
    {
        return $this->setData(self::TRIAL_FULL_PRODUCT_SKU, $trialFullProductSku);
    }

    /**
     * @return string|null
     */
    public function getTrialEmailTemplateCode()
    {
        return $this->getData(self::TRIAL_EMAIL_TEMPLATE_CODE);
    }

    /**
     * @param string|null $trialEmailTemplateCode
     * @return $this
     */
    public function setTrialEmailTemplateCode($trialEmailTemplateCode)
    {
        return $this->setData(self::TRIAL_EMAIL_TEMPLATE_CODE, $trialEmailTemplateCode);
    }

    /**
     * @return int|null
     */
    public function getTrialEmailThresholdDays()
    {
        return $this->getData(self::TRIAL_EMAIL_THRESHOLD_DAYS);
    }

    /**
     * @param int|null $trialEmailThresholdDays
     * @return $this
     */
    public function setTrialEmailThresholdDays($trialEmailThresholdDays)
    {
        return $this->setData(self::TRIAL_EMAIL_THRESHOLD_DAYS, $trialEmailThresholdDays);
    }

    /**
     * @return string|null
     */
    public function getTrialWelcomeEmailTemplateCode()
    {
        return $this->getData(self::TRIAL_WELCOME_EMAIL_TEMPLATE_CODE);
    }

    /**
     * @param string|null $trialWelcomeEmailTemplateCode
     * @return $this
     */
    public function setTrialWelcomeEmailTemplateCode($trialWelcomeEmailTemplateCode)
    {
        return $this->setData(self::TRIAL_WELCOME_EMAIL_TEMPLATE_CODE, $trialWelcomeEmailTemplateCode);
    }

    /**
     * @return bool
     */
    public function getIsSubscriptionEnabled()
    {
        return $this->getData(self::IS_SUBSCRIPTION_ENABLED);
    }

    /**
     * @return string
     */
    public function getThumbnailUrl()
    {
        return $this->getData(self::THUMBNAIL_URL);
    }

    /**
     * @param string|null $thumbnailUrl
     * @return $this
     */
    public function setThumbnailUrl($thumbnailUrl)
    {
        return $this->setData(self::THUMBNAIL_URL, $thumbnailUrl);
    }

    /**
     * @return float
     */
    public function getMSRP()
    {
        return $this->getData(self::MSRP);
    }

    /**
     * @param float|null $msrp
     * @return $this
     */
    public function setMSRP($msrp)
    {
        return $this->setData(self::MSRP, $msrp);
    }

    /**
     * @return float
     */
    public function getSalePrice()
    {
        return $this->getData(self::SALE_PRICE);
    }

    /**
     * @param float|null $sale_price
     * @return $this
     */
    public function setSalePrice($sale_price)
    {
        return $this->getData(self::SALE_PRICE);
    }

    /**
     * @return boolean
     */
    public function getIsOnSale()
    {
        return $this->getData(self::IS_ON_SALE);
    }

    /**
     * @param boolean|null $is_on_sale
     * @return $this
     */
    public function setIsOnSale($is_on_sale)
    {
        return $this->setData(self::IS_ON_SALE, $is_on_sale);
    }

    /**
     * @return integer
     */
    public function getQtyInStock()
    {
        return $this->getData(self::THUMBNAIL_URL);
    }

    /**
     * @param integer|null $qty_in_stock
     * @return $this
     */
    public function setQtyInStock($qty_in_stock)
    {
        return $this->setData(self::QTY_IN_STOCK, $qty_in_stock);
    }

    /**
     * @return boolean
     */
    public function getIsInStock()
    {
        return $this->getData(self::IS_IN_STOCK);
    }

    /**
     * @param boolean|null $is_in_stock
     * @return $this
     */
    public function setIsInStock($is_in_stock)
    {
        return $this->setData(self::IS_IN_STOCK, $is_in_stock);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getCreated($format = null)
    {
        return $this->getDatetimeData(self::CREATED, $format);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getUpdated($format = null)
    {
        return $this->getDatetimeData(self::UPDATED, $format);
    }

    //@codeCoverageIgnoreEnd
}
