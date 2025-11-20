<?php
session_start();
if (!isset($_SESSION['petugas_id'])) {
    http_response_code(403);
    exit;
}

header('Content-Type: application/json');
require 'db.php';

$pdo->exec("DELETE FROM antrian");
$pdo->exec("ALTER TABLE antrian AUTO_INCREMENT = 1");

echo json_encode(['sukses' => true, 'pesan' => 'Semua data antrian berhasil dihapus']);
?>