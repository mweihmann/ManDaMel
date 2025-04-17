<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';

use Firebase\JWT\JWT;

$data = json_decode(file_get_contents("php://input"), true);

// Validation
$required = ['pronouns', 'given_name', 'surname', 'email', 'username', 'password', 'confirm_password', 'postal_code', 'city', 'street', 'house_number'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['message' => "Field '$field' is required."]);
        exit;
    }
}

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['message' => "Invalid email address."]);
    exit;
}

if (strlen($data['password']) < 6) {
    http_response_code(400);
    echo json_encode(['message' => "Password must be at least 6 characters."]);
    exit;
}

if ($data['password'] !== $data['confirm_password']) {
    http_response_code(400);
    echo json_encode(['message' => "Passwords do not match."]);
    exit;
}

// Check user exists
$userLogic = new UserLogic();
if ($userLogic->userExists($data['username'], $data['email'])) {
    http_response_code(409);
    echo json_encode(['message' => "Username or email already in use."]);
    exit;
}

// Prepare data
$hashed = password_hash($data['password'], PASSWORD_DEFAULT);
$user = [
    'username' => $data['username'],
    'pronouns' => $data['pronouns'],
    'given_name' => $data['given_name'],
    'surname' => $data['surname'],
    'email' => $data['email'],
    'telephone' => $data['telephone'] ?? '',
    'country' => $data['country'] ?? '',
    'city' => $data['city'],
    'postal_code' => $data['postal_code'],
    'street' => $data['street'],
    'house_number' => $data['house_number'],
    'password_hash' => $hashed
];

// Save to DB
$createdUser = $userLogic->register($user);
if (!$createdUser) {
    http_response_code(500);
    echo json_encode(['message' => "Failed to register user."]);
    exit;
}

$createdUserId = $createdUser['id'];

// JWT
$payload = [
    'iss' => 'http://localhost',
    'aud' => 'http://localhost',
    'iat' => time(),
    'exp' => time() + 3600,
    'data' => [
        'id' => $createdUserId,
        'email' => $user['email'],
        'username' => $user['username'],
        'role' => 'user'
    ]
];

$jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

// Store refresh token
$refresh_token = bin2hex(random_bytes(32));
$expires_at = date('Y-m-d H:i:s', time() + (60 * 60 * 24 * 7));

try {
    $stmt = $pdo->prepare("INSERT INTO refresh_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
    $stmt->execute([
        'user_id' => $createdUserId,
        'token' => $refresh_token,
        'expires_at' => $expires_at
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Could not store refresh token.', 'error' => $e->getMessage()]);
    exit;
}

// Set cookie
setcookie('refresh_token', $refresh_token, [
    'expires' => time() + (60 * 60 * 24 * 7),
    'path' => '/',
    'httponly' => true,
    'secure' => false, //true in production
    'samesite' => 'Strict'
]);

// Success response
echo json_encode([
    'message' => 'Registration successful',
    'token' => $jwt
]);