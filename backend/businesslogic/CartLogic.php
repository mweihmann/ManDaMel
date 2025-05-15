<?php
require_once __DIR__ . '/../config/db.php';

class CartLogic {
    public static function getOrCreateCartId(int $userId): int {
        global $pdo;

        $stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id = ?");
        $stmt->execute([$userId]);
        $cartId = $stmt->fetchColumn();

        if (!$cartId) {
            $pdo->prepare("INSERT INTO carts (user_id) VALUES (?)")->execute([$userId]);
            $cartId = $pdo->lastInsertId();
        }

        return (int)$cartId;
    }

    public static function addProduct(int $cartId, int $productId, int $quantity): void {
        global $pdo;

        $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([$cartId, $productId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $newQty = $existing['quantity'] + $quantity;
            $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?")->execute([$newQty, $existing['id']]);
        } else {
            $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)")
                ->execute([$cartId, $productId, $quantity]);
        }
    }

    public static function getCartItems(int $userId): array {
        global $pdo;

        $stmt = $pdo->prepare("
            SELECT p.id, p.name, p.price, ci.quantity, p.image
            FROM carts c
            JOIN cart_items ci ON ci.cart_id = c.id
            JOIN products p ON p.id = ci.product_id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateQuantity(int $userId, int $productId, int $quantity): void {
        global $pdo;

        $cartId = self::getOrCreateCartId($userId);

        if ($quantity === 0) {
            $pdo->prepare("DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?")->execute([$cartId, $productId]);
        } else {
            $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND product_id = ?")
                ->execute([$quantity, $cartId, $productId]);
        }
    }

    public static function clearCart(int $userId): void {
        global $pdo;

        $stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id = ?");
        $stmt->execute([$userId]);
        $cartId = $stmt->fetchColumn();

        if ($cartId) {
            $pdo->prepare("DELETE FROM cart_items WHERE cart_id = ?")->execute([$cartId]);
        }
    }
}