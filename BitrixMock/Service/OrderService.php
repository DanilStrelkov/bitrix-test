<?php
namespace BitrixMock\Service;

use BitrixMock\Contracts\CatalogProductInterface;

class OrderService
{
    public function __construct(
        private CatalogProductInterface $catalogProduct
    ) {}

    public function placeOrder(int $productId, int $qty): array
    {
        if ($qty <= 0) {
            return ['success' => false, 'error' => 'Количество должно быть больше нуля'];
        }

        $stock = $this->catalogProduct->GetByID($productId);
        if ($stock === false) {
            return ['success' => false, 'error' => 'Товар не найден'];
        }

        if ($qty > $stock['QUANTITY']) {
            return ['success' => false, 'error' => 'Недостаточно товара на складе'];
        }

        $newQty = $stock['QUANTITY'] - $qty;
        $_SESSION['products'][$productId]['CATALOG_QUANTITY'] = $newQty;

        return ['success' => true, 'newQuantity' => $newQty];
    }
}
