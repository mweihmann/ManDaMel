<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/CartLogic.php';

header('Content-Type: application/json');

$payload = authenticate(false);
$items = [];

if ($payload) {
    $userId = $payload->id;
    $items = CartLogic::getCartItems($userId);
} else {
    $items = $_SESSION['cart'] ?? [];
}

$total = array_reduce($items, fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);
$count = array_sum(array_column($items, 'quantity'));

echo json_encode([
    'status' => 'success',
    'items' => array_values($items),
    'total' => round($total, 2),
    'count' => $count
]);