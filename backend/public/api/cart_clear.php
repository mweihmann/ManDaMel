<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/auth.php';

header('Content-Type: application/json');

$payload = authenticate();
$userId = $payload->id;

CartLogic::clearCart($userId);

echo json_encode(['status' => 'success']);
