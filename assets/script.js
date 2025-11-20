function showNotification(pesan, tipe = 'sukses') {
  const notif = document.getElementById('notification');
  notif.textContent = pesan;
  notif.className = `notification ${tipe === 'error' ? 'error' : ''} show`;
  setTimeout(() => {
    notif.classList.remove('show');
  }, 3000);
}

document.getElementById('daftarForm').onsubmit = async (e) => {
  e.preventDefault();

  const data = {
    jenis_kendaraan: document.getElementById('jenis_kendaraan').value,
    nomor_kendaraan: document.getElementById('nomor_kendaraan').value.trim(),
    nama_pengemudi: document.getElementById('nama_pengemudi').value.trim(),
    telepon: document.getElementById('telepon').value.trim()
  };

  if (!data.jenis_kendaraan || !data.nomor_kendaraan || !data.nama_pengemudi || !data.telepon) {
    showNotification('Semua kolom wajib diisi!', 'error');
    return;
  }

  try {
    const res = await fetch('api/daftar.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(data)
    });

    const hasil = await res.json();
    if (hasil.sukses) {
      showNotification(`Berhasil! Nomor antrian: ${hasil.nomor_antrian}`);
      setTimeout(() => {
        window.location.href = `dashboard-user.php?id=${hasil.id_antrian}&queue=${hasil.nomor_antrian}&vehicle=${encodeURIComponent(hasil.kendaraan)}&jenis=${encodeURIComponent(hasil.jenis_kendaraan)}`;
      }, 1500);
    } else {
      showNotification(hasil.pesan, 'error');
    }
  } catch (err) {
    showNotification('Gagal terhubung ke server', 'error');
  }
};