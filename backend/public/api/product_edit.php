<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once __DIR__ . '/../../config/db.php';

$sql = "SELECT id, name, description, price FROM products WHERE active = 1 ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($products);
