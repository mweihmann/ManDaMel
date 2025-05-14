<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/CartLogic.php';

header('Content-Type: application/json');

$payload = authenticate(false);
$data = json_decode(file_get_contents('php://input'), true);
$productId = (int)($data['product_id'] ?? 0);
$quantity = (int)($data['quantity'] ?? 1);

if ($productId <= 0 || $quantity < 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

if ($payload) {
    $userId = $payload->id;
    CartLogic::updateQuantity($userId, $productId, $quantity);
    $items = CartLogic::getCartItems($userId);
} else {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $key = array_search($productId, array_column($_SESSION['cart'], 'id'));
    if ($key !== false) {
        if ($quantity === 0) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex
        } else {
            $_SESSION['cart'][$key]['quantity'] = $quantity;
        }
    }
    $items = $_SESSION['cart'];
}

$count = array_sum(array_column($items, 'quantity'));
$total = array_reduce($items, fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);

echo json_encode([
    'status' => 'success',
    'count' => $count,
    'total' => round($total, 2),
    'items' => $items
]);
