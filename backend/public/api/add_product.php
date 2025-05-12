<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json"); // Antwort als JSON

// Datenbankverbindung einbinden
include_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../businesslogic/ProductLogic.php';

// Verzeichnisse für Uploads
$uploadDirFiles = __DIR__ . '/../../uploads/files/';
$uploadDirImages = __DIR__ . '/../../public/uploads/images/';

// Verzeichnisse erstellen, falls nicht vorhanden
if (!is_dir($uploadDirFiles)) {
    mkdir($uploadDirFiles, 0777, true);
}
if (!is_dir($uploadDirImages)) {
    mkdir($uploadDirImages, 0777, true);
}

// POST-Daten auslesen
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$rating = $_POST['rating'] ?? 0;
$price = $_POST['price'] ?? 0;
$category_id = $_POST['category_id'] ?? null;

if (empty($name) || empty($description) || empty($price)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Fehlende Pflichtfelder']);
    exit;
}

// Upload-Dateien vorbereiten
$file_path = '';
$image_path = '';

// Fehlerbehandlung: Prüfen ob Datei hochgeladen wurde
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $fileName = basename($_FILES['file']['name']);
    $fileDestination = $uploadDirFiles . $fileName;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $fileDestination)) {
        $file_path = $fileName;
    }
}

// Fehlerbehandlung: Prüfen ob Bild hochgeladen wurde
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imageName = basename($_FILES['image']['name']);
    $imageDestination = $uploadDirImages . $imageName;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $imageDestination)) {
        $image_path = $imageName;
    }
}

$data = [
    'name' => $name,
    'description' => $description,
    'rating' => (int)$rating,
    'price' => (float)$price,
    'category_id' => (int)$category_id,
    'file_path' => $file_path,
    'image' => $image_path
];

$logic = new ProductLogic();
if ($logic->create($data)) {
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Fehler beim Speichern']);
}