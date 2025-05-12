<?php
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/PaymentLogic.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

$userData = authenticate();
$logic = new PaymentLogic();

$methods = $logic->getMethodsByUserId($userData->id);
echo json_encode($methods);
