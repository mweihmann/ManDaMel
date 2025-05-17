<?php
// Datenbankverbindungsdaten
$host = "mandamel-db";
$port = "3306";
$dbname = "mandamel";
$username = "mandamel";
$password = "mandamel";

try {
    // Verbindung zur Datenbank aufbauen (mit PDO)
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);

    // Fehler als Exception ausgeben lassen
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Erfolgreiche Verbindung in der Konsole ausgeben
    echo "<script>console.log('Connected successfully!');</script>";
} catch (PDOException $e) {
    // Fehlermeldung in der Konsole ausgeben, wenn Verbindung fehlschlägt
    echo "<script>console.error('Connection failed: " . addslashes($e->getMessage()) . "');</script>";
}
?>
