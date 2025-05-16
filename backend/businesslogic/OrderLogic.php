<?php
require_once __DIR__ . '/../config/db.php';

class OrderLogic {
    public static function getOrdersForUser(int $userId): array {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $stmtItems = $pdo->prepare("
                SELECT p.name, oi.price
                FROM order_items oi
                JOIN products p ON p.id = oi.product_id
                WHERE oi.order_id = ?
            ");
            $stmtItems->execute([$order['id']]);
            $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
        }

        return $orders;
    }

    public static function updateOrder(int $orderId, array $data): bool {
        global $pdo;

        $fields = [];
        $params = ['id' => $orderId];

        if (isset($data['total'])) {
            $fields[] = 'total = :total';
            $params['total'] = $data['total'];
        }

        if (isset($data['status'])) {
            $fields[] = 'status = :status';
            $params['status'] = $data['status'];
        }

        if (empty($fields)) return false;

        $stmt = $pdo->prepare("
            UPDATE orders SET " . implode(', ', $fields) . " WHERE id = :id
        ");
        return $stmt->execute($params);
    }
}