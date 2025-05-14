<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/CartLogic.php';
require_once __DIR__ . '/../../businesslogic/ProductLogic.php';

header('Content-Type: application/json');

// Authentifizierung: JWT
$payload = authenticate(false); // Token optional
$data = json_decode(file_get_contents('php://input'), true);

$productId = (int)($data['product_id'] ?? 0);
$quantity = (int)($data['quantity'] ?? 1);

if ($productId <= 0 || $quantity < 1) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

$productLogic = new ProductLogic();
$product = $productLogic->findById($productId);

if (!$product || (int)$product['active'] !== 1) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Produkt nicht gefunden oder inaktiv']);
    exit;
}

// Angemeldeter Benutzer
if ($payload) {
    $userId = $payload->id;
    $cartId = CartLogic::getOrCreateCartId($userId);
    CartLogic::addProduct($cartId, $productId, $quantity);
    $items = CartLogic::getCartItems($userId);
} else {
    // Gast: Session-Warenkorb
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $key = array_search($productId, array_column($_SESSION['cart'], 'id'));

    if ($key !== false) {
        $_SESSION['cart'][$key]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'image' => $product['image']
        ];
    }

    // Änderungen müssen aus $_SESSION gelesen werden, nicht aus $items
    $items = $_SESSION['cart'];
}

$count = array_sum(array_column($items, 'quantity'));
$total = array_reduce($items, fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);

echo json_encode([
    'status' => 'success',
    'items' => $items,
    'total' => round($total, 2),
    'count' => $count
]);