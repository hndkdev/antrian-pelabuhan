<?php
header('Content-Type: application/json');
session_start();
require __DIR__ . '/../config/db.php';

function resp($ok, $msg) {
    echo json_encode(['sukses' => $ok, 'pesan' => $msg]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    resp(false, 'Metode tidak diizinkan');
}

if (!isset($_SESSION['user_id'])) {
    resp(false, 'Anda belum login');
}

$kode = strtoupper(trim($_POST['kode'] ?? ''));
if ($kode === '') resp(false, 'Kode tidak valid');

try {
    // cek dulu apakah data benar milik user
    $cek = $pdo->prepare("SELECT * FROM antrian WHERE kode_antrian = ? AND id_pengguna = ?");
    $cek->execute([$kode, $_SESSION['user_id']]);
    $data = $cek->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        resp(false, "Data tidak ditemukan untuk kode: $kode");
    }

    // hapus jika ketemu
    $stmt = $pdo->prepare("DELETE FROM antrian WHERE kode_antrian = ? AND id_pengguna = ?");
    $stmt->execute([$kode, $_SESSION['user_id']]);

    resp(true, 'Riwayat antrian berhasil dihapus!');

} catch (Exception $e) {
    resp(false, 'Terjadi kesalahan server: ' . $e->getMessage());
}
