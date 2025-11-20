<?php
$host = 'localhost';
$db   = 'antrian_pelabuhan';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die(json_encode(['sukses' => false, 'pesan' => 'Koneksi gagal: ' . $e->getMessage()]));
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
