<?php
use PHPUnit\Framework\TestCase;
use BitrixMock\Repository\ProductRepository;
use BitrixMock\Contracts\IBlockElementInterface;
use BitrixMock\Contracts\CatalogProductInterface;
use BitrixMock\Contracts\DBResultInterface;

class ProductRepositoryTest extends TestCase
{
    private function makeDbResult(array $rows): DBResultInterface
    {
        $mock = $this->createMock(DBResultInterface::class);
        $returns = array_merge($rows, [false]);
        $mock->method('Fetch')->willReturnOnConsecutiveCalls(...$returns);
        return $mock;
    }

    public function test_get_all_merges_product_and_stock_data(): void
    {
        $dbResult = $this->makeDbResult([
            ['ID' => 1, 'NAME' => 'Товар А', 'PROPERTY_ARTICLE_VALUE' => 'ART-001', 'CATALOG_PRICE_1' => 100.0],
        ]);

        $blockElement = $this->createMock(IBlockElementInterface::class);
        $blockElement->method('GetList')->willReturn($dbResult);

        $catalog = $this->createMock(CatalogProductInterface::class);
        $catalog->method('GetByID')->with(1)->willReturn(['QUANTITY' => 5]);

        $repo = new ProductRepository($blockElement, $catalog);
        $result = $repo->getAll();

        $this->assertCount(1, $result);
        $this->assertSame([
            'id'       => 1,
            'name'     => 'Товар А',
            'article'  => 'ART-001',
            'price'    => 100.0,
            'quantity' => 5,
        ], $result[0]);
    }

    public function test_get_all_returns_zero_quantity_when_product_absent_in_catalog(): void
    {
        $dbResult = $this->makeDbResult([
            ['ID' => 99, 'NAME' => 'X', 'PROPERTY_ARTICLE_VALUE' => 'ART-099', 'CATALOG_PRICE_1' => 50.0],
        ]);

        $blockElement = $this->createMock(IBlockElementInterface::class);
        $blockElement->method('GetList')->willReturn($dbResult);

        $catalog = $this->createMock(CatalogProductInterface::class);
        $catalog->method('GetByID')->willReturn(false);

        $repo = new ProductRepository($blockElement, $catalog);
        $result = $repo->getAll();

        $this->assertSame(0, $result[0]['quantity']);
    }

    public function test_get_all_returns_empty_array_for_empty_catalog(): void
    {
        $dbResult = $this->makeDbResult([]);

        $blockElement = $this->createMock(IBlockElementInterface::class);
        $blockElement->method('GetList')->willReturn($dbResult);

        $catalog = $this->createMock(CatalogProductInterface::class);

        $repo = new ProductRepository($blockElement, $catalog);
        $this->assertSame([], $repo->getAll());
    }
}
