<?php

namespace SubscribePro\Service\Product;

use SubscribePro\Sdk;
use SubscribePro\Service\AbstractService;
use SubscribePro\Exception\InvalidArgumentException;

class ProductService extends AbstractService
{
    /**
     * Service name
     */
    const NAME = 'product';

    const API_NAME_PRODUCT = 'product';
    const API_NAME_PRODUCTS = 'products';

    /**
     * @param \SubscribePro\Sdk $sdk
     * @return \SubscribePro\Service\DataFactoryInterface
     */
    protected function createDataFactory(Sdk $sdk)
    {
        return new ProductFactory(
            $this->getConfigValue(self::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\Product\Product')
        );
    }

    /**
     * @param array $productData
     * @return \SubscribePro\Service\Product\ProductInterface
     */
    public function createProduct(array $productData = [])
    {
        return $this->dataFactory->create($productData);
    }

    /**
     * @param \SubscribePro\Service\Product\ProductInterface $product
     * @return \SubscribePro\Service\Product\ProductInterface
     * @throws \SubscribePro\Exception\InvalidArgumentException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function saveProduct(ProductInterface $product)
    {
        if (!$product->isValid()) {
            throw new InvalidArgumentException('Not all required fields are set.');
        }

        $url = $product->isNew() ? '/services/v2/product.json' : "/services/v2/products/{$product->getId()}.json";
        $response = $this->httpClient->post($url, [self::API_NAME_PRODUCT => $product->getFormData()]);
        return $this->retrieveItem($response, self::API_NAME_PRODUCT, $product);
    }

    /**
     * @param int $productId
     * @return \SubscribePro\Service\Product\ProductInterface
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadProduct($productId)
    {
        $response = $this->httpClient->get("/services/v2/products/{$productId}.json");
        return $this->retrieveItem($response, self::API_NAME_PRODUCT);
    }

    /**
     * @param string|null $sku
     * @return \SubscribePro\Service\Product\ProductInterface[]
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadProducts($sku = null)
    {
        $filters = !empty($sku) ? [ProductInterface::SKU => $sku] : [];
        $response = $this->httpClient->get('/services/v2/products.json', $filters);
        return $this->retrieveItems($response, self::API_NAME_PRODUCTS);
    }
}
