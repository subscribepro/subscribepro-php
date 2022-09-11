<?php

namespace SubscribePro\Service\Product;

use SubscribePro\Service\AbstractService;

/**
 * Config options for product service:
 * - instance_name
 *   Specified class must implement \SubscribePro\Service\Product\ProductInterface interface
 *   Default value is \SubscribePro\Service\Product\Product
 *
 *   @see \SubscribePro\Service\Product\ProductInterface
 *
 * @method \SubscribePro\Service\Product\ProductInterface   retrieveItem($response, $entityName, \SubscribePro\Service\DataInterface $item = null)
 * @method \SubscribePro\Service\Product\ProductInterface[] retrieveItems($response, $entitiesName)
 *
 * @property \SubscribePro\Service\Product\ProductFactory $dataFactory
 */
class ProductService extends AbstractService
{
    /**
     * Service name
     */
    public const NAME = 'product';

    public const API_NAME_PRODUCT = 'product';
    public const API_NAME_PRODUCTS = 'products';

    /**
     * @param array $productData
     *
     * @return \SubscribePro\Service\Product\ProductInterface
     */
    public function createProduct(array $productData = [])
    {
        return $this->dataFactory->create($productData);
    }

    /**
     * @param \SubscribePro\Service\Product\ProductInterface $product
     *
     * @return \SubscribePro\Service\Product\ProductInterface
     *
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function saveProduct(ProductInterface $product)
    {
        $url = $product->isNew() ? '/services/v2/product.json' : "/services/v2/products/{$product->getId()}.json";
        $response = $this->httpClient->post($url, [self::API_NAME_PRODUCT => $product->getFormData()]);

        return $this->retrieveItem($response, self::API_NAME_PRODUCT, $product);
    }

    /**
     * @param int $productId
     *
     * @return \SubscribePro\Service\Product\ProductInterface
     *
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadProduct($productId)
    {
        $response = $this->httpClient->get("/services/v2/products/{$productId}.json");

        return $this->retrieveItem($response, self::API_NAME_PRODUCT);
    }

    /**
     * @param string|null $sku
     *
     * @return \SubscribePro\Service\Product\ProductInterface[]
     *
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadProducts($sku = null)
    {
        $filters = !empty($sku) ? [ProductInterface::SKU => $sku] : [];
        $response = $this->httpClient->get('/services/v2/products.json', $filters);

        return $this->retrieveItems($response, self::API_NAME_PRODUCTS);
    }
}
