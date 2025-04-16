<?php

require_once __DIR__ . '/../../config/bootstrap.php';
global $pdo;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$data = json_decode(file_get_contents("php://input"), true);
$token = $data['refresh_token'] ?? '';

if (!$token) {
    http_response_code(400);
    echo json_encode(['message' => 'Refresh token required.']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM refresh_tokens WHERE token = :token AND expires_at > NOW()");
$stmt->execute(['token' => $token]);
$storedToken = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$storedToken) {
    http_response_code(401);
    echo json_encode(['message' => 'Invalid or expired refresh token.']);
    exit;
}

$user_id = $storedToken['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404);
    echo json_encode(['message' => 'User not found.']);
    exit;
}

$payload = [
    'iss' => 'http://localhost',
    'aud' => 'http://localhost',
    'iat' => time(),
    'exp' => time() + 3600,
    'data' => [
        'id' => $user['id'],
        'email' => $user['email'],
        'username' => $user['username'],
        'role' => $user['role']
    ]
];

$jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

echo json_encode([
    'token' => $jwt
]);