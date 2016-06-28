<?php

namespace SubscribePro\Tools;

use SubscribePro\Sdk;

class Config
{
    /**
     * Tool name
     */
    const NAME = 'config';

    const API_NAME_CONFIG = 'config';

    const CONFIG_ID = 'id';
    const CONFIG_TRANSPARENT_REDIRECT_ENVIRONMENT_KEY = 'transparent_redirect_environment_key';
    const CONFIG_IS_SANDBOX = 'is_sandbox';
    const CONFIG_SANDBOX_CATCHALL_EMAIL = 'sandbox_catchall_email';
    const CONFIG_MAGENTO_URL = 'magento_url';
    const CONFIG_MAGENTO_ADMIN_URL = 'magento_admin_url';
    const CONFIG_MAGENTO_STORE_CODE = 'magento_store_code';
    const CONFIG_MAGENTO_API_URL = 'magento_api_url';
    const CONFIG_MAGENTO_API_USERNAME = 'magento_api_username';
    const CONFIG_PAYMENT_VAULT = 'payment_vault';
    const CONFIG_ENABLE_TRANSACTION_ROUTING_RULES = 'enable_transaction_routing_rules';
    const CONFIG_MAGENTO_PAYMENT_METHOD = 'magento_payment_method';
    const CONFIG_AUTHORIZE_NET_DUPLICATE_WINDOW = 'authorize_net_duplicate_window';
    const CONFIG_AUTOMATIC_ORDER_GENERATION = 'automatic_order_generation';
    const CONFIG_ORDER_GENERATION_TIME = 'order_generation_time';
    const CONFIG_FULFILLMENT_THRESHOLD_DAYS = 'fulfillment_threshold_days';
    const CONFIG_DEFAULT_MIN_QTY = 'default_min_qty';
    const CONFIG_DEFAULT_MAX_QTY = 'default_max_qty';
    const CONFIG_DEFAULT_DISCOUNT = 'default_discount';
    const CONFIG_DEFAULT_INTERVALS = 'default_intervals';
    const CONFIG_EMAIL_TEMPLATE_TRANSLATE_WITH_MAGENTO_STORE_CODE = 'email_template_translate_with_magento_store_code';
    const CONFIG_FROM_EMAIL_USE_DKIM_DOMAIN = 'from_email_use_dkim_domain';
    const CONFIG_STORE_NAME = 'store_name';
    const CONFIG_EMAIL_TEMPLATE_DISABLE_SUBSCRIPTION_CREATED = 'email_template_disable_subscription_created';
    const CONFIG_EMAIL_TEMPLATE_DISABLE_SUBSCRIPTION_UPDATED = 'email_template_disable_subscription_updated';
    const CONFIG_EMAIL_TEMPLATE_DISABLE_SUBSCRIPTION_CANCELLED = 'email_template_disable_subscription_cancelled';
    const CONFIG_EMAIL_TEMPLATE_DISABLE_SUBSCRIPTION_ORDER_FAILED = 'email_template_disable_subscription_order_failed';
    const CONFIG_EMAIL_TEMPLATE_DISABLE_UPCOMING_SUBSCRIPTION = 'email_template_disable_upcoming_subscription';
    const CONFIG_EMAIL_TEMPLATE_DISABLE_EXPIRING_CARD = 'email_template_disable_expiring_card';
    const CONFIG_UPCOMING_SUBSCRIPTION_EMAIL_DAYS_AHEAD = 'upcoming_subscription_email_days_ahead';
    const CONFIG_UPCOMING_SUBSCRIPTION_EMAIL_TIME = 'upcoming_subscription_email_time';
    const CONFIG_EXPIRING_CARD_EMAIL_DAYS_AHEAD = 'expiring_card_email_days_ahead';
    const CONFIG_EXPIRING_CARD_EMAIL_TIME = 'expiring_card_email_time';
    const CONFIG_TRIAL_CONVERSION_EMAIL_TIME = 'trial_conversion_email_time';
    const CONFIG_ORDER_GENERATION_SHIPPING_METHOD_MODE = 'order_generation_shipping_method_mode';
    const CONFIG_ORDER_GENERATION_FREE_PAYMENT_MODE = 'order_generation_free_payment_mode';
    const CONFIG_ORDER_GENERATION_STORE_MODE = 'order_generation_store_mode';
    const CONFIG_ORDER_GENERATION_API_MODE = 'order_generation_api_mode';
    const CONFIG_ORDER_GENERATION_USE_REWARD_POINTS = 'order_generation_use_reward_points';
    const CONFIG_ORDER_GENERATION_MAX_REWARD_POINTS = 'order_generation_max_reward_points';
    const CONFIG_LOCALE = 'locale';
    const CONFIG_TIMEZONE = 'timezone';
    const CONFIG_CURRENCY = 'currency';
    const CONFIG_CREATED = 'created';
    const CONFIG_UPDATED = 'updated';

    /**
     * @var \SubscribePro\Http
     */
    protected $httpClient;

    /**
     * @param \SubscribePro\Sdk $sdk
     */
    public function __construct(Sdk $sdk)
    {
        $this->httpClient = $sdk->getHttp();
    }

    /**
     * @return array
     * @throws \SubscribePro\Exception\HttpException
     */
    public function load()
    {
        $config = $this->httpClient->get('/services/v2/config.json');
        return isset($config[self::API_NAME_CONFIG]) ? $config[self::API_NAME_CONFIG] : [];
    }
}
