<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/CheckoutLogic.php';
require_once __DIR__ . '/../../businesslogic/CartLogic.php';

header('Content-Type: application/json');

try {
    $payload = authenticate();
    $userId = $payload->id;

    $data = json_decode(file_get_contents('php://input'), true);
    $voucherCode = $data['voucher_code'] ?? null;
    $paymentMethod = $data['payment_method'] ?? null;

    if (!$voucherCode && !$paymentMethod) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Zahlungsmethode oder Gutschein erforderlich.']);
        exit;
    }

    $result = CheckoutLogic::performCheckout($userId, $voucherCode, $paymentMethod);

    if ($result['status'] === 'success') {
        echo json_encode(['status' => 'success', 'order_id' => $result['order_id']]);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => $result['message']]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Serverfehler', 'error' => $e->getMessage()]);
}
