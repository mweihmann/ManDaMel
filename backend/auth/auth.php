<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../models/User.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Authenticates the user from JWT token in Authorization header.
 * @return object $decoded->data
 */
function authenticate(bool $required = true) {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';

    // if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    //     header("Access-Control-Allow-Origin: http://localhost:3000");
    //     header("Content-Type: application/json");
    //     http_response_code(401);
    //     echo json_encode(['message' => 'Access denied. No token provided.']);
    //     exit;
    // }

    if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        if ($required) {
            header("Access-Control-Allow-Origin: http://localhost:3000");
            header("Content-Type: application/json");
            http_response_code(401);
            echo json_encode(['message' => 'Access denied. No token provided.']);
            exit;
        } else {
            return null;
        }
    }

    $jwt = $matches[1];

    try {
        $decoded = JWT::decode($jwt, new Key($_ENV['JWT_SECRET'], 'HS256'));
        return $decoded->data;
    } catch (Exception $e) {
        // header("Access-Control-Allow-Origin: http://localhost:3000");
        // header("Content-Type: application/json");
        // http_response_code(401);
        // echo json_encode(['message' => 'Access denied. Invalid token.', 'error' => $e->getMessage()]);
        // exit;
        if ($required) {
            header("Access-Control-Allow-Origin: http://localhost:3000");
            header("Content-Type: application/json");
            http_response_code(401);
            echo json_encode(['message' => 'Access denied. Invalid token.', 'error' => $e->getMessage()]);
            exit;
        } else {
            return null;
        }
    }
}