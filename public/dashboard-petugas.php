<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require __DIR__ . '/../config/db.php';

// ✅ Cek login session
if (!isset($_SESSION['petugas_id'])) {
  header('Location: login-petugas.php');
  exit;
}

if (!$pdo) die("❌ Koneksi database gagal!");

// ✅ Ambil statistik ringkas
$stat = $pdo->query("
  SELECT 
    COUNT(*) AS total,
    SUM(status='Menunggu') AS menunggu,
    SUM(status='Dipanggil') AS dipanggil,
    SUM(status='Diverifikasi') AS diverifikasi,
    SUM(status='Selesai') AS selesai
  FROM antrian
")->fetch(PDO::FETCH_ASSOC);

// ✅ Ambil data antrian
try {
  $stmt = $pdo->query("
    SELECT id_antrian, id_pengguna, kode_antrian, nama_pengemudi, no_kendaraan, jenis_kendaraan, status,
           DATE_FORMAT(waktu, '%d-%m-%Y %H:%i') AS waktu
    FROM antrian
    ORDER BY waktu ASC
  ");
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
  $data = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Petugas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: linear-gradient(135deg, #e3f2fd, #bbdefb);
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
    }
    .navbar {
      background: linear-gradient(90deg, #007bff, #00aaff);
    }
    .card {
      border: none;
      border-radius: 18px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      animation: fadeIn 0.6s ease;
    }
    @keyframes fadeIn { from {opacity: 0; transform: translateY(20px);} to {opacity: 1; transform: translateY(0);} }
    table thead {
      background: linear-gradient(90deg, #007bff, #00aaff);
      color: #fff;
    }
    table tbody tr:hover {
      background-color: #f8fbff;
    }
    th, td {
      vertical-align: middle !important;
    }
    .badge {
      padding: 0.45em 0.8em;
      font-size: 0.85rem;
      border-radius: 0.5rem;
    }
    .btn-sm {
      padding: 5px 10px;
      font-size: 0.85rem;
      border-radius: 6px;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-dark shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="#">🚢 Dashboard Petugas</a>
      <div class="d-flex align-items-center">
        <span class="text-light me-3">Halo, <?= htmlspecialchars($_SESSION['petugas_nama'] ?? 'Petugas', ENT_QUOTES) ?> 👋</span>
        <a href="logout.php" class="btn btn-danger btn-sm fw-semibold">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container py-5">

    <!-- ✅ Statistik Ringkas -->
    <div class="row text-center mb-4">
      <div class="col-md-3 mb-3">
        <div class="card p-3 bg-primary text-white shadow">
          <h6>Total Antrian</h6>
          <h3><?= $stat['total'] ?? 0 ?></h3>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card p-3 bg-warning text-dark shadow">
          <h6>Menunggu</h6>
          <h3><?= $stat['menunggu'] ?? 0 ?></h3>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card p-3 bg-info text-white shadow">
          <h6>Dipanggil</h6>
          <h3><?= $stat['dipanggil'] ?? 0 ?></h3>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card p-3 bg-success text-white shadow">
          <h6>Selesai</h6>
          <h3><?= $stat['selesai'] ?? 0 ?></h3>
        </div>
      </div>
    </div>

    <h3 class="text-center fw-bold text-primary mb-4">📋 Data Antrian Kendaraan</h3>

    <!-- ✅ Filter dan Pencarian -->
    <div class="d-flex justify-content-between mb-3 flex-wrap">
      <select id="filterStatus" class="form-select w-auto" onchange="filterStatus()">
        <option value="">Semua Status</option>
        <option>Menunggu</option>
        <option>Dipanggil</option>
        <option>Diverifikasi</option>
        <option>Selesai</option>
      </select>
      <input type="text" id="searchInput" class="form-control w-auto" placeholder="Cari nama / kendaraan..." onkeyup="cariAntrian()">
    </div>

    <!-- ✅ Tabel Antrian -->
    <div class="card p-4">
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Nama Pengemudi</th>
              <th>No. Kendaraan</th>
              <th>Jenis</th>
              <th>Status</th>
              <th>Waktu</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="antrianTableBody">
            <?php if (!empty($data)): ?>
              <?php foreach ($data as $i => $row): 
                $status = $row['status'] ?? 'Tidak Diketahui';
                $badge_class = match($status) {
                  'Menunggu' => 'bg-warning text-dark',
                  'Dipanggil' => 'bg-info text-dark',
                  'Diverifikasi' => 'bg-primary',
                  'Selesai' => 'bg-success',
                  default => 'bg-secondary'
                };
              ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($row['kode_antrian'], ENT_QUOTES) ?></td>
                <td><?= htmlspecialchars($row['nama_pengemudi'] ?? '—', ENT_QUOTES) ?></td>
                <td><?= htmlspecialchars($row['no_kendaraan'] ?? '—', ENT_QUOTES) ?></td>
                <td><?= htmlspecialchars($row['jenis_kendaraan'] ?? '—', ENT_QUOTES) ?></td>
                <td><span class="badge <?= $badge_class ?>"><?= htmlspecialchars($status, ENT_QUOTES) ?></span></td>
                <td><?= htmlspecialchars($row['waktu'] ?? '—', ENT_QUOTES) ?></td>
                <td>
                  <?php if ($status == 'Menunggu'): ?>
                    <button class="btn btn-info btn-sm text-white" onclick="ubahStatus(<?= $row['id_antrian'] ?>, 'Dipanggil')">🔊 Panggil</button>
                  <?php elseif ($status == 'Dipanggil'): ?>
                    <button class="btn btn-warning btn-sm" onclick="ubahStatus(<?= $row['id_antrian'] ?>, 'Diverifikasi')">✅ Verifikasi</button>
                  <?php elseif ($status == 'Diverifikasi'): ?>
                    <button class="btn btn-success btn-sm" onclick="ubahStatus(<?= $row['id_antrian'] ?>, 'Selesai')">🏁 Selesai</button>
                  <?php elseif ($status == 'Selesai'): ?>
                    <button class="btn btn-danger btn-sm" onclick="hapusAntrian(<?= $row['id_antrian'] ?>)">🗑️ Hapus</button>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-muted py-4">Belum ada data antrian 😅</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    
    function ubahStatus(id, status) {
      fetch('../api/update-status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ id, status })
      })
      .then(r => r.json())
      .then(d => {
        Swal.fire({
          icon: d.sukses ? 'success' : 'error',
          title: d.pesan,
          timer: 1200,
          showConfirmButton: false
        }).then(() => location.reload());
      })
      .catch(() => {
        Swal.fire({ icon: 'error', title: 'Gagal konek ke server 😅', timer: 1500, showConfirmButton: false });
      });
    }

    function hapusAntrian(id) {
      Swal.fire({
        title: 'Yakin mau hapus?',
        text: 'Data ini akan hilang permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((res) => {
        if (res.isConfirmed) {
          fetch('../api/hapus-antrian.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ id })
          })
          .then(r => r.json())
          .then(d => {
            Swal.fire({
              icon: d.sukses ? 'success' : 'error',
              title: d.pesan,
              timer: 1300,
              showConfirmButton: false
            }).then(() => location.reload());
          });
        }
      });
    }

    function cariAntrian() {
      const filter = document.getElementById('searchInput').value.toLowerCase();
      document.querySelectorAll('tbody tr').forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    }

    function filterStatus() {
      const val = document.getElementById('filterStatus').value.toLowerCase();
      document.querySelectorAll('tbody tr').forEach(row => {
        const status = row.querySelector('.badge').innerText.toLowerCase();
        row.style.display = !val || status.includes(val) ? '' : 'none';
      });
    }
  </script>
</body>
</html>
