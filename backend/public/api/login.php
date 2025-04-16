<?php

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$data = json_decode(file_get_contents("php://input"), true);
$login = $data['login'] ?? '';
$password = $data['password'] ?? '';
$remember = $data['remember'] ?? false;

if (!$login || !$password) {
    http_response_code(400);
    echo json_encode(['message' => 'Login and password are required.']);
    exit;
}

$userLogic = new UserLogic();
$user = $userLogic->findByLogin($login);

if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Invalid credentials.']);
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

// Create refresh token
$refresh_token = bin2hex(random_bytes(32));
$expires_at = date('Y-m-d H:i:s', time() + (60 * 60 * 24 * 7)); // 7 days

$stmt = $pdo->prepare("INSERT INTO refresh_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
$stmt->execute([
    'user_id' => $user['id'],
    'token' => $refresh_token,
    'expires_at' => $expires_at
]);

// refresh token as HttpOnly cookie (expires if remember is checked)
setcookie('refresh_token', $refresh_token, [
    'expires' => $remember ? time() + (60 * 60 * 24 * 30) : 0,
    'path' => '/',
    'httponly' => true,
    'secure' => false, // true if using HTTPS
    'samesite' => 'Strict'
]);


echo json_encode([
    'message' => 'Login successful',
    'token' => $jwt
]);
