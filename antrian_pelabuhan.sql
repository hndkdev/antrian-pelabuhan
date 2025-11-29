-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Nov 2025 pada 06.18
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `antrian_pelabuhan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `antrian`
--

CREATE TABLE `antrian` (
  `id_antrian` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `kode_antrian` varchar(11) DEFAULT NULL,
  `nama_pengemudi` varchar(100) DEFAULT NULL,
  `no_kendaraan` varchar(50) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `jenis_kendaraan` enum('Truck','Bus','Mobil','Motor') DEFAULT NULL,
  `status` enum('Menunggu','Dipanggil','Diverifikasi','Selesai') DEFAULT 'Menunggu',
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `antrian`
--

INSERT INTO `antrian` (`id_antrian`, `id_pengguna`, `kode_antrian`, `nama_pengemudi`, `no_kendaraan`, `telepon`, `jenis_kendaraan`, `status`, `waktu`) VALUES
(13, 2, 'TRK-251118', 'Muhamad Handika', 'B 1234 CRS', '', 'Truck', 'Dipanggil', '2025-11-18 02:57:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `username`, `password`, `tanggal_daftar`) VALUES
(2, 'Muhamad Handika', 'handika', '$2y$10$G9sOwsl5E6AWRQnC.CWFp.me7UaFR94hUHBhW8VqAL7ePVwPlWWji', '2025-10-24 14:51:42'),
(3, 'Muhammad azzam', 'azzam', '$2y$10$g14I/PE9A30gB52gaK2lTOB8l2P7jS2ANOrqkJ9lX5ZgwC/bIqTv2', '2025-10-26 04:48:46'),
(4, 'Muhamad Handika Mawardi', 'dika', '$2y$10$8XEnSBtfPYwEcCefGyhvf.yA8H860qUGHHaEkKCKo2oFBd3Wg0c3m', '2025-11-08 12:41:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `petugas`
--

CREATE TABLE `petugas` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('admin','petugas') DEFAULT 'petugas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `petugas`
--

INSERT INTO `petugas` (`id`, `nama`, `username`, `password`, `level`) VALUES
(2, 'Petugas 1', 'petugas1', '1234', 'petugas'),
(12, 'Administrator', 'admin', '$2y$10$87FiQq2JC6A9ND/R3HCofuvm3TEC/WYXlyCpY.rPx0zyh8i3Vc1qi', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `antrian`
--
ALTER TABLE `antrian`
  ADD PRIMARY KEY (`id_antrian`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `antrian`
--
ALTER TABLE `antrian`
  MODIFY `id_antrian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
