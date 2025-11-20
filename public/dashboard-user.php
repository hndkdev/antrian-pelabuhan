<?php
session_start();

// ✅ Cek login
if (!isset($_SESSION['user_id'])) {
  header('Location: login-user.php');
  exit;
}

$user_id = (int)$_SESSION['user_id'];
$nama = $_SESSION['user_nama'] ?? 'Pengguna';

// ✅ Ambil data antrian
require __DIR__ . '/../config/db.php';
$stmt = $pdo->prepare("SELECT * FROM antrian WHERE id_pengguna = ? ORDER BY waktu DESC");
$stmt->execute([$user_id]);
$riwayat = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Pengguna | Antrian Pelabuhan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/qrious.min.js"></script>
  <style>
    body {
      background: linear-gradient(135deg, #007bff, #00aaff);
      min-height: 100vh;
      color: #fff;
      font-family: 'Poppins', sans-serif;
    }

    .navbar {
      background: rgba(0,0,0,0.2);
      backdrop-filter: blur(8px);
    }

    .card {
      border-radius: 20px;
      border: none;
      background: #fff;
      color: #333;
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      transition: 0.3s;
    }

    .table {
      border-radius: 12px;
      overflow: hidden;
      background: #fff;
    }

    .table thead th {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: white;
      border: none;
    }

    .table td {
      vertical-align: middle;
    }

    .btn-primary {
      background: linear-gradient(90deg, #00c6ff, #0072ff);
      border: none;
      font-weight: 600;
      transition: 0.3s;
    }

    footer {
      text-align: center;
      color: rgba(255,255,255,0.8);
      font-size: 13px;
      margin-top: 40px;
    }

    .clock {
      font-weight: bold;
      color: #fff;
    }
  </style>
</head>
<body>
  <!-- ✅ Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark shadow-sm px-3">
    <a class="navbar-brand fw-bold" href="#">🚗 Antrian Pelabuhan</a>
    <div class="ms-auto d-flex align-items-center">
      <span class="text-light me-3">🕒 <span id="clock"></span></span>
      <span class="text-light me-3">Halo, <?= htmlspecialchars($nama) ?> 👋</span>
      <a href="logout-user.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </nav>

  <div class="container py-5">
    <div class="text-center mb-4">
      <h2 class="fw-bold">📋 Status Antrian Anda</h2>
      <p class="text-light">Pantau dan buat antrian kendaraan Anda di sini</p>
    </div>

    <!-- ✅ Form ambil antrian -->
    <div class="card p-4 mb-5">
      <form id="formAntrian" class="row g-3">
        <input type="hidden" name="id_pengguna" value="<?= (int)$user_id ?>">

        <div class="col-md-6">
          <label class="form-label fw-semibold">Nama Pengemudi</label>
          <input type="text" class="form-control" name="nama_pengemudi" value="<?= htmlspecialchars($nama) ?>" readonly>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">No. Kendaraan</label>
          <input type="text" class="form-control" name="no_kendaraan" required placeholder="B1234XYZ">
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Jenis Kendaraan</label>
          <select class="form-select" name="jenis_kendaraan" required>
            <option value="">-- Pilih Jenis Kendaraan --</option>
            <option value="Truck">Truck</option>
            <option value="Bus">Bus</option>
            <option value="Mobil">Mobil</option>
            <option value="Motor">Motor</option>
          </select>
        </div>

        <div class="col-md-6 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100 fw-bold">🚀 Ambil Antrian</button>
        </div>
      </form>
    </div>

    <!-- ✅ Riwayat antrian -->
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center table-hover">
        <thead>
          <tr>
            <th>No</th>
            <th>Kode Antrian</th>
            <th>No. Kendaraan</th>
            <th>Jenis</th>
            <th>Status</th>
            <th>Waktu</th>
            <th>QR Code</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($riwayat) > 0): ?>
            <?php foreach ($riwayat as $i => $row): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($row['kode_antrian']) ?></td>
                <td><?= htmlspecialchars($row['no_kendaraan'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['jenis_kendaraan'] ?? '') ?></td>
                <td>
                  <?php 
                    $statusClass = match($row['status'] ?? '') {
                      'Menunggu' => 'text-secondary',
                      'Dipanggil' => 'text-warning fw-bold',
                      'Diverifikasi' => 'text-primary fw-bold',
                      'Selesai' => 'text-success fw-bold',
                      default => 'text-dark'
                    };
                  ?>
                  <span class="<?= $statusClass ?>"><?= htmlspecialchars($row['status'] ?? '') ?></span>
                </td>
                <td><?= date('H:i:s d/m/Y', strtotime($row['waktu'])) ?></td>
                <td><canvas class="qr-canvas" data-content="<?= htmlspecialchars($row['kode_antrian']) ?>" width="60" height="60"></canvas></td>
                <td>
                  <button 
                    class="btn btn-info btn-sm"
                    onclick="lihatDetail(
                      '<?= htmlspecialchars($row['kode_antrian']) ?>',
                      '<?= htmlspecialchars($row['no_kendaraan']) ?>',
                      '<?= htmlspecialchars($row['jenis_kendaraan']) ?>',
                      '<?= htmlspecialchars($row['status']) ?>'
                    )"
                  >👁️</button>
                  <button class="btn btn-danger btn-sm"
  onclick="hapusRiwayat('<?= htmlspecialchars($row['kode_antrian']) ?>')">
  🗑️ Hapus
</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="8" class="text-muted">Belum ada antrian 😅</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <footer>© <?= date('Y') ?> Aplikasi Antrian Pelabuhan - Semua Hak Dilindungi</footer>

  <canvas id="qrDetailCanvas" width="200" height="200" style="display:none;"></canvas>

  <script>
    // ✅ Jam realtime
    setInterval(() => {
      const now = new Date();
      document.getElementById('clock').innerText = now.toLocaleTimeString();
    }, 1000);

    // ✅ Generate QR code
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.qr-canvas').forEach(canvas => {
        new QRious({ element: canvas, value: canvas.dataset.content, size: 60 });
      });
    });

    // ✅ Tambah antrian (FIXED)
    document.getElementById('formAntrian')?.addEventListener('submit', async e => {
      e.preventDefault();
      const formData = new FormData(e.target);
      const res = await fetch('../api/tambah-antrian.php', { 
        method: 'POST', 
        body: formData,
        credentials: 'include' // 🔥 penting agar session ikut terkirim
      });
      const data = await res.json();
      if (data.sukses) {
        Swal.fire({ icon: 'success', title: data.pesan, timer: 1500, showConfirmButton: false })
          .then(() => location.reload());
      } else {
        Swal.fire({ icon: 'error', title: 'Gagal', text: data.pesan });
      }
    });

    // ✅ Lihat detail QR
    function lihatDetail(kode, noKendaraan, jenis, status) {
      const qrCanvas = document.getElementById('qrDetailCanvas');
      new QRious({ element: qrCanvas, value: kode, size: 200 });
      setTimeout(() => {
        Swal.fire({
          title: `📋 Detail Antrian ${kode}`,
          html: `
            <div style="text-align:left;line-height:1.8">
              <b>Kode:</b> ${kode}<br>
              <b>No. Kendaraan:</b> ${noKendaraan}<br>
              <b>Jenis Kendaraan:</b> ${jenis}<br>
              <b>Status:</b> <span class="badge bg-primary">${status}</span><br><br>
              <center><img src="${qrCanvas.toDataURL()}" alt="QR" width="200" height="200"></center>
            </div>
          `,
          confirmButtonText: 'Tutup',
          width: 350
        });
      }, 100);
    }

    // ✅ Hapus riwayat
function hapusRiwayat(kode) {
  Swal.fire({
    title: 'Hapus antrian ini?',
    text: 'Data tidak bisa dikembalikan!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal'
  }).then(async (res) => {
    if (res.isConfirmed) {
      const formData = new FormData();
      formData.append('kode', kode);
      const req = await fetch('../api/hapus-antrian.php', { method: 'POST', body: formData });
      const data = await req.json();
      Swal.fire({ icon: data.sukses ? 'success' : 'error', title: data.pesan, timer: 1500, showConfirmButton: false });
      if (data.sukses) setTimeout(() => location.reload(), 800);
    }
  });
}
  </script>
</body>
</html>