<?php
require_once __DIR__ . '/../vendor/autoload.php';

// set_error_handler('ErrorHandler::handleError');
// set_exception_handler('ErrorHandler::handleException');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

error_reporting(E_ALL);
ini_set('display_errors', $_ENV['APP_DEBUG'] ?? true);

date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'UTC');

// Start session if needed
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>