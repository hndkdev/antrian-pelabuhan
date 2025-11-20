<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Petugas | Antrian Pelabuhan</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      height: 100vh;
      background: linear-gradient(135deg, #00c6ff, #0072ff);
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: "Poppins", sans-serif;
      animation: fadeIn 1s ease;
    }
    @keyframes fadeIn { from {opacity: 0;} to {opacity: 1;} }
    .card {
      width: 400px;
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      padding: 30px;
      animation: floatUp 1s ease;
      background-color: white;
    }
    @keyframes floatUp { from {transform: translateY(30px); opacity: 0;} to {transform: translateY(0); opacity: 1;} }
    .btn-primary {
      background: linear-gradient(90deg, #00c6ff, #0072ff);
      border: none;
      transition: 0.3s;
      font-weight: 600;
    }
    .btn-primary:hover {
      box-shadow: 0 0 15px #00c6ff;
      background: linear-gradient(90deg, #0072ff, #00c6ff);
    }
    .password-container {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
      color: gray;
    }
  </style>
</head>
<body>
  <div class="card text-center">
    <h3 class="mb-4">🧾 Login Petugas</h3>
    <form id="loginPetugas">
      <div class="mb-3 text-start">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3 text-start password-container">
        <label>Password</label>
        <input type="password" name="password" class="form-control" id="password" required>
        <span class="toggle-password" id="togglePassword">👁️</span>
      </div>
      <button type="submit" class="btn btn-primary w-100">Masuk</button>
      <footer class="mt-3 text-muted" style="font-size:13px;">© <?= date('Y'); ?> Antrian Pelabuhan</footer>
    </form>
  </div>

  <script>
  // ✅ Toggle lihat/sembunyikan password
  const toggle = document.getElementById('togglePassword');
  const pass = document.getElementById('password');
  toggle.addEventListener('click', () => {
    const type = pass.getAttribute('type') === 'password' ? 'text' : 'password';
    pass.setAttribute('type', type);
    toggle.textContent = type === 'password' ? '👁️' : '🙈';
  });

  // ✅ Form login petugas
  document.getElementById("loginPetugas").addEventListener("submit", async (e) => {
    e.preventDefault();
    const res = await fetch("../api/login-petugas.php", {
      method: "POST",
      body: new FormData(e.target)
    });
    const data = await res.json();
    if (data.sukses) {
      Swal.fire({icon:'success', title:'Login Berhasil!', timer:1500, showConfirmButton:false})
        .then(()=> window.location.href="dashboard-petugas.php");
    } else {
      Swal.fire({icon:'error', title:'Gagal Login', text:data.pesan});
    }
  });
  </script>
</body>
</html>
