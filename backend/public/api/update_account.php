<?php
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';

$userData = authenticate(); 
$input = json_decode(file_get_contents("php://input"), true);
// file_put_contents("debug.log", print_r($input, true), FILE_APPEND);

$logic = new UserLogic();

if ($logic->updateAccount($userData->id, $input)) {
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Update failed']);
}