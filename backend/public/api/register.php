<?php
// File: public/api/register.php

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';

use Firebase\JWT\JWT;

$data = json_decode(file_get_contents("php://input"), true);

$required = ['username', 'given_name', 'surname', 'email', 'password'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['message' => "Missing field: $field"]);
        exit;
    }
}

$userLogic = new UserLogic();

if ($userLogic->userExists($data['username'], $data['email'])) {
    http_response_code(409);
    echo json_encode(['message' => 'Username or email already exists.']);
    exit;
}

$hash = password_hash($data['password'], PASSWORD_DEFAULT);

$userData = [
    'username' => $data['username'],
    'pronouns' => $data['salutation'] ?? 'they/them',
    'given_name' => $data['given_name'],
    'surname' => $data['surname'],
    'email' => $data['email'],
    'telephone' => $data['telephone'] ?? '',
    'country' => $data['country'] ?? '',
    'city' => $data['city'] ?? '',
    'postal_code' => $data['postal_code'] ?? '',
    'street' => $data['street'] ?? '',
    'house_number' => $data['house_number'] ?? '',
    'password_hash' => $hash
];

$createdUser = $userLogic->register($userData);

if (!$createdUser) {
    http_response_code(500);
    echo json_encode(['message' => 'Registration failed.']);
    exit;
}

$payload = [
    'iss' => 'http://localhost',
    'aud' => 'http://localhost',
    'iat' => time(),
    'exp' => time() + 3600,
    'data' => [
        'id' => $createdUser['id'],
        'email' => $createdUser['email'],
        'username' => $createdUser['username']
    ]
];

$jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

echo json_encode([
    'message' => 'Registration successful',
    'token' => $jwt
]);