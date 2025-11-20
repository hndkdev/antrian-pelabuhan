<?php
header('Content-Type: application/json');
require __DIR__ . '/../config/db.php';
session_start();

$id_pengguna = $_SESSION['user_id'] ?? null;
$nama = $_POST['nama'] ?? null;

// kalau user belum login, bisa tetap daftar jika sistem mau; di contoh ini butuh login
if (!$id_pengguna) {
    echo json_encode(['sukses' => false, 'pesan' => 'Login terlebih dahulu']);
    exit;
}

// bikin nomor otomatis: tanggal + counter (simple)
$prefix = date('Ymd');
$stmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM antrian WHERE DATE(waktu_ambil) = CURDATE()");
$stmt->execute();
$row = $stmt->fetch();
$counter = $row ? $row['cnt'] + 1 : 1;
$nomor = $prefix . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);

try {
  $ins = $pdo->prepare("INSERT INTO antrian (id_pengguna, nomor_antrian) VALUES (?, ?)");
  $ins->execute([$id_pengguna, $nomor]);
  echo json_encode(['sukses' => true, 'pesan' => 'Ambil antrian berhasil', 'nomor' => $nomor]);
} catch (PDOException $e) {
  echo json_encode(['sukses' => false, 'pesan' => 'Kesalahan DB: '.$e->getMessage()]);
}
?>
