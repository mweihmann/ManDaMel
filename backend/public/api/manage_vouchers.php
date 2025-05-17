<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../businesslogic/VoucherLogic.php';

header('Content-Type: application/json');

$payload = authenticate();

if ($payload->role !== 'admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Zugriff verweigert']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        echo json_encode(VoucherLogic::getAll());
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $ok = VoucherLogic::create($data['code'], $data['value'], $data['expires_at']);
        echo json_encode(['success' => $ok]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $ok = VoucherLogic::update($data['id'], $data['code'], $data['value'], $data['expires_at']);
        echo json_encode(['success' => $ok]);
        break;

    // delete makes no sense to use without deleting info in the orders
    // case 'DELETE':
    //     parse_str(file_get_contents("php://input"), $data);
    //     $ok = VoucherLogic::delete((int)($data['id'] ?? 0));
    //     echo json_encode(['success' => $ok]);
    //     break;

    case 'PATCH':
        $data = json_decode(file_get_contents('php://input'), true);
        $ok = VoucherLogic::markAsUsed((int)($data['id'] ?? 0));
        echo json_encode(['success' => $ok]);
        break;

    
    default:
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Nicht erlaubt']);
}