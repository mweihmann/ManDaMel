<?php
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../config/bootstrap.php';

try {
    // Auth check
    $payload = authenticate();
    $userId = $payload->id;

    // Check for file ID
    $fileId = $_GET['file_id'] ?? null;
    if (!$fileId) {
        http_response_code(400);
        echo json_encode(['message' => 'File ID is required']);
        exit;
    }

    global $pdo;

    // Check if the user bought the product
    $stmt = $pdo->prepare("
        SELECT p.name, p.file_path FROM products p
        JOIN order_items oi ON oi.product_id = p.id
        JOIN orders o ON o.id = oi.order_id
        WHERE p.id = :fileId AND o.user_id = :userId
    ");
    $stmt->execute([
        'fileId' => $fileId,
        'userId' => $userId
    ]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        http_response_code(403);
        echo json_encode(['message' => 'Access denied']);
        exit;
    }

    // Resolve and validate file path
    $filename = basename($product['file_path']);
    $absolutePath = __DIR__ . '/../../uploads/files/' . $filename;
    if (!file_exists($absolutePath)) {
        http_response_code(404);
        echo json_encode(['message' => 'File not found']);
        exit;
    }

    //get filename with extension
    $filenameWithExtension = basename($product['file_path']);

    if (ob_get_length()) ob_end_clean();

    // Set secure headers
    header('Access-Control-Expose-Headers: Content-Disposition');
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename=\"$filenameWithExtension\"");
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($absolutePath));
    flush();

    // Send the file
    readfile($absolutePath);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Download failed', 'error' => $e->getMessage()]);
    exit;
}
