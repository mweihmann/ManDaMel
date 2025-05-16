<?php
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';

$userData = authenticate(); 
$input = json_decode(file_get_contents("php://input"), true);
// file_put_contents("debug.log", print_r($input, true), FILE_APPEND);

if (empty($input['current_password'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Password required for update']);
    exit;
}

$logic = new UserLogic();
$currentUser = $logic->findUserById($userData->id);

if (!$currentUser || !password_verify($input['current_password'], $currentUser['password_hash'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
    exit;
}

if ($logic->updateAccount($userData->id, $input)) {
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Update failed']);
}
