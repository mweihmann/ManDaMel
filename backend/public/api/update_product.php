<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../businesslogic/ProductLogic.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$logic = new ProductLogic();

$id = $_POST['id'] ?? null;
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$rating = $_POST['rating'] ?? 0;
$price = $_POST['price'] ?? 0;
$category_id = $_POST['category_id'] ?? null;
$active = $_POST['active'] ?? 1;

if (!$id || !$name || !$description || !$price) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
    exit;
}

// Upload-Pfade
$uploadDirFiles = __DIR__ . '/../../uploads/files/';
$uploadDirImages = __DIR__ . '/../../public/uploads/images/';
if (!is_dir($uploadDirFiles)) mkdir($uploadDirFiles, 0777, true);
if (!is_dir($uploadDirImages)) mkdir($uploadDirImages, 0777, true);

$file_path = null;
$image_path = null;

if (!empty($_FILES['file']['name'])) {
    $fileName = basename($_FILES['file']['name']);
    $dest = $uploadDirFiles . $fileName;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
        $file_path = $fileName;
    }
}

if (!empty($_FILES['image']['name'])) {
    $imgName = basename($_FILES['image']['name']);
    $dest = $uploadDirImages . $imgName;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        $image_path = $imgName;
    }
}

// Produkt aktualisieren
$ok = $logic->updateWithFiles([
    'id' => $id,
    'name' => $name,
    'description' => $description,
    'rating' => $rating,
    'price' => $price,
    'category_id' => $category_id,
    'file_path' => $file_path,
    'image' => $image_path,
    'active' => $active
]);

echo json_encode(['status' => $ok ? 'success' : 'error']);
