<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/auth.php';

header('Content-Type: application/json');

$payload = authenticate();
$userId = $payload->id;

$items = CartLogic::getCartItems($userId);
$total = array_reduce($items, fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);

echo json_encode([
    'status' => 'success',
    'items' => $items,
    'total' => round($total, 2)
]);
