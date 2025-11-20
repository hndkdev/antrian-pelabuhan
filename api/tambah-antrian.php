<?php
// api/tambah-antrian.php
header('Content-Type: application/json');

// jangan keluarkan apa-apa sebelum header/json
session_start();

require __DIR__ . '/../config/db.php';

// safe response function
function resp($sukses, $pesan, $extra = []) {
    $out = array_merge(['sukses' => (bool)$sukses, 'pesan' => $pesan], $extra);
    echo json_encode($out);
    exit;
}

// pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    resp(false, 'Metode tidak diizinkan');
}

// pastikan user login (session harus diset di login-user.php)
if (!isset($_SESSION['user_id'])) {
    resp(false, 'Anda belum login');
}

try {
    $id_pengguna = (int) $_SESSION['user_id'];
    $nama_pengemudi = trim($_POST['nama_pengemudi'] ?? '');
    $no_kendaraan = trim($_POST['no_kendaraan'] ?? '');
    $jenis_kendaraan = trim($_POST['jenis_kendaraan'] ?? '');

    // validasi sederhana
    if ($id_pengguna <= 0 || $nama_pengemudi === '' || $no_kendaraan === '' || $jenis_kendaraan === '') {
        resp(false, 'Data tidak lengkap');
    }

    // cek antrian aktif
   $cek = $pdo->prepare("SELECT kode_antrian FROM antrian WHERE id_pengguna = ? AND status IN ('Menunggu', 'Dipanggil')");
    $cek->execute([$id_pengguna]);
    if ($cek->fetch(PDO::FETCH_ASSOC)) {
        resp(false, 'Anda sudah memiliki antrian aktif!');
    }

    // generate kode_antrian sederhana: prefix dari jenis + auto increment-like
    $prefixMap = [
        'Truck' => 'TRK',
        'Bus'   => 'BUS',
        'Mobil' => 'MBL',
        'Motor' => 'MOT'
    ];
    $prefix = $prefixMap[$jenis_kendaraan] ?? 'UNK';

    // dapatkan next id (opsional) — safer: insert dulu lalu ambil lastInsertId
    $stmt = $pdo->prepare("INSERT INTO antrian (kode_antrian, nama_pengemudi, no_kendaraan, telepon, jenis_kendaraan, status, waktu, id_pengguna)
                           VALUES (?, ?, ?, ?, ?, 'Menunggu', NOW(), ?)");
    // sementara telepon kosong (bisa tambah input nanti)
    $kode_antrian = $prefix . '-' . date('ymd') . '-' . rand(100,999);
    $telepon = trim($_POST['telepon'] ?? '');

    $stmt->execute([$kode_antrian, $nama_pengemudi, $no_kendaraan, $telepon, $jenis_kendaraan, $id_pengguna]);

    // ambil ID yang baru dibuat
    resp(true, 'Antrian berhasil diambil!', ['kode_antrian' => $kode_antrian]);

} catch (Exception $e) {
    // jangan print stack trace ke user di produksi; tapi untuk debugging sementara kirim pesan
    resp(false, 'Terjadi kesalahan server: ' . $e->getMessage());
}
