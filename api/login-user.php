<?php
header('Content-Type: application/json');
require __DIR__ . '/../config/db.php';
session_start();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
  echo json_encode(['sukses' => false, 'pesan' => 'Isi username dan password']);
  exit;
}

$stmt = $pdo->prepare("SELECT id, password, nama FROM pengguna WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['user_nama'] = $user['nama'];
  echo json_encode(['sukses' => true, 'pesan' => 'Login berhasil']);
} else {
  echo json_encode(['sukses' => false, 'pesan' => 'Username atau password salah']);
}
?>
