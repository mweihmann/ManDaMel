<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../models/User.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Authenticates the user from JWT token in Authorization header.
 * @return object $decoded->data
 */
function authenticate() {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';

    if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(['message' => 'Access denied. No token provided.']);
        exit;
    }

    $jwt = $matches[1];

    try {
        $decoded = JWT::decode($jwt, new Key($_ENV['JWT_SECRET'], 'HS256'));
        return $decoded->data;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['message' => 'Access denied. Invalid token.', 'error' => $e->getMessage()]);
        exit;
    }
}