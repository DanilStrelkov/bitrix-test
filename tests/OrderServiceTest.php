<?php
use PHPUnit\Framework\TestCase;
use BitrixMock\Service\OrderService;
use BitrixMock\Contracts\CatalogProductInterface;

class OrderServiceTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['products'] = [
            1 => ['ID' => 1, 'NAME' => 'Товар', 'CATALOG_QUANTITY' => 10],
        ];
    }

    private function makeService(): OrderService
    {
        $catalog = $this->createMock(CatalogProductInterface::class);
        $catalog->method('GetByID')->willReturnCallback(function (int $id): array|false {
            if (!isset($_SESSION['products'][$id])) {
                return false;
            }
            return ['QUANTITY' => $_SESSION['products'][$id]['CATALOG_QUANTITY']];
        });
        return new OrderService($catalog);
    }

    public function test_place_order_deducts_quantity_and_returns_new_quantity(): void
    {
        $result = $this->makeService()->placeOrder(1, 3);

        $this->assertTrue($result['success']);
        $this->assertSame(7, $result['newQuantity']);
        $this->assertSame(7, $_SESSION['products'][1]['CATALOG_QUANTITY']);
    }

    public function test_place_order_fails_when_insufficient_stock(): void
    {
        $result = $this->makeService()->placeOrder(1, 15);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_place_order_fails_when_quantity_is_zero(): void
    {
        $result = $this->makeService()->placeOrder(1, 0);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_place_order_fails_when_product_not_found(): void
    {
        $result = $this->makeService()->placeOrder(999, 1);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error', $result);
    }
}
