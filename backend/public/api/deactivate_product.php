<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once __DIR__ . '/../../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Ungültige Produkt-ID']);
    exit;
}

$sql = "UPDATE products SET active = 0 WHERE id = :id";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([':id' => (int)$id]);
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
