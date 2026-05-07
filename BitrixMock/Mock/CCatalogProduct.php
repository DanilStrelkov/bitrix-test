<?php
use BitrixMock\Contracts\CatalogProductInterface;

class CCatalogProduct implements CatalogProductInterface
{
    public function GetByID(int $id): array|false
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['products'][$id])) {
            return false;
        }
        return ['QUANTITY' => $_SESSION['products'][$id]['CATALOG_QUANTITY']];
    }
}
