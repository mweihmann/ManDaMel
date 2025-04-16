<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

$userLogic = new UserLogic();
$user = $userLogic->authenticate($email, $password);

if (!$user) {
    http_response_code(401);
    echo json_encode(['message' => 'Invalid credentials']);
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
    ]
];

$jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

echo json_encode([
    'token' => $jwt,
    'user' => $user
]);
