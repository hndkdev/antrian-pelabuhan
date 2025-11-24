<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tentang Aplikasi - Sistem Antrian Pelabuhan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #007bff, #00aaff);
      color: #fff;
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
    }
    .card {
      background: #fff;
      color: #333;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      padding: 30px;
    }
    h1, h3 {
      font-weight: 700;
    }
    .navbar {
      box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="#">🚢 Sistem Antrian Pelabuhan</a>
      <div class="d-flex">
        <a href="index.php" class="btn btn-light btn-sm me-2">🏠 Beranda</a>
      </div>
    </div>
  </nav>

  <!-- Konten -->
  <div class="container py-5">
    <div class="card mx-auto" style="max-width: 800px;">
      <h1 class="text-center text-primary mb-4">Tentang Aplikasi</h1>
      <p>
        <b>Sistem Antrian Pelabuhan Kendaraan</b> merupakan proyek aplikasi berbasis web
        yang dirancang untuk membantu proses pengelolaan dan pemantauan antrian kendaraan
        di area pelabuhan secara digital dan efisien.
      </p>

      <h3 class="mt-4 text-primary">🎯 Tujuan Proyek</h3>
      <ul>
        <li>Meningkatkan efisiensi pengelolaan antrian kendaraan di pelabuhan.</li>
        <li>Memberikan kemudahan bagi pengguna dalam mengambil dan memantau status antrian secara real-time.</li>
        <li>Mendukung proses pembelajaran mata kuliah <b>Teknik Simulasi dan Pemodelan</b> dengan penerapan sistem simulasi antrian nyata.</li>
      </ul>

      <h3 class="mt-4 text-primary">⚙️ Teknologi yang Digunakan</h3>
      <ul>
        <li><b>Frontend:</b> HTML5, CSS3, Bootstrap 5, dan JavaScript</li>
        <li><b>Backend:</b> PHP 8 (Native, tanpa framework)</li>
        <li><b>Database:</b> MySQL / MariaDB</li>
        <li><b>Hosting:</b> InfinityFree (Free Web Hosting)</li>
      </ul>

      <h3 class="mt-4 text-primary">ℹ️Tentang Pengembang</h3>
      <p>
        Aplikasi ini dikembangkan oleh Kelompok 12 bagian
        <br> Proyek ini menjadi bentuk penerapan konsep simulasi antrian dalam dunia nyata
        menggunakan pendekatan sistem informasi digital.</br>
      </p>

      <div class="text-center mt-4">
        <a href="index.php" class="btn btn-outline-primary">⬅️ Kembali ke Dashboard</a>
      </div>
    </div>
  </div>

  <footer class="text-center text-light mt-5 mb-3">
    <small>© <?= date('Y') ?> Sistem Antrian Pelabuhan Kendaraan</small>
  </footer>
</body>
</html>
