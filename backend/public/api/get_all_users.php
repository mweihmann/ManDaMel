<?php
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

$user = authenticate();

if ($user->role !== 'admin') {
    http_response_code(403);
    echo json_encode(['message' => 'Access denied.']);
    exit;
}

$logic = new UserLogic();
$users = $logic->getAllUsers();

echo json_encode($users);