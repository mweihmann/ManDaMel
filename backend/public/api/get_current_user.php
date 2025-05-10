<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';
require_once __DIR__ . '/../../config/db.php';

$userData = authenticate();
$userLogic = new UserLogic();
$user = $userLogic->findUserById($userData->id);

if (!$user) {
    http_response_code(404);
    echo json_encode(['message' => 'User not found.']);
    exit;
}

echo json_encode($user);