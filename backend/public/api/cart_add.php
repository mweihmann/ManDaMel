<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/auth.php';

header('Content-Type: application/json');

$payload = authenticate();
$userId = $payload->id;

$data = json_decode(file_get_contents('php://input'), true);
$productId = (int)($data['product_id'] ?? 0);
$quantity = (int)($data['quantity'] ?? 1);

if (!$productId || $quantity < 1) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

$cartId = CartLogic::getOrCreateCartId($userId);
CartLogic::addProduct($cartId, $productId, $quantity);

echo json_encode(['status' => 'success']);