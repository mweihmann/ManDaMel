<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json"); // Wichtig: JSON als Antworttyp

include_once __DIR__ . '/../../config/db.php';

$category = $_GET['category'] ?? '';
$rating = $_GET['rating'] ?? '';
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM products WHERE active = 1";
$params = [];

if ($category !== '') {
    $sql .= " AND category_id = :category";
    $params['category'] = $category;
}
if ($rating !== '') {
    $sql .= " AND rating >= :rating";
    $params['rating'] = $rating;
}
if ($search !== '') {
    $sql .= " AND (name LIKE :search_name OR description LIKE :search_description)";
}

$stmt = $pdo->prepare($sql);

if ($category !== '') {
    $stmt->bindValue(':category', $category, PDO::PARAM_INT);
}
if ($rating !== '') {
    $stmt->bindValue(':rating', $rating, PDO::PARAM_INT);
}
if ($search !== '') {
    $searchWildcard = '%' . $search . '%';
    $stmt->bindValue(':search_name', $searchWildcard, PDO::PARAM_STR);
    $stmt->bindValue(':search_description', $searchWildcard, PDO::PARAM_STR);
}

$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Antwort als JSON zurückgeben
echo json_encode($products);
