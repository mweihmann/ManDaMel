<?php
require_once __DIR__ . '/../config/db.php';

class VoucherLogic {

    public static function getAll(): array {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM vouchers ORDER BY expires_at DESC");
        $vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($vouchers as &$voucher) {
            $voucher['expired'] = strtotime($voucher['expires_at']) < time();
        }

        return $vouchers;
    }

    public static function create(?string $code, float $value, string $expires_at): bool {
        global $pdo;
        if (!$code) $code = self::generateCode();
        $stmt = $pdo->prepare("INSERT INTO vouchers (code, value, expires_at) VALUES (?, ?, ?)");
        return $stmt->execute([$code, $value, $expires_at]);
    }

    public static function update(int $id, string $code, float $value, string $expires_at): bool {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE vouchers SET code = ?, value = ?, expires_at = ? WHERE id = ?");
        return $stmt->execute([$code, $value, $expires_at, $id]);
    }

    // delete makes no sense without ruining orders
    // public static function delete(int $id): bool {
    //     global $pdo;
    //     $stmt = $pdo->prepare("DELETE FROM vouchers WHERE id = ?");
    //     return $stmt->execute([$id]);
    // }

    public static function generateCode(int $length = 5): string {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $code;
    }

    public static function markAsUsed(int $id): bool {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE vouchers SET is_used = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
}
