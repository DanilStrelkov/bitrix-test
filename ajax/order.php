<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../BitrixMock/bootstrap.php';

use BitrixMock\Service\OrderService;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Метод не поддерживается'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $body = json_decode(file_get_contents('php://input'), true);

    if (!isset($body['productId'], $body['quantity'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Неверные параметры запроса'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['products'])) {
        $_SESSION['products'] = require __DIR__ . '/../BitrixMock/data.php';
    }

    $service = new OrderService(new CCatalogProduct());
    $result = $service->placeOrder((int)$body['productId'], (int)$body['quantity']);

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
