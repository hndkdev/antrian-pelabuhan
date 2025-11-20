<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Antrian Pelabuhan</title>
  <style>
    /* RESET DASAR */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      height: 100vh;
      background: linear-gradient(135deg, #0072ff, #8e2de2);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* KONTENER UTAMA */
    .container {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      padding: 40px 50px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
      color: white;
      max-width: 420px;
      width: 100%;
      transition: all 0.3s ease;
    }

    h1 {
      font-size: 26px;
      margin-bottom: 15px;
      line-height: 1.3;
    }

    p {
      margin-bottom: 25px;
      font-size: 16px;
      opacity: 0.9;
    }

    /* TOMBOL UTAMA */
    .btn {
      display: block;
      width: 100%;
      margin: 10px 0;
      padding: 14px 0;
      font-size: 16px;
      font-weight: 600;
      color: #fff;
      background: linear-gradient(90deg, #4facfe, #00f2fe);
      border: none;
      border-radius: 50px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      position: relative;
      overflow: hidden;
      animation: glowPulse 2.5s infinite ease-in-out;
    }

    /* Efek Glow Berdenyut */
    @keyframes glowPulse {
      0% { box-shadow: 0 0 8px rgba(79, 172, 254, 0.5); }
      50% { box-shadow: 0 0 18px rgba(79, 172, 254, 0.9); }
      100% { box-shadow: 0 0 8px rgba(79, 172, 254, 0.5); }
    }

    /* Efek Hover Modern */
    .btn:hover {
      transform: scale(1.07);
      background: linear-gradient(90deg, #00f2fe, #4facfe);
      box-shadow: 0 0 25px rgba(79, 172, 254, 0.9);
    }

    /* Efek Ripple */
    .btn::after {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      background: rgba(255, 255, 255, 0.4);
      border-radius: 50%;
      transform: translate(-50%, -50%);
      opacity: 0;
      transition: width 0.4s ease, height 0.4s ease, opacity 0.4s ease;
    }

    .btn:active::after {
      width: 200%;
      height: 200%;
      opacity: 0;
      transition: 0s;
    }

    footer {
      margin-top: 20px;
      font-size: 13px;
      opacity: 0.8;
    }

    /* ===================== */
    /* RESPONSIF UNTUK MOBILE */
    /* ===================== */
    @media (max-width: 480px) {
      .container {
        padding: 30px 25px;
        border-radius: 16px;
      }

      h1 {
        font-size: 22px;
      }

      p {
        font-size: 15px;
      }

      .btn {
        font-size: 15px;
        padding: 12px 0;
      }

      footer {
        font-size: 12px;
      }
    }

    /* RESPONSIF UNTUK LAYAR BESAR */
    @media (min-width: 992px) {
      h1 {
        font-size: 28px;
      }
      .container {
        max-width: 450px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🚢 Aplikasi Antrian Pelabuhan</h1>
    <p>Selamat datang! Silakan pilih login sebagai:</p>
    <a href="login-user.php" class="btn">👤 Pengguna</a>
    <a href="login-petugas.php" class="btn">🧭 Petugas</a>
    <a href="about-me.php" class="btn">ℹ️Tentang Aplikasi</a>
    <footer>© <?= date('Y'); ?>  Sistem Antrian Pelabuhan Kendaraan</footer>
  </div>
</body>
</html>