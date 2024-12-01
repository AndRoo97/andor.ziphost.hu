<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbHost = 'localhost';
$dbUser = 'andor';
$dbPass = 'EUEOpRKLdKvy3YMU';
$dbName = 'andor';

$db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($db->connect_error) {
    die("Adatbázis kapcsolat hiba (MySQLi): " . $db->connect_error);
}

$db->set_charset('utf8');

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Adatbázis kapcsolat hiba (PDO): " . $e->getMessage());
}
?>
