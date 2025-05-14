<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/CartLogic.php';

header('Content-Type: application/json');

$payload = authenticate(false);

if ($payload) {
    $userId = $payload->id;
    CartLogic::clearCart($userId);
} else {
    unset($_SESSION['cart']);
}

echo json_encode(['status' => 'success']);