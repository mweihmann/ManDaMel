<?php

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/AuthMiddleware.php';

try {
    $user = authenticate();

    echo json_encode([
        'message' => 'Access granted to protected content.',
        'user' => $user
    ]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized: ' . $e->getMessage()]);
}
