<?php
require_once __DIR__ . '/../vendor/autoload.php';

// set_error_handler('ErrorHandler::handleError');
// set_exception_handler('ErrorHandler::handleException');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad(); # avoids fatal error if .env is missing

error_reporting(E_ALL);
ini_set('display_errors', $_ENV['APP_DEBUG'] ?? true);

date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'UTC');

// CORS and JSON headers for API responses
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Handle CORS preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Start session if needed
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>