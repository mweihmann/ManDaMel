<?php
// File: public/api/login.php

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$data = json_decode(file_get_contents("php://input"), true);
$login = $data['login'] ?? '';
$password = $data['password'] ?? '';

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

http_response_code(200);
echo json_encode([
    'message' => 'Login successful',
    'token' => $jwt
]);
