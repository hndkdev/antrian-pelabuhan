<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Pengguna | Antrian Pelabuhan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* RESET */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      height: 100vh;
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      animation: fadeIn 1.2s ease-in-out;
      padding: 20px;
    }

    @keyframes fadeIn {
      from {opacity:0; transform: translateY(-10px);}
      to {opacity:1; transform: translateY(0);}
    }

    form {
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(12px);
      padding: 35px;
      border-radius: 18px;
      width: 100%;
      max-width: 340px;
      text-align: center;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
      transition: all 0.3s ease;
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
      background: linear-gradient(90deg, #4facfe, #00f2fe);
      color: white;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 0 10px rgba(79, 172, 254, 0.6);
      animation: glowPulse 2.5s infinite ease-in-out;
    }

    @keyframes glowPulse {
      0% { box-shadow: 0 0 8px rgba(79, 172, 254, 0.5); }
      50% { box-shadow: 0 0 18px rgba(79, 172, 254, 0.9); }
      100% { box-shadow: 0 0 8px rgba(79, 172, 254, 0.5); }
    }

    button:hover {
      transform: scale(1.05);
      background: linear-gradient(90deg, #00f2fe, #4facfe);
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
  <form id="loginUser">
    <h2>Login Pengguna</h2>

    <div class="input-group">
      <input type="text" name="username" placeholder="Username" required>
    </div>

    <div class="input-group">
      <input type="password" name="password" id="password" placeholder="Password" required>
      <span class="toggle-password" id="togglePassword">👁️</span>
    </div>

    <button type="submit">Masuk</button>
    <p>Belum punya akun? <a href="register-user.php">Daftar di sini</a></p>

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

    // ===== Handle Login Request =====
    document.getElementById("loginUser").addEventListener("submit", async (e) => {
      e.preventDefault();

      const res = await fetch("../api/login-user.php", {
        method: "POST",
        body: new FormData(e.target)
      });

      const data = await res.json();

      if (data.sukses) {
        Swal.fire({
          icon: 'success',
          title: 'Login Berhasil!',
          timer: 1500,
          showConfirmButton: false
        }).then(() => window.location.href = "dashboard-user.php");
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Gagal Login',
          text: data.pesan
        });
      }
    });
  </script>
</body>
</html>
