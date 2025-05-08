<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json"); // Wichtig: JSON als Antworttyp

include_once __DIR__ . '/../../config/db.php';

$category = $_GET['category'] ?? '';
$rating = $_GET['rating'] ?? '';

$sql = "SELECT * FROM products WHERE 1=1";
if ($category !== '') {
    $sql .= " AND category_id = :category";
}
if ($rating !== '') {
    $sql .= " AND rating >= :rating";
}

$stmt = $pdo->prepare($sql);

if ($category !== '') {
    $stmt->bindValue(':category', $category, PDO::PARAM_INT);
}
if ($rating !== '') {
    $stmt->bindValue(':rating', $rating, PDO::PARAM_INT);
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Antwort als JSON zurückgeben
echo json_encode($products);
