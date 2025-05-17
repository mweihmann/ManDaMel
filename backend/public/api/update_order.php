<?php
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/OrderLogic.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$admin = authenticate();
if ($admin->role !== 'admin') {
    http_response_code(403);
    echo json_encode(['message' => 'Access denied']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing order ID']);
    exit;
}

$ok = OrderLogic::updateOrder((int)$data['id'], $data);
echo json_encode(['success' => $ok]);
