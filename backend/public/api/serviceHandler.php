<?php
// CORS Headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Preflight request handling
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';

$method = $_GET['method'] ?? '';

$userLogic = new UserLogic();

if ($method === 'getAllUsers') {
    $users = $userLogic->getAllUsers();
    echo json_encode($users);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid method']);
}