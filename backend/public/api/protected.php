<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';

$userData = authenticate();

echo json_encode([
    'message' => 'Access granted',
    'user' => $userData
]);
