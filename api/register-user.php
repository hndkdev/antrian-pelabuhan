<?php
header('Content-Type: application/json');
require __DIR__ . '/../config/db.php';

$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$nama || !$username || !$password) {
    echo json_encode(['sukses' => false, 'pesan' => 'Lengkapi semua data']);
    exit;
}

// cek username
$cek = $pdo->prepare("SELECT id FROM pengguna WHERE username = ?");
$cek->execute([$username]);
if ($cek->fetch()) {
    echo json_encode(['sukses' => false, 'pesan' => 'Username sudah terdaftar']);
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO pengguna (nama, username, password) VALUES (?, ?, ?)");
    $stmt->execute([$nama, $username, $hashed]);
    echo json_encode(['sukses' => true, 'pesan' => 'Registrasi berhasil']);
} catch (PDOException $e) {
    echo json_encode(['sukses' => false, 'pesan' => 'Kesalahan DB: '.$e->getMessage()]);
}
?>
