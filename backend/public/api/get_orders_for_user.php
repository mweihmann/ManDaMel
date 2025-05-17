<?php
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/OrderLogic.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Content-Type: application/json");

$admin = authenticate();
if ($admin->role !== 'admin') {
    http_response_code(403);
    echo json_encode(['message' => 'Access denied.']);
    exit;
}

$userId = $_GET['user_id'] ?? null;
if (!$userId) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing user_id']);
    exit;
}

$orders = OrderLogic::getOrdersForUser((int)$userId);
echo json_encode(['orders' => $orders]);