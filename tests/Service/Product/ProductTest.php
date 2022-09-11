<?php

namespace SubscribePro\Tests\Service\Product;

use SubscribePro\Service\Product\Product;
use SubscribePro\Service\Product\ProductInterface;

class ProductTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Product\ProductInterface
     */
    protected $product;

    protected function setUp(): void
    {
        $this->product = new Product();
    }

    /**
     * @param array $data
     * @param array $expectedData
     * @dataProvider getFormDataProvider
     */
    public function testGetFormData($data, $expectedData)
    {
        $this->product->importData($data);
        $this->assertEquals($expectedData, $this->product->getFormData());
    }

    /**
     * @return array
     */
    public function getFormDataProvider()
    {
        return [
            'New product' => [
                'data' => [
                    ProductInterface::SKU => 'sku',
                    ProductInterface::NAME => 'name',
                    ProductInterface::PRICE => 222,
                    ProductInterface::SHOW_ON_UI => false,
                    ProductInterface::MIN_QTY => '1',
                    ProductInterface::MAX_QTY => '22',
                    ProductInterface::DISCOUNT => 11,
                    ProductInterface::IS_DISCOUNT_PERCENTAGE => false,
                    ProductInterface::SUBSCRIPTION_OPTION_MODE => 'subscription_only',
                    ProductInterface::DEFAULT_SUBSCRIPTION_OPTION => 'subscribe',
                    ProductInterface::DEFAULT_INTERVAL => 'monthly',
                    ProductInterface::INTERVALS => [],
                    ProductInterface::PRODUCT_OPTIONS_MODE => 'mode',
                    ProductInterface::IS_TRIAL_PRODUCT => true,
                    ProductInterface::TRIAL_INTERVAL => 3,
                    ProductInterface::TRIAL_PRICE => 123,
                    ProductInterface::TRIAL_FULL_PRODUCT_SKU => 'sku',
                    ProductInterface::TRIAL_EMAIL_TEMPLATE_CODE => 'code',
                    ProductInterface::TRIAL_EMAIL_THRESHOLD_DAYS => 'days',
                    ProductInterface::TRIAL_WELCOME_EMAIL_TEMPLATE_CODE => 'welcome_code',
                    ProductInterface::IS_SUBSCRIPTION_ENABLED => true,
                    ProductInterface::CREATED => '2016-12-12',
                    ProductInterface::UPDATED => '2016-12-12',
                ],
                'expectedData' => [
                    ProductInterface::SKU => 'sku',
                    ProductInterface::NAME => 'name',
                    ProductInterface::SHOW_ON_UI => false,
                    ProductInterface::MIN_QTY => '1',
                    ProductInterface::MAX_QTY => '22',
                    ProductInterface::PRICE => 222,
                    ProductInterface::DISCOUNT => 11,
                    ProductInterface::IS_DISCOUNT_PERCENTAGE => false,
                    ProductInterface::SUBSCRIPTION_OPTION_MODE => 'subscription_only',
                    ProductInterface::DEFAULT_SUBSCRIPTION_OPTION => 'subscribe',
                    ProductInterface::DEFAULT_INTERVAL => 'monthly',
                    ProductInterface::INTERVALS => [],
                    ProductInterface::PRODUCT_OPTIONS_MODE => 'mode',
                    ProductInterface::IS_TRIAL_PRODUCT => true,
                    ProductInterface::TRIAL_INTERVAL => 3,
                    ProductInterface::TRIAL_PRICE => 123,
                    ProductInterface::TRIAL_FULL_PRODUCT_SKU => 'sku',
                    ProductInterface::TRIAL_EMAIL_TEMPLATE_CODE => 'code',
                    ProductInterface::TRIAL_EMAIL_THRESHOLD_DAYS => 'days',
                    ProductInterface::TRIAL_WELCOME_EMAIL_TEMPLATE_CODE => 'welcome_code',
                ],
            ],
            'Not new product' => [
                'data' => [
                    ProductInterface::ID => 333,
                    ProductInterface::SKU => 'sku',
                    ProductInterface::NAME => 'name',
                    ProductInterface::PRICE => 222,
                    ProductInterface::SHOW_ON_UI => false,
                    ProductInterface::MIN_QTY => '1',
                    ProductInterface::MAX_QTY => '22',
                    ProductInterface::DISCOUNT => 11,
                    ProductInterface::IS_DISCOUNT_PERCENTAGE => false,
                    ProductInterface::SUBSCRIPTION_OPTION_MODE => 'subscription_only',
                    ProductInterface::DEFAULT_SUBSCRIPTION_OPTION => 'subscribe',
                    ProductInterface::DEFAULT_INTERVAL => 'monthly',
                    ProductInterface::INTERVALS => [],
                    ProductInterface::PRODUCT_OPTIONS_MODE => 'mode',
                    ProductInterface::IS_TRIAL_PRODUCT => true,
                    ProductInterface::TRIAL_INTERVAL => 3,
                    ProductInterface::TRIAL_PRICE => 123,
                    ProductInterface::TRIAL_FULL_PRODUCT_SKU => 'sku',
                    ProductInterface::TRIAL_EMAIL_TEMPLATE_CODE => 'code',
                    ProductInterface::TRIAL_EMAIL_THRESHOLD_DAYS => 'days',
                    ProductInterface::TRIAL_WELCOME_EMAIL_TEMPLATE_CODE => 'welcome_code',
                    ProductInterface::IS_SUBSCRIPTION_ENABLED => true,
                    ProductInterface::CREATED => '2016-12-12',
                    ProductInterface::UPDATED => '2016-12-12',
                ],
                'expectedData' => [
                    ProductInterface::SKU => 'sku',
                    ProductInterface::NAME => 'name',
                    ProductInterface::SHOW_ON_UI => false,
                    ProductInterface::MIN_QTY => '1',
                    ProductInterface::MAX_QTY => '22',
                    ProductInterface::PRICE => 222,
                    ProductInterface::DISCOUNT => 11,
                    ProductInterface::IS_DISCOUNT_PERCENTAGE => false,
                    ProductInterface::SUBSCRIPTION_OPTION_MODE => 'subscription_only',
                    ProductInterface::DEFAULT_SUBSCRIPTION_OPTION => 'subscribe',
                    ProductInterface::DEFAULT_INTERVAL => 'monthly',
                    ProductInterface::INTERVALS => [],
                    ProductInterface::IS_TRIAL_PRODUCT => true,
                    ProductInterface::TRIAL_INTERVAL => 3,
                    ProductInterface::TRIAL_PRICE => 123,
                    ProductInterface::TRIAL_FULL_PRODUCT_SKU => 'sku',
                    ProductInterface::TRIAL_EMAIL_TEMPLATE_CODE => 'code',
                    ProductInterface::TRIAL_EMAIL_THRESHOLD_DAYS => 'days',
                    ProductInterface::TRIAL_WELCOME_EMAIL_TEMPLATE_CODE => 'welcome_code',
                ],
            ],
        ];
    }
}
