<?php
session_start();
require __DIR__ . '/../config/db.php';

$username = trim($_POST['username'] ?? '');
$passwordInput = trim($_POST['password'] ?? '');

if (!$username || !$passwordInput) {
  echo json_encode(['sukses' => false, 'pesan' => 'Isi username dan password']);
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM petugas WHERE username = ?");
$stmt->execute([$username]);
$petugas = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$petugas) {
  echo json_encode(['sukses' => false, 'pesan' => 'Username tidak ditemukan']);
  exit;
}

// ✅ Verifikasi password yang sudah di-hash di DB
if (password_verify($passwordInput, $petugas['password'])) {
  $_SESSION['petugas_id'] = $petugas['id'];
  $_SESSION['petugas_nama'] = $petugas['nama'];
  $_SESSION['role'] = 'petugas';

  echo json_encode(['sukses' => true, 'pesan' => 'Login berhasil']);
} else {
  echo json_encode(['sukses' => false, 'pesan' => 'Password salah']);
}
