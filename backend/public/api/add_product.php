<?php

// CORS-Header setzen, damit Frontend (Port 3000) mit Backend (Port 5000) kommunizieren darf
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json"); // Antwort als JSON

// Datenbankverbindung einbinden
include_once __DIR__ . '/../../config/db.php';

// Verzeichnisse für Uploads
$uploadDirFiles = __DIR__ . '/../uploads/files/';
$uploadDirImages = __DIR__ . '/../images/';

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

// Upload-Dateien vorbereiten
$file_path = '';
$image_path = '';

// Fehlerbehandlung: Prüfen ob Datei hochgeladen wurde
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $fileName = basename($_FILES['file']['name']); // <<< Nur Dateiname übernehmen
    $fileDestination = $uploadDirFiles . $fileName;

    if (!move_uploaded_file($_FILES['file']['tmp_name'], $fileDestination)) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Datei-Upload fehlgeschlagen']);
        exit;
    }

    $file_path = $fileName;
}

// Fehlerbehandlung: Prüfen ob Bild hochgeladen wurde
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imageName = basename($_FILES['image']['name']); // <<< Nur Dateiname übernehmen
    $imageDestination = $uploadDirImages . $imageName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $imageDestination)) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Bild-Upload fehlgeschlagen']);
        exit;
    }

    $image_path = $imageName;
}

// Pflichtfelder prüfen
if (empty($name) || empty($description) || empty($price) || empty($file_path) || empty($image_path)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Fehlende Felder im Formular']);
    exit;
}

// Daten in die Datenbank einfügen
$sql = "INSERT INTO products (name, description, rating, price, category_id, image, file_path, created_at)
        VALUES (:name, :description, :rating, :price, :category_id, :image, :file_path, NOW())";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':rating' => (int)$rating,
        ':price' => (float)$price,
        ':category_id' => (int)$category_id,
        ':image' => $image_path,
        ':file_path' => $file_path
    ]);

    // Erfolgsmeldung senden
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Fehler beim Speichern in der Datenbank: ' . $e->getMessage()
    ]);
}
