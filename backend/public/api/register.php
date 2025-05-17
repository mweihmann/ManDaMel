<?php
// Konfigurations- und Modellklassen einbinden
require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';
require_once __DIR__ . '/../../businesslogic/RegisterLogic.php';

use Firebase\JWT\JWT;

// Eingehende JSON-Daten aus dem Request-Body dekodieren
$data = json_decode(file_get_contents("php://input"), true);

// -------------------
// 1. Pflichtfelder prüfen
// -------------------

// Pflichtfelder definieren
$required = ['pronouns', 'given_name', 'surname', 'email', 'username', 'password', 'confirm_password', 'postal_code', 'city', 'street', 'house_number'];

// Prüfen, ob alle Pflichtfelder vorhanden sind
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['message' => "Field '$field' is required."]);
        exit;
    }
}

// -------------------
// 2. Validierungsmuster
// -------------------
$lettersOnlyRegex = '/^[A-Za-zÄäÖöÜüß\s\-]+$/u';
$numberRegex = '/^[0-9]+$/';
$zipRegex = '/^\d{4,10}$/';
$ibanRegex = '/^[A-Z]{2}[0-9]{2}[A-Z0-9]{11,30}$/i';
$ccRegex = '/^\d{12,19}$/';
$ccExpiryRegex = '/^(0[1-9]|1[0-2])\/\d{4}$/'; // MM/YYYY
$ccCvvRegex = '/^\d{3,4}$/';                  // 3–4 digits

// -------------------
// 3. Eingabewerte validieren
// -------------------
if (!preg_match($lettersOnlyRegex, $data['given_name'])) {
    http_response_code(400);
    echo json_encode(['message' => "First name must contain only letters."]);
    exit;
}

if (!preg_match($lettersOnlyRegex, $data['surname'])) {
    http_response_code(400);
    echo json_encode(['message' => "Last name must contain only letters."]);
    exit;
}

if (!preg_match($lettersOnlyRegex, $data['street'])) {
    http_response_code(400);
    echo json_encode(['message' => "Street must contain only letters."]);
    exit;
}

if (!preg_match($numberRegex, $data['house_number'])) {
    http_response_code(400);
    echo json_encode(['message' => "House number must be numeric."]);
    exit;
}

if (!preg_match($zipRegex, $data['postal_code'])) {
    http_response_code(400);
    echo json_encode(['message' => "Invalid ZIP code."]);
    exit;
}

if (!preg_match($lettersOnlyRegex, $data['city'])) {
    http_response_code(400);
    echo json_encode(['message' => "City must contain only letters."]);
    exit;
}

if (!preg_match($lettersOnlyRegex, $data['country'])) {
    http_response_code(400);
    echo json_encode(['message' => "Country must contain only letters."]);
    exit;
}

if (!empty($data['telephone']) && !preg_match($numberRegex, $data['telephone'])) {
    http_response_code(400);
    echo json_encode(['message' => "Phone number must contain only digits."]);
    exit;
}

if (!preg_match($lettersOnlyRegex, $data['username'])) {
    http_response_code(400);
    echo json_encode(['message' => "Username must contain only letters."]);
    exit;
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

// Zahlungsdaten prüfen
// Mindestens IBAN oder Kreditkarte erforderlich
$iban = $data['iban'] ?? null;
$creditcard = $data['creditcard_number'] ?? null;
$cc_expiry = $data['creditcard_expiry'] ?? null;
$cc_cvv = $data['creditcard_cvv'] ?? null;
$holder = $data['holder_name'] ?? ($data['given_name'] . ' ' . $data['surname']);

if (!$iban && !$creditcard) {
    http_response_code(400);
    echo json_encode(['message' => "You must provide either an IBAN or a credit card number."]);
    exit;
}

if ($iban && !preg_match($ibanRegex, $iban)) {
    http_response_code(400);
    echo json_encode(['message' => "Invalid IBAN format."]);
    exit;
}

if ($creditcard) {
    if (!preg_match($ccRegex, $creditcard)) {
        http_response_code(400);
        echo json_encode(['message' => "Invalid credit card format."]);
        exit;
    }
    if (!preg_match($ccExpiryRegex, $cc_expiry)) {
        http_response_code(400);
        echo json_encode(['message' => "Credit card expiry must be in MM/YYYY format."]);
        exit;
    }
    if (!preg_match($ccCvvRegex, $cc_cvv)) {
        http_response_code(400);
        echo json_encode(['message' => "CVV must be 3 or 4 digits."]);
        exit;
    }
}

if (!preg_match($lettersOnlyRegex, $holder)) {
    http_response_code(400);
    echo json_encode(['message' => "Holder name must contain only letters."]);
    exit;
}

// ----------------------------
// 4. Prüfen ob User existiert
// ----------------------------

// Check user exists
$userLogic = new UserLogic();
if ($userLogic->userExists($data['username'], $data['email'])) {
    http_response_code(409);
    echo json_encode(['message' => "Username or email already in use."]);
    exit;
}

// -----------------------------
// 5. Benutzer erstellen
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
$createdUser = RegisterLogic::registerUser(array_merge($user, [
    'iban' => $iban,
    'creditcard_number' => $creditcard,
    'creditcard_expiry' => $cc_expiry,
    'creditcard_cvv' => $cc_cvv,
    'holder_name' => $holder
]));

if (!$createdUser) {
    http_response_code(500);
    echo json_encode(['message' => "User registration failed (could not insert into DB)."]);
    exit;
}

$createdUserId = $createdUser['id'];

// -----------------------------
// 6. JWT erzeugen
// -----------------------------
$payload = [
    'iss' => 'http://localhost', // Aussteller
    'aud' => 'http://localhost', // Empfänger
    'iat' => time(), // Ausgestellt am
    'exp' => time() + 3600, // Ablaufzeit (1 Stunde)
    'data' => [
        'id' => $createdUserId,
        'email' => $user['email'],
        'username' => $user['username'],
        'role' => 'user' // Rollebe des Benutzers
    ]
];

// JWT-Token mit geheimem Schlüssel signieren
$jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

// -----------------------------
// 7. Refresh Token speichern
// -----------------------------
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

// -----------------------------
// 8. Refresh Token als Cookie
// -----------------------------
setcookie('refresh_token', $refresh_token, [
    'expires' => time() + (60 * 60 * 24 * 7), // 7 tage
    'path' => '/',
    'httponly' => true,  // nicht per js lesbar
    'secure' => false,  // in prod auf true
    'samesite' => 'Strict' // Kein Cookie bei externem Request
]);

// -----------------------------
// 9. Antwort
// -----------------------------
echo json_encode([
    'message' => 'Registration successful',
    'token' => $jwt
]);
