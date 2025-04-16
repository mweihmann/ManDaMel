<?php

require_once __DIR__ . '/../../config/bootstrap.php';

// Clear refresh token cookie
setcookie('refresh_token', '', [
    'expires' => time() - 3600,
    'path' => '/',
    'httponly' => true,
    'secure' => false, // true with HTTPS
    'samesite' => 'Strict'
]);

// remove refresh token from database
if (isset($_COOKIE['refresh_token'])) {
    $stmt = $pdo->prepare("DELETE FROM refresh_tokens WHERE token = :token");
    $stmt->execute(['token' => $_COOKIE['refresh_token']]);
}

http_response_code(200);
echo json_encode(['message' => 'Logout successful.']);
