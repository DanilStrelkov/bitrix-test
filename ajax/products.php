<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../BitrixMock/bootstrap.php';

use BitrixMock\Repository\ProductRepository;

try {
    $repo = new ProductRepository(new CIBlockElement(), new CCatalogProduct());
    echo json_encode(['success' => true, 'data' => $repo->getAll()], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
