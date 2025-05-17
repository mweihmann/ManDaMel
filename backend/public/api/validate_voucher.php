<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/VoucherLogic.php';
require_once __DIR__ . '/../../businesslogic/CartLogic.php';

header('Content-Type: application/json');

try {
    $payload = authenticate();
    $userId = $payload->id;

    $data = json_decode(file_get_contents('php://input'), true);
    $voucherCode = $data['voucher_code'] ?? null;

    if (!$voucherCode) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Kein Gutscheincode angegeben.']);
        exit;
    }

    $voucher = VoucherLogic::validateVoucher($voucherCode);

    if (!$voucher || $voucher['is_used'] || $voucher['expired'] || $voucher['remaining_value'] <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Ungültiger oder abgelaufener Gutschein.']);
        exit;
    }

    $items = CartLogic::getCartItems($userId);
    $total = array_reduce($items, fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);

    $discount = min($total, $voucher['remaining_value']);
    $finalTotal = max(0, $total - $discount);

    echo json_encode([
        'status' => 'success',
        'voucher_value' => $voucher['value'],
        'discount' => $discount,
        'remaining_value' => $voucher['remaining_value'],
        'remaining_after' => max(0, $voucher['remaining_value'] - $discount),
        'total_before' => $total,
        'total_after' => $finalTotal
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Serverfehler', 'error' => $e->getMessage()]);
}
