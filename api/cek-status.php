<?php
header('Content-Type: application/json');
require 'db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    echo json_encode(['sukses' => false]);
    exit;
}

$stmt = $pdo->prepare("SELECT status FROM antrian WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    echo json_encode(['sukses' => false]);
    exit;
}

$status = $data['status'];
$pesan = match($status) {
    'Menunggu' => '⏳ Sedang menunggu giliran',
    'Dipanggil' => '🚨 Giliran Anda! Segera menuju gerbang',
    'Diverifikasi' => '✅ Kendaraan telah diverifikasi',
    'Selesai' => '✔️ Proses antrian selesai'
};

echo json_encode(['sukses' => true, 'status' => $status, 'pesan' => $pesan]);
?>