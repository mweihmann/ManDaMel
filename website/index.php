<?php
// Include database connection
require_once 'config.php';

try {
    // Fetch products from the database
    $stmt = $pdo->query("SELECT name, price FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManDaMel Shop</title>
</head>
<body>
    <h1>Welcome to ManDaMel Shop</h1>
    <h2>Available Products</h2>
    <ul>
        <?php foreach ($products as $product): ?>
            <li><?= htmlspecialchars($product['name']) ?> - $<?= number_format($product['price'], 2) ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
