<?php
require_once __DIR__ . '/../config/db.php';

class PaymentLogic
{
    /**
     * Add a new payment method for a user
     */
    public function addPaymentMethod(array $data): bool
    {
        global $pdo;

        $stmt = $pdo->prepare("INSERT INTO payment_info (
            user_id,
            method,
            creditcard_number,
            creditcard_expiry,
            creditcard_cvv,
            iban,
            voucher_code,
            holder_name
        ) VALUES (
            :user_id,
            :method,
            :creditcard_number,
            :creditcard_expiry,
            :creditcard_cvv,
            :iban,
            :voucher_code,
            :holder_name
        )");

        return $stmt->execute([
            'user_id' => $data['user_id'],
            'method' => $data['method'],
            'creditcard_number' => $data['creditcard_number'] ?? null,
            'creditcard_expiry' => $data['creditcard_expiry'] ?? null,
            'creditcard_cvv' => $data['creditcard_cvv'] ?? null,
            'iban' => $data['iban'] ?? null,
            'voucher_code' => $data['voucher_code'] ?? null,
            'holder_name' => $data['holder_name'] ?? null,
        ]);
    }

    public function getMethodsByUserId(int $userId): array
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM payment_info WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
