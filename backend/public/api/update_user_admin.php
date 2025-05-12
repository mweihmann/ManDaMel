<?php
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$user = authenticate();
if ($user->role !== 'admin') {
    http_response_code(403);
    echo json_encode(['message' => 'Access denied']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing user ID']);
    exit;
}

$logic = new UserLogic();
$ok = $logic->updateAccount((int)$data['id'], $data, true);
echo json_encode(['success' => $ok]);