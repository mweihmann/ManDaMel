<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/PaymentLogic.php';

header('Content-Type: application/json');

try {
    $payload = authenticate();
    $logic = new PaymentLogic();
    $methods = $logic->getAvailablePaymentMethods($payload->id);

    echo json_encode(['success' => true, 'methods' => $methods]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false,
     'message' => 'Serverfehler',
     'error' => $e->getMessage()]);
}
