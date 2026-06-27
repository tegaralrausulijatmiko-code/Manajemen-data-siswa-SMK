-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Jun 2026 pada 17.17
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_manajemen_smk`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-06-05-000000', 'App\\Database\\Migrations\\AddKelasGuruToMapel', 'default', 'App', 1780684547, 1),
(2, '2026-06-09-000001', 'App\\Database\\Migrations\\CreateGuruTable', 'default', 'App', 1782148765, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_absensi`
--

CREATE TABLE `tbl_absensi` (
  `id_absensi` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_tahun_ajaran` int(11) NOT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpa') NOT NULL DEFAULT 'Hadir',
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_absensi`
--

INSERT INTO `tbl_absensi` (`id_absensi`, `id_siswa`, `id_kelas`, `id_tahun_ajaran`, `id_jadwal`, `tanggal`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(22, 11, 12, 0, 4, '2026-06-23', 'Hadir', '', '2026-06-23 06:12:42', '2026-06-23 11:52:35'),
(23, 8, 9, 0, NULL, '2026-06-23', 'Hadir', '', '2026-06-23 11:51:53', '2026-06-23 11:52:35'),
(24, 4, 2, 0, NULL, '2026-06-23', 'Izin', 'Acara', '2026-06-23 11:52:35', '2026-06-23 11:52:35'),
(25, 6, 2, 0, NULL, '2026-06-23', 'Alpa', '', '2026-06-23 11:52:35', '2026-06-23 11:52:35'),
(26, 3, 3, 0, NULL, '2026-06-23', 'Izin', 'Acara Keluarga', '2026-06-23 11:52:35', '2026-06-23 11:52:35'),
(27, 10, 10, 0, NULL, '2026-06-23', 'Hadir', '', '2026-06-23 11:52:35', '2026-06-23 11:52:35'),
(28, 7, 6, 0, NULL, '2026-06-23', 'Alpa', '', '2026-06-23 11:52:35', '2026-06-23 11:52:35'),
(29, 9, 11, 0, NULL, '2026-06-23', 'Hadir', '', '2026-06-23 11:52:35', '2026-06-23 11:52:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_guru`
--

CREATE TABLE `tbl_guru` (
  `id_guru` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nama_guru` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_guru`
--

INSERT INTO `tbl_guru` (`id_guru`, `nip`, `nama_guru`, `jenis_kelamin`, `no_hp`, `alamat`, `created_at`, `updated_at`) VALUES
(1872, '123456789876543212', 'Sinta', 'P', '08564123492', 'Cibubur', '2026-06-13 14:12:38', '2026-06-23 11:28:18'),
(1975, '123456789876543211', 'Bambang', 'L', '086786568646', 'Bojong gede', '2026-06-13 14:07:14', '2026-06-23 11:28:06'),
(1976, '123456789876543213', 'Sucipto', 'L', '087612462387', 'Citayem', '2026-06-23 11:27:56', '2026-06-23 11:27:56'),
(1977, '123456789876543214', 'Dewi', 'P', '087914250987', 'Srengseng Sawah', '2026-06-23 11:29:00', '2026-06-23 11:29:00'),
(1978, '123456789876543215', 'Nurul', 'P', '089099459123', 'Citereup', '2026-06-23 11:30:00', '2026-06-23 11:30:00'),
(1979, '123456789876543216', 'Budi', 'L', '089124820987', 'Depok', '2026-06-23 11:37:49', '2026-06-23 11:37:49'),
(1980, '123456789876543217', 'Olivia', 'P', '089189671234', 'PIK 2', '2026-06-23 11:39:25', '2026-06-23 11:39:25'),
(1981, '123456789876543218', 'Anto', 'L', '089712340987', 'Blok M', '2026-06-23 11:39:54', '2026-06-23 11:39:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jadwal`
--

CREATE TABLE `tbl_jadwal` (
  `id_jadwal` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL COMMENT 'FK ke tbl_kelas',
  `id_mapel` int(11) NOT NULL COMMENT 'FK ke tbl_mata_pelajaran',
  `id_guru` int(11) NOT NULL COMMENT 'FK ke tbl_guru',
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_jadwal`
--

INSERT INTO `tbl_jadwal` (`id_jadwal`, `id_kelas`, `id_mapel`, `id_guru`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
(4, 12, 6, 1975, 'Selasa', '15:00:00', '17:30:00', '401', '2026-06-20 16:52:14', '2026-06-23 11:48:04'),
(5, 9, 11, 1872, 'Kamis', '10:50:00', '13:20:00', '507', '2026-06-23 11:49:00', '2026-06-23 11:49:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jurusan`
--

CREATE TABLE `tbl_jurusan` (
  `id_jurusan` int(11) NOT NULL,
  `kode_jurusan` varchar(10) NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL,
  `id_kaprog` int(11) DEFAULT NULL COMMENT 'FK ke tbl_guru',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_jurusan`
--

INSERT INTO `tbl_jurusan` (`id_jurusan`, `kode_jurusan`, `nama_jurusan`, `id_kaprog`, `created_at`, `updated_at`) VALUES
(1, 'RPL', 'Rekayasa Perangkat Lunak', 1975, '2026-06-05 08:26:00', '2026-06-14 17:13:49'),
(2, 'TKJ', 'Teknik Jaringan Komputer', 1976, '2026-06-09 04:28:01', '2026-06-23 11:30:29'),
(3, 'AKL', 'Akuntansi Keuangan Lembaga', 1872, '2026-06-09 04:28:44', '2026-06-20 16:14:52'),
(4, 'TF', 'Teknologi Farmasi', 1977, '2026-06-09 04:29:06', '2026-06-23 11:30:18'),
(5, 'TLM', 'Teknik Laboratorium Medik', 1978, '2026-06-09 04:30:02', '2026-06-23 11:31:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kelas`
--

CREATE TABLE `tbl_kelas` (
  `id_kelas` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL COMMENT 'FK ke tbl_jurusan',
  `nama_kelas` varchar(50) NOT NULL,
  `tingkat` enum('X','XI','XII') NOT NULL,
  `id_wali_kelas` int(11) DEFAULT NULL COMMENT 'FK ke tbl_guru',
  `jumlah_siswa` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_kelas`
--

INSERT INTO `tbl_kelas` (`id_kelas`, `id_jurusan`, `nama_kelas`, `tingkat`, `id_wali_kelas`, `jumlah_siswa`, `created_at`, `updated_at`) VALUES
(2, 1, 'X RPL 2', 'X', 1980, 28, '2026-06-09 04:23:57', '2026-06-23 11:40:07'),
(3, 1, 'X RPL 3', 'X', 1981, 28, '2026-06-09 04:26:34', '2026-06-23 11:40:14'),
(6, 2, 'X TKJ 1', 'X', 1976, 29, '2026-06-09 04:32:18', '2026-06-23 11:36:30'),
(9, 3, 'X AKL 1', 'X', 1872, 33, '2026-06-15 04:48:17', '2026-06-23 12:36:36'),
(10, 4, 'X TF 1', 'X', 1977, 40, '2026-06-15 04:48:48', '2026-06-23 11:35:36'),
(11, 5, 'X TLM 1', 'X', 1978, 31, '2026-06-15 04:49:08', '2026-06-23 11:36:39'),
(12, 1, 'X RPL 1', 'X', 1975, 26, '2026-06-20 16:20:20', '2026-06-23 11:36:07'),
(16, 3, 'X AKL 2', 'X', 1979, 38, '2026-06-23 13:09:10', '2026-06-23 13:09:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_mata_pelajaran`
--

CREATE TABLE `tbl_mata_pelajaran` (
  `id_mapel` int(11) NOT NULL,
  `kode_mapel` varchar(10) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `status` enum('Produktif','Non Produktif') NOT NULL DEFAULT 'Non Produktif',
  `tingkat` enum('X','XI','XII') NOT NULL DEFAULT 'X',
  `id_guru` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_mata_pelajaran`
--

INSERT INTO `tbl_mata_pelajaran` (`id_mapel`, `kode_mapel`, `nama_mapel`, `status`, `tingkat`, `id_guru`, `created_at`, `updated_at`) VALUES
(6, '177', 'Pemrograman Web', 'Produktif', 'X', 1975, '2026-06-09 05:19:17', '2026-06-23 05:59:13'),
(11, '158', 'Matematika', 'Non Produktif', 'X', 1872, '2026-06-23 11:44:50', '2026-06-23 11:44:50'),
(12, '156', 'Desain Grafis', 'Produktif', 'X', 1980, '2026-06-23 11:45:03', '2026-06-23 11:45:03'),
(13, '124', 'Jaringan Komputer', 'Produktif', 'X', 1981, '2026-06-23 11:45:35', '2026-06-23 11:45:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_siswa`
--

CREATE TABLE `tbl_siswa` (
  `id_siswa` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL COMMENT 'FK ke tbl_kelas',
  `nisn` varchar(20) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_siswa`
--

INSERT INTO `tbl_siswa` (`id_siswa`, `id_kelas`, `nisn`, `nama_siswa`, `jenis_kelamin`, `alamat`, `no_hp`, `foto`, `created_at`, `updated_at`) VALUES
(3, 3, '0012000001', 'Samni Marito Lestian Panjaitan', 'L', 'Jakarta Timur', '08253363324', '1782213778_42d50069856f59088885.jpg', '2026-06-05 17:40:28', '2026-06-23 11:22:58'),
(4, 2, '0012000002', 'Meylinda Zelin', 'P', 'Cilangkap', '08345354323', '1782213756_36283b37b78175584862.jpg', '2026-06-09 04:25:57', '2026-06-23 11:22:36'),
(6, 2, '0012000004', 'Riski Dwiyanto', 'L', 'Pondok Gede', '087698765511', '1782213766_926e75a1418a084a7d03.jpg', '2026-06-11 07:04:43', '2026-06-23 11:22:46'),
(7, 6, '0012000005', 'Canggih Wibowo', 'L', 'Depok', '087545671890', '1782213798_86464bdecd4f34080566.jpg', '2026-06-13 10:07:35', '2026-06-23 11:23:18'),
(8, 9, '0012000006', 'Nohan Aurel Adanio', 'L', 'Pengasinan', '087283421234', '1782213730_5a0e782941475dc79adc.jpg', '2026-06-14 12:59:43', '2026-06-23 11:24:07'),
(9, 11, '0012000007', 'Tegar Al Rausuli Jatmiko', 'L', 'Pondok Indah', '089654371288', '1782213936_85cf510930b1c8d6dff7.jpg', '2026-06-15 04:50:31', '2026-06-23 11:25:36'),
(10, 10, '0012000008', 'Muhammad Firmansyah', 'L', 'Bogor', '081247581233', '1782213787_3e41e1fc66ecc67e0ba2.jpg', '2026-06-15 04:51:06', '2026-06-23 11:24:59'),
(11, 12, '0012000003', 'Rizky Dharmawan', 'L', 'CIpayung', '08976583465', '1782213747_23268299c3f2d06b707a.jpg', '2026-06-20 16:21:02', '2026-06-23 11:22:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `id_guru` int(11) DEFAULT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(20) DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_users`
--

INSERT INTO `tbl_users` (`id_user`, `username`, `nama`, `password`, `email`, `role`, `id_guru`, `id_siswa`, `created_at`, `updated_at`, `status`) VALUES
(1, 'admin', 'Administrator', '$2y$10$SjgwuNpUMQZK3Ir.BNmeWO2qHv5cFSIe4tqCyvAh.G25A.t/bZPNW', 'admin@sekolah.local', 'admin', NULL, NULL, '2026-06-14 12:52:39', '2026-06-14 05:55:53', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  ADD PRIMARY KEY (`id_absensi`);

--
-- Indeks untuk tabel `tbl_guru`
--
ALTER TABLE `tbl_guru`
  ADD PRIMARY KEY (`id_guru`),
  ADD UNIQUE KEY `uq_nip` (`nip`);

--
-- Indeks untuk tabel `tbl_jadwal`
--
ALTER TABLE `tbl_jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `fk_jadwal_kelas` (`id_kelas`),
  ADD KEY `fk_jadwal_mapel` (`id_mapel`),
  ADD KEY `fk_jadwal_guru` (`id_guru`);

--
-- Indeks untuk tabel `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  ADD PRIMARY KEY (`id_jurusan`),
  ADD UNIQUE KEY `uq_kode_jurusan` (`kode_jurusan`),
  ADD KEY `fk_jurusan_kaprog` (`id_kaprog`);

--
-- Indeks untuk tabel `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `fk_kelas_jurusan` (`id_jurusan`),
  ADD KEY `fk_kelas_wali` (`id_wali_kelas`);

--
-- Indeks untuk tabel `tbl_mata_pelajaran`
--
ALTER TABLE `tbl_mata_pelajaran`
  ADD PRIMARY KEY (`id_mapel`),
  ADD UNIQUE KEY `uq_kode_mapel` (`kode_mapel`);

--
-- Indeks untuk tabel `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `uq_nisn` (`nisn`),
  ADD KEY `fk_siswa_kelas` (`id_kelas`);

--
-- Indeks untuk tabel `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `tbl_guru`
--
ALTER TABLE `tbl_guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1982;

--
-- AUTO_INCREMENT untuk tabel `tbl_jadwal`
--
ALTER TABLE `tbl_jadwal`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  MODIFY `id_jurusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `tbl_mata_pelajaran`
--
ALTER TABLE `tbl_mata_pelajaran`
  MODIFY `id_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbl_jadwal`
--
ALTER TABLE `tbl_jadwal`
  ADD CONSTRAINT `fk_jadwal_guru` FOREIGN KEY (`id_guru`) REFERENCES `tbl_guru` (`id_guru`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jadwal_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tbl_kelas` (`id_kelas`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jadwal_mapel` FOREIGN KEY (`id_mapel`) REFERENCES `tbl_mata_pelajaran` (`id_mapel`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  ADD CONSTRAINT `fk_jurusan_kaprog` FOREIGN KEY (`id_kaprog`) REFERENCES `tbl_guru` (`id_guru`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  ADD CONSTRAINT `fk_kelas_jurusan` FOREIGN KEY (`id_jurusan`) REFERENCES `tbl_jurusan` (`id_jurusan`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kelas_wali` FOREIGN KEY (`id_wali_kelas`) REFERENCES `tbl_guru` (`id_guru`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD CONSTRAINT `fk_siswa_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tbl_kelas` (`id_kelas`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
