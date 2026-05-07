<?php
require_once __DIR__ . '/../vendor/autoload.php';

$mockFiles = [
    __DIR__ . '/../BitrixMock/Mock/CDBResult.php',
    __DIR__ . '/../BitrixMock/Mock/CIBlockElement.php',
    __DIR__ . '/../BitrixMock/Mock/CCatalogProduct.php',
];
foreach ($mockFiles as $file) {
    if (file_exists($file)) {
        require_once $file;
    }
}
