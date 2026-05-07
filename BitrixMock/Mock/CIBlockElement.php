<?php
use BitrixMock\Contracts\IBlockElementInterface;

class CIBlockElement implements IBlockElementInterface
{
    public function GetList(
        array $order = [],
        array $filter = [],
        mixed $groupBy = false,
        array $navParams = [],
        array $select = []
    ): \BitrixMock\Contracts\DBResultInterface {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['products'])) {
            $_SESSION['products'] = require __DIR__ . '/../data.php';
        }
        return new CDBResult(array_values($_SESSION['products']));
    }
}
