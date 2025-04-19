<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/auth.php';

try {
    $payload = authenticate(); // Decoded JWT from cookie  header
    $userId = $payload->id;

    global $pdo;

    // Get all orders from the user
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :userId ORDER BY created_at DESC");
    $stmt->execute(['userId' => $userId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($orders as &$order) {
        // Attach products to each order
        $stmtItems = $pdo->prepare("
            SELECT p.id, p.name, oi.price
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = :orderId
        ");
        $stmtItems->execute(['orderId' => $order['id']]);
        $order['products'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode(['orders' => $orders]);

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized', 'error' => $e->getMessage()]);
}
