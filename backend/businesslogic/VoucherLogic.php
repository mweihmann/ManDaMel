<?php
require_once __DIR__ . '/../config/db.php';

class VoucherLogic {
    public static function getAll(): array {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM vouchers ORDER BY expires_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(string $code, float $value, string $expires_at): bool {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO vouchers (code, value, expires_at) VALUES (?, ?, ?)");
        return $stmt->execute([$code, $value, $expires_at]);
    }

    public static function update(int $id, string $code, float $value, string $expires_at): bool {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE vouchers SET code = ?, value = ?, expires_at = ? WHERE id = ?");
        return $stmt->execute([$code, $value, $expires_at, $id]);
    }

    public static function delete(int $id): bool {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM vouchers WHERE id = ?");
        return $stmt->execute([$id]);
    }
}