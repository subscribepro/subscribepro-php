<?php

namespace SubscribePro\Tests\Service\Product;

use SubscribePro\Service\Product\ProductInterface;
use SubscribePro\Service\Product\ProductService;

class ProductServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Product\ProductService
     */
    protected $productService;

    /**
     * @var \SubscribePro\Service\DataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productFactoryMock;

    /**
     * @var \SubscribePro\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClientMock;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->getMockBuilder('SubscribePro\Http')
            ->disableOriginalConstructor()
            ->getMock();

        $this->productFactoryMock = $this->getMockBuilder('SubscribePro\Service\DataFactoryInterface')->getMock();

        $this->productService = new ProductService($this->httpClientMock, $this->productFactoryMock);
    }

    public function testCreateProduct()
    {
        $productMock = $this->createProductMock();
        $productData = [ProductInterface::SKU => 'sku'];

        $this->productFactoryMock->expects($this->once())
            ->method('create')
            ->with($productData)
            ->willReturn($productMock);

        $this->assertSame($productMock, $this->productService->createProduct($productData));
    }

    /**
     * @param string $url
     * @param string $itemId
     * @param bool $isNew
     * @param array $formData
     * @param array $resultData
     * @dataProvider saveProductDataProvider
     */
    public function testSaveProduct($url, $itemId, $isNew, $formData, $resultData)
    {
        $productMock = $this->createProductMock();
        $productMock->expects($this->once())->method('isNew')->willReturn($isNew);
        $productMock->expects($this->once())->method('getFormData')->willReturn($formData);
        $productMock->expects($this->any())->method('getId')->willReturn($itemId);
        $productMock->expects($this->once())
            ->method('importData')
            ->with($resultData)
            ->willReturnSelf();

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with($url, [ProductService::API_NAME_PRODUCT => $formData])
            ->willReturn([ProductService::API_NAME_PRODUCT => $resultData]);

        $this->assertSame($productMock, $this->productService->saveProduct($productMock));
    }

    /**
     * @return array
     */
    public function saveProductDataProvider()
    {
        return [
            'Save new product' => [
                'url' => '/services/v2/product.json',
                'itemId' => null,
                'isNew' => true,
                'formData' => [ProductInterface::NAME => 'product name'],
                'resultData' => [ProductInterface::ID => 11],
            ],
            'Update existing product' => [
                'url' => '/services/v2/products/22.json',
                'itemId' => 22,
                'isNew' => false,
                'formData' => [ProductInterface::NAME => 'product name'],
                'resultData' => [ProductInterface::ID => 22],
            ],
        ];
    }

    public function testLoadProduct()
    {
        $itemId = 4123;
        $itemData = [ProductInterface::ID => $itemId];
        $productMock = $this->createProductMock();

        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with("/services/v2/products/{$itemId}.json")
            ->willReturn([ProductService::API_NAME_PRODUCT => $itemData]);

        $this->productFactoryMock->expects($this->once())
            ->method('create')
            ->with($itemData)
            ->willReturn($productMock);

        $this->assertSame($productMock, $this->productService->loadProduct($itemId));
    }

    /**
     * @param int $customerId
     * @param array $filters
     * @param array $itemsData
     * @dataProvider loadProductsDataProvider
     */
    public function testLoadProducts($customerId, $filters, $itemsData)
    {
        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->with('/services/v2/products.json', $filters)
            ->willReturn([ProductService::API_NAME_PRODUCTS => $itemsData]);

        $products = [];
        $productFactoryMap = [];
        foreach ($itemsData as $itemData) {
            $product = $this->createProductMock();
            $productFactoryMap[] = [$itemData, $product];
            $products[] = $product;
        }
        $this->productFactoryMock->expects($this->exactly(count($itemsData)))
            ->method('create')
            ->willReturnMap($productFactoryMap);

        $this->assertSame($products, $this->productService->loadProducts($customerId));
    }

    /**
     * @return array
     */
    public function loadProductsDataProvider()
    {
        return [
            'Loading without filter' => [
                'sku' => null,
                'filters' => [],
                'itemsData' => [[ProductInterface::ID => 111], [ProductInterface::ID => 222]]
            ],
            'Loading by sku' => [
                'sku' => '122-aab',
                'filters' => [ProductInterface::SKU => '122-aab'],
                'itemsData' => [[ProductInterface::SKU => '122-aab', ProductInterface::ID => 333]]
            ],
        ];
    }

    /**
     * @return \SubscribePro\Service\Product\ProductInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createProductMock()
    {
        return $this->getMockBuilder('SubscribePro\Service\Product\ProductInterface')->getMock();
    }
}
