<?php
// Konfigurations- und Modellklassen einbinden
require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';

use Firebase\JWT\JWT;

// Eingehende JSON-Daten aus dem Request-Body dekodieren
$data = json_decode(file_get_contents("php://input"), true);

// -------------------
// 1. Grundvalidierung
// -------------------

// Pflichtfelder definieren
$required = ['pronouns', 'given_name', 'surname', 'email', 'username', 'password', 'confirm_password', 'postal_code', 'city', 'street', 'house_number'];

// Prüfen, ob alle Pflichtfelder vorhanden sind
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400); // Bad Request
        echo json_encode(['message' => "Field '$field' is required."]);
        exit;
    }
}

// E-Mail-Format validieren
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['message' => "Invalid email address."]);
    exit;
}

// Passwortlänge prüfen
if (strlen($data['password']) < 6) {
    http_response_code(400);
    echo json_encode(['message' => "Password must be at least 6 characters."]);
    exit;
}

// Passwörter vergleichen
if ($data['password'] !== $data['confirm_password']) {
    http_response_code(400);
    echo json_encode(['message' => "Passwords do not match."]);
    exit;
}

// ----------------------------
// 2. Prüfen ob User existiert
// ----------------------------

// Check user exists
$userLogic = new UserLogic();
if ($userLogic->userExists($data['username'], $data['email'])) {
    http_response_code(409); // Konflikt
    echo json_encode(['message' => "Username or email already in use."]);
    exit;
}

// -----------------------------
// 3. Benutzerdaten vorbereiten
// -----------------------------

// Passwort hashen
$hashed = password_hash($data['password'], PASSWORD_DEFAULT);

// Neues Benutzerobjekt vorbereiten
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

// Benutzer in der Datenbank speichern
$createdUser = $userLogic->register($user);
if (!$createdUser) {
    http_response_code(500);
    echo json_encode(['message' => "Failed to register user."]);
    exit;
}

$createdUserId = $createdUser['id'];

// -----------------------------
// 4. JWT erzeugen (für Login)
// -----------------------------

// JWT
$payload = [
    'iss' => 'http://localhost',            // Aussteller
    'aud' => 'http://localhost',            // Empfänger
    'iat' => time(),                        // Ausgestellt am
    'exp' => time() + 3600,                 // Ablaufzeit (1 Stunde)
    'data' => [
        'id' => $createdUserId,
        'email' => $user['email'],
        'username' => $user['username'],
        'role' => 'user'                    // Rollebe des Benutzers
    ]
];

// JWT-Token mit geheimem Schlüssel signieren
$jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

// ----------------------------------
// 5. Refresh Token in DB speichern
// ----------------------------------

$refresh_token = bin2hex(random_bytes(32)); // Zufälliger Token
$expires_at = date('Y-m-d H:i:s', time() + (60 * 60 * 24 * 7)); // 7 Tage gültig

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

// -------------------------------
// 6. Refresh Token als Cookie senden
// -------------------------------

setcookie('refresh_token', $refresh_token, [
    'expires' => time() + (60 * 60 * 24 * 7), // 7 Tage
    'path' => '/',
    'httponly' => true,                       // Nicht per JavaScript lesbar
    'secure' => false,                        // In Produktion auf true setzen!
    'samesite' => 'Strict'                    // Kein Cookie bei externem Request
]);

// ----------------------------
// 7. Erfolgsantwort an Client
// ----------------------------

// Success response
echo json_encode([
    'message' => 'Registration successful',
    'token' => $jwt
]);
