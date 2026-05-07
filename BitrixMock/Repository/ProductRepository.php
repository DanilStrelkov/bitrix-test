<?php
namespace BitrixMock\Repository;

use BitrixMock\Contracts\IBlockElementInterface;
use BitrixMock\Contracts\CatalogProductInterface;

class ProductRepository
{
    public function __construct(
        private IBlockElementInterface $blockElement,
        private CatalogProductInterface $catalogProduct
    ) {}

    public function getAll(): array
    {
        $result = $this->blockElement->GetList([], [], false, [], []);
        $products = [];
        while ($item = $result->Fetch()) {
            $stock = $this->catalogProduct->GetByID((int)$item['ID']);
            $products[] = [
                'id'       => (int)$item['ID'],
                'name'     => $item['NAME'],
                'article'  => $item['PROPERTY_ARTICLE_VALUE'],
                'price'    => (float)$item['CATALOG_PRICE_1'],
                'quantity' => $stock ? (int)$stock['QUANTITY'] : 0,
            ];
        }
        return $products;
    }
}
