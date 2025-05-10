<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include_once __DIR__ . '/../../config/db.php';

// DEBUG (aktivieren für Entwicklung)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Parameter einlesen
$category = $_GET['category_id'] ?? '';
$rating   = $_GET['rating'] ?? '';
$search   = $_GET['search'] ?? '';
$sort_by  = $_GET['sort_by'] ?? '';

// SQL Grundstruktur
$sql = "SELECT * FROM products WHERE active = 1";
$params = [];

// Filter: Kategorie
if ($category !== '') {
    $sql .= " AND category_id = :category";
    $params[':category'] = $category;
}

// Filter: Bewertung
if ($rating !== '') {
    $sql .= " AND rating = :rating";
    $params[':rating'] = $rating;
}

// Filter: Suchbegriff (2x in SQL → 2x im Params-Array!)
if ($search !== '') {
    $sql .= " AND (name LIKE :search_name OR description LIKE :search_description)";
    $params[':search_name'] = '%' . $search . '%';
    $params[':search_description'] = '%' . $search . '%';
}

// Sortierung
switch ($sort_by) {
    case 'price_asc':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY price DESC";
        break;
    case 'rating_asc':
        $sql .= " ORDER BY rating ASC";
        break;
    case 'rating_desc':
        $sql .= " ORDER BY rating DESC";
        break;
    default:
        $sql .= " ORDER BY created_at DESC";
        break;
}

// Query vorbereiten
$stmt = $pdo->prepare($sql);

// Parameter korrekt binden
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}

// Ausführen & JSON senden
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products);
