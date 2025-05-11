<?php
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/PaymentLogic.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$userData = authenticate();
$data = json_decode(file_get_contents("php://input"), true);
$data['user_id'] = $userData->id;

$logic = new PaymentLogic();
$ok = $logic->addPaymentMethod($data);

echo json_encode(['success' => $ok]);
