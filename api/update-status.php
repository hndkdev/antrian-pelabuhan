<?php
header('Content-Type: application/json');
require __DIR__ . '/../config/db.php';

$id_antrian = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$id_antrian || !$status) {
  echo json_encode(['sukses' => false, 'pesan' => 'Data tidak valid']);
  exit;
}

$stmt = $pdo->prepare("UPDATE antrian SET status = ? WHERE id_antrian = ?");
$ok = $stmt->execute([$status, $id_antrian]);

echo json_encode([
  'sukses' => $ok,
  'pesan' => $ok ? "Status antrian #$id_antrian diubah ke $status" : "Gagal mengubah status"
]);
?>
