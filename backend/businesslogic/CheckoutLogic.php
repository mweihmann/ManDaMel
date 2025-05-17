<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/CartLogic.php';

class CheckoutLogic {
    public static function performCheckout(int $userId, ?string $voucherCode, ?string $paymentMethod): array {
        global $pdo;

        // Warenkorb holen
        $items = CartLogic::getCartItems($userId);
        if (count($items) === 0) {
            return ['status' => 'error', 'message' => 'Warenkorb ist leer'];
        }

        $total = array_reduce($items, fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);
        $voucherId = null;
        $voucherValue = 0;

        // Gutschein prüfen
        if ($voucherCode) {
            $stmt = $pdo->prepare("SELECT * FROM vouchers WHERE code = ? AND remaining_value > 0 AND is_used = 0 AND expires_at > NOW()");
            $stmt->execute([$voucherCode]);
            $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$voucher) {
                return ['status' => 'error', 'message' => 'Ungültiger oder abgelaufener Gutschein'];
            }

            $voucherValue = min($total, $voucher['remaining_value']);
            $voucherId = $voucher['id'];
        }

        // Restbetrag
        $finalTotal = max(0, $total - $voucherValue);
        if (!$paymentMethod && $finalTotal > 0) {
            return ['status' => 'error', 'message' => 'Zahlungsmethode erforderlich'];
        }

        // Bestellung speichern
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, payment_method, total, voucher_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $finalTotal > 0 ? $paymentMethod : 'voucher', $total, $voucherId]);
        $orderId = $pdo->lastInsertId();

        foreach ($items as $item) {
            $pdo->prepare("INSERT INTO order_items (order_id, product_id, price) VALUES (?, ?, ?)")
                ->execute([$orderId, $item['id'], $item['price']]);
        }

        if ($voucherId) {
            $newValue = $voucher['remaining_value'] - $voucherValue;
            $isUsed = $newValue <= 0 ? 1 : 0;
            $update = $pdo->prepare("UPDATE vouchers SET remaining_value = ?, is_used = ? WHERE id = ?");
            $update->execute([$newValue, $isUsed, $voucherId]);
        }

        CartLogic::clearCart($userId);

        return [
            'status' => 'success',
            'message' => 'Bestellung erfolgreich',
            'order_id' => $orderId
        ];
        
    }
}