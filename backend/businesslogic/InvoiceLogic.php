<?php
require_once __DIR__ . '/../config/db.php';

class InvoiceLogic {
    public static function getOrderWithItemsAndUser(int $orderId, int $userId): ?array {
        global $pdo;

        // Bestellung prüfen
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $userId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) return null;

        // Produkte laden
        $stmtItems = $pdo->prepare("
            SELECT p.name, oi.price
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = ?
        ");
        $stmtItems->execute([$orderId]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        // Rechnungsnummer generieren falls noch nicht vorhanden
        if (!$order['invoice_number']) {
            $invoiceNumber = 'INV-' . str_pad($orderId, 6, '0', STR_PAD_LEFT);
            $update = $pdo->prepare("UPDATE orders SET invoice_number = ? WHERE id = ?");
            $update->execute([$invoiceNumber, $orderId]);
            $order['invoice_number'] = $invoiceNumber;
        }

        $order['items'] = $items;
        return $order;
    }
}