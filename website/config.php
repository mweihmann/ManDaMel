<?php
$host = "mandamel-db";
$port = "3306";
$dbname = "mandamel";
$username = "mandamel";
$password = "mandamel";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<script>console.log('Connected successfully!');</script>";
} catch (PDOException $e) {
    echo "<script>console.error('Connection failed: " . addslashes($e->getMessage()) . "');</script>";
}
?>
