<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Pengguna | Antrian Pelabuhan</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      height: 100vh;
      background: linear-gradient(135deg, #141e30, #243b55);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    form {
      background: rgba(255,255,255,0.12);
      backdrop-filter: blur(12px);
      padding: 35px;
      border-radius: 18px;
      width: 100%;
      max-width: 360px;
      text-align: center;
      box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    }

    h2 {
      margin-bottom: 20px;
      font-size: 24px;
    }

    .input-group {
      position: relative;
      margin-bottom: 15px;
    }

    input {
      width: 100%;
      padding: 12px 40px 12px 12px;
      border: none;
      border-radius: 10px;
      outline: none;
      font-size: 15px;
      color: #333;
    }

    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #333;
      opacity: 0.7;
      transition: 0.3s;
    }

    .toggle-password:hover {
      opacity: 1;
      transform: translateY(-50%) scale(1.2);
    }

    button {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      border: none;
      border-radius: 50px;
      background: linear-gradient(90deg, #ff512f, #dd2476);
      color: white;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      animation: glowPulse 2.5s infinite ease-in-out;
    }

    @keyframes glowPulse {
      0% { box-shadow: 0 0 8px rgba(221, 36, 118, 0.5); }
      50% { box-shadow: 0 0 18px rgba(221, 36, 118, 0.9); }
      100% { box-shadow: 0 0 8px rgba(221, 36, 118, 0.5); }
    }

    button:hover {
      transform: scale(1.05);
      background: linear-gradient(90deg, #dd2476, #ff512f);
    }

    p {
      margin-top: 15px;
      font-size: 14px;
    }

    a {
      color: #00c6ff;
      text-decoration: none;
      transition: 0.3s;
    }

    a:hover {
      text-decoration: underline;
    }

    footer {
      margin-top: 20px;
      font-size: 13px;
      opacity: 0.7;
    }

    /* Responsif */
    @media (max-width: 480px) {
      form {
        padding: 28px 25px;
      }
      h2 {
        font-size: 22px;
      }
      input, button {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>
  <form id="registerUser">
    <h2>Daftar Pengguna</h2>

    <div class="input-group">
      <input type="text" name="nama" placeholder="Nama Lengkap" required>
    </div>

    <div class="input-group">
      <input type="text" name="username" placeholder="Username" required>
    </div>

    <div class="input-group">
      <input type="password" name="password" id="password" placeholder="Password" required>
      <span class="toggle-password" id="togglePassword">👁️</span>
    </div>

    <button type="submit">Daftar</button>
    <p>Sudah punya akun? <a href="login-user.php">Login</a></p>

    <footer>© <?= date('Y'); ?> Antrian Pelabuhan Created by Kelompok</footer>
  </form>

  <script>
    // ===== Toggle Password Show/Hide =====
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");

    togglePassword.addEventListener("click", () => {
      const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
      passwordField.setAttribute("type", type);
      togglePassword.textContent = type === "password" ? "👁️" : "🙈";
    });

    // ===== Handle Register Request =====
    document.getElementById("registerUser").addEventListener("submit", async (e) => {
      e.preventDefault();

      const res = await fetch("../api/register-user.php", {
        method: "POST",
        body: new FormData(e.target)
      });

      const data = await res.json();

      if (data.sukses) {
        Swal.fire({
          icon: 'success',
          title: 'Registrasi Berhasil!',
          timer: 1500,
          showConfirmButton: false
        }).then(() => window.location.href = "login-user.php");
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Gagal Registrasi',
          text: data.pesan
        });
      }
    });
  </script>
</body>
</html>
