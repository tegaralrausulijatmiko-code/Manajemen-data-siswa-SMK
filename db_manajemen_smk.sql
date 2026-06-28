-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 28 Jun 2026 pada 06.50
-- Versi server: 9.4.0
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
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-06-05-000000', 'App\\Database\\Migrations\\AddKelasGuruToMapel', 'default', 'App', 1780684547, 1),
(5, '2026-06-09-000001', 'App\\Database\\Migrations\\CreateGuruTable', 'default', 'App', 1782573870, 2),
(6, '2026-06-25-000001', 'App\\Database\\Migrations\\UpdateAbsensiStatusForGuru', 'default', 'App', 1782573937, 3),
(7, '2026-06-25-000002', 'App\\Database\\Migrations\\AddBkRoleToUsers', 'default', 'App', 1782573937, 3),
(8, '2026-06-27-152727', 'App\\Database\\Migrations\\AddJenisToAbsensi', 'default', 'App', 1782576862, 4),
(9, '2026-06-27-221218', 'App\\Database\\Migrations\\RemoveTerlambatStatus', 'default', 'App', 1782598394, 5),
(10, '2026-06-27-221425', 'App\\Database\\Migrations\\RemoveRuangFromJadwal', 'default', 'App', 1782598479, 6),
(11, '2026-06-27-232633', 'App\\Database\\Migrations\\AddNomorKelas', 'default', 'App', 1782602830, 7),
(12, '2026-06-28-001507', 'App\\Database\\Migrations\\DropKodeMapelFromTblMataPelajaran', 'default', 'App', 1782605730, 8),
(13, '2026-06-28-100000', 'App\\Database\\Migrations\\AddJenisAndJurusanToMapel', 'default', 'App', 1782607053, 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_absensi`
--

CREATE TABLE `tbl_absensi` (
  `id_absensi` int NOT NULL,
  `id_siswa` int NOT NULL,
  `id_kelas` int NOT NULL,
  `id_tahun_ajaran` int NOT NULL,
  `id_jadwal` int DEFAULT NULL,
  `jenis` enum('mapel','harian') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mapel',
  `tanggal` date NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpha') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Hadir',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_guru`
--

CREATE TABLE `tbl_guru` (
  `id_guru` int NOT NULL,
  `nip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_guru` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_guru`
--

INSERT INTO `tbl_guru` (`id_guru`, `nip`, `nama_guru`, `jenis_kelamin`, `no_hp`, `alamat`, `created_at`, `updated_at`) VALUES
(1983, '197201241999041001', 'Ali Imran', 'L', '085321819600', 'Jl. Flamboyan No. 28, RT 04/RW 09, Limo, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1984, '196609071995041001', 'Arif Rifai', 'L', '085894026542', 'Jl. Nusa Indah No. 98, RT 06/RW 02, Kelapa Dua, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1985, '198902122017101001', 'A. Latif', 'L', '081407816184', 'Jl. Sukamaju No. 74, RT 04/RW 02, Cimanggis, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1986, '197905032005022001', 'Kurnia Mustiarti', 'P', '085647525534', 'Jl. Melati No. 78, RT 11/RW 03, Cilodong, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1987, '198003152009052001', 'Paryati', 'P', '087883503056', 'Jl. Akasia No. 9, RT 04/RW 10, Sukmajaya, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1988, '197811162007112001', 'Ety Suyety', 'P', '085824238849', 'Jl. Beringin No. 75, RT 07/RW 06, Tapos, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1989, '197309161997012001', 'Nur Handayani', 'P', '088212269166', 'Jl. Pinus No. 68, RT 05/RW 09, Cimanggis, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1990, '197211181999112001', 'Rusdiana', 'P', '081614627048', 'Jl. Raya No. 65, RT 02/RW 05, Sawangan, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1991, '197703122002091001', 'Sutardi', 'L', '089880957015', 'Jl. Rambutan No. 31, RT 01/RW 04, Limo, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1992, '197002242000021001', 'Nirsanto', 'L', '089882278248', 'Jl. Beringin No. 28, RT 09/RW 04, Cibubur, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1993, '199011212018081001', 'Redi Dias Setiadi', 'L', '088387133150', 'Jl. Pahlawan No. 76, RT 04/RW 01, Kelapa Dua, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1994, '196804031991062001', 'Sri Hastutie', 'P', '081383473829', 'Jl. Kelapa No. 32, RT 08/RW 07, Tapos, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1995, '197102222000062001', 'Fathiah Ramadani El Afsya', 'P', '085767010651', 'Jl. Pondok Jaya No. 25, RT 04/RW 09, Bojongsari, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1996, '197307062000081001', 'Indra Panca Hermawan', 'L', '085317810801', 'Jl. Pondok Jaya No. 22, RT 07/RW 08, Bojongsari, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1997, '197807022003072001', 'Ratna Suminar', 'P', '081164746872', 'Jl. Kebon Jeruk No. 38, RT 04/RW 01, Limo, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1998, '196812111991011001', 'Catur Nugroho', 'L', '085978820812', 'Jl. Melati No. 77, RT 02/RW 04, Pancoran Mas, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(1999, '197210081995102001', 'Setiyarini', 'P', '081369985435', 'Jl. Pondok Jaya No. 34, RT 07/RW 03, Sawangan, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2000, '198408112008011001', 'Tulus Sutanto', 'L', '085899118384', 'Jl. Dahlia No. 45, RT 02/RW 04, Sukmajaya, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2001, '198303152010101001', 'M. Husni Usman', 'L', '089980841241', 'Jl. Cendana No. 96, RT 09/RW 03, Cibubur, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2002, '198310072011042001', 'Kiki Herwanti', 'P', '089548740164', 'Jl. Mawar No. 1, RT 06/RW 03, Sawangan, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2003, '198103242011092001', 'Ely Sulistriana', 'P', '089668011280', 'Jl. Sukamaju No. 75, RT 09/RW 03, Pancoran Mas, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2004, '197301102001012001', 'Veronika Cahyandari', 'P', '088353315869', 'Jl. Tulip No. 31, RT 03/RW 03, Pancoran Mas, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2005, '196603241994071001', 'Rachmat', 'L', '089934216073', 'Jl. Kebon Jeruk No. 59, RT 06/RW 05, Tapos, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2006, '197901222005072001', 'Septiana Putri Rahayu', 'P', '081641458685', 'Jl. Anggrek No. 15, RT 05/RW 03, Limo, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2007, '198101042010062001', 'Dian Solinda', 'P', '089756981693', 'Jl. Cendrawasih No. 6, RT 12/RW 07, Cimanggis, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2008, '197706142001112001', 'Santy Widyasari', 'P', '081695148465', 'Jl. Cempaka No. 90, RT 05/RW 09, Beji, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2009, '197707222006111001', 'Didik Sudjanto', 'L', '089729946804', 'Jl. Mangga No. 27, RT 07/RW 10, Limo, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2010, '198508152015112001', 'Eva Octaviani', 'P', '085287214895', 'Jl. Flamboyan No. 97, RT 04/RW 05, Tapos, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2011, '197703012000042001', 'Yeni Ayu Rahmawati', 'P', '081791769367', 'Jl. Cempaka No. 32, RT 03/RW 01, Kelapa Dua, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2012, '199204062022011001', 'Isma Yulianto', 'L', '081931727889', 'Jl. Swadaya No. 97, RT 08/RW 10, Cilodong, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2013, '199209152017121001', 'Muhammad Kafrawi', 'L', '088277434873', 'Jl. Akasia No. 57, RT 02/RW 05, Tapos, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2014, '198206112006031001', 'Basri', 'L', '082236231665', 'Jl. Salak No. 60, RT 07/RW 01, Tapos, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2015, '199107252014102001', 'Ita Octaviani', 'P', '085670546688', 'Jl. Pahlawan No. 63, RT 04/RW 05, Pancoran Mas, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2016, '199601132024111001', 'Dedi Jayadi', 'L', '089562729806', 'Jl. Anggrek No. 11, RT 11/RW 07, Beji, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2017, '199403022021072001', 'Nuni Setiarmi', 'P', '081637556464', 'Jl. Flamboyan No. 61, RT 01/RW 09, Cimanggis, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2018, '198704212011111001', 'Fajri Islamiyanto', 'L', '081203309232', 'Jl. Kelapa No. 86, RT 02/RW 10, Tapos, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2019, '199412092022031001', 'Fariz Achmad', 'L', '087791241904', 'Jl. Bahagia No. 51, RT 12/RW 04, Kelapa Dua, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2020, '198002232007112001', 'Arum Esti Mumpuni', 'P', '087719058651', 'Jl. Jambu No. 83, RT 06/RW 01, Pancoran Mas, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2021, '199602142024112001', 'Kiky Rizky Amalia', 'P', '088372628498', 'Jl. Kelapa No. 60, RT 07/RW 10, Cibubur, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2022, '198504272009051001', 'Tegar Prasetyo', 'L', '088373799650', 'Jl. Asem No. 42, RT 03/RW 08, Tapos, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2023, '198705112014102001', 'Herlin Nur Indah', 'P', '089648083136', 'Jl. Asem No. 72, RT 04/RW 08, Sawangan, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2024, '199608262019021001', 'Zeta Adha Trisativa', 'L', '081536349578', 'Jl. Durian No. 45, RT 07/RW 09, Sukmajaya, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2025, '198712152014052001', 'Anisa Dwi Kusherawati', 'P', '081431351823', 'Jl. Nusa Indah No. 95, RT 08/RW 05, Limo, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2026, '198302272009052001', 'Detta Pagisty Arthamaya', 'P', '085352408240', 'Jl. Kenanga No. 71, RT 05/RW 03, Sawangan, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2027, '199602282019102001', 'Febi Larasati', 'P', '081577752047', 'Jl. Kamboja No. 9, RT 07/RW 08, Kelapa Dua, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2028, '196803051995021001', 'Gunawan Agung', 'L', '085318699938', 'Jl. Bahagia No. 58, RT 08/RW 05, Limo, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2029, '199205192015102001', 'Feby Saskia Putri', 'P', '089713341232', 'Jl. Melati No. 21, RT 01/RW 07, Bojongsari, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2030, '199505022021052001', 'Nurul Hana', 'P', '089647134936', 'Jl. Kamboja No. 70, RT 04/RW 03, Cibubur, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2031, '197402021999052001', 'Kartika Diana Priyadi', 'P', '087794717464', 'Jl. Jambu No. 70, RT 08/RW 08, Kelapa Dua, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2032, '196707241995102001', 'Rahmawati Utami Fajrin', 'P', '081401399049', 'Jl. Mawar No. 98, RT 03/RW 08, Cilodong, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2033, '199305062022112001', 'Khoirunnisa', 'P', '088171756551', 'Jl. Pemuda No. 43, RT 07/RW 08, Cibubur, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2034, '199009022020022001', 'Iin Tri Mulya Ningsih', 'P', '081645168087', 'Jl. Kenari No. 7, RT 04/RW 09, Sukmajaya, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2035, '199611152019042001', 'Siti Sundari', 'P', '081482477109', 'Jl. Pondok Jaya No. 91, RT 03/RW 05, Cilodong, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2036, '196509141989041001', 'Adji Saka Ardhana', 'L', '088117127484', 'Jl. Kenari No. 62, RT 08/RW 04, Bojongsari, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2037, '197407071999021001', 'Rifqi Fadhlurrahman', 'L', '081465840449', 'Jl. Asem No. 20, RT 08/RW 09, Bojongsari, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2038, '198706182016082001', 'Siti Muthiah', 'P', '081633963605', 'Jl. Kelapa No. 91, RT 07/RW 07, Sawangan, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2039, '197408021999091001', 'Yubel Tampasigi', 'L', '085951718702', 'Jl. Kenari No. 84, RT 03/RW 02, Bojongsari, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2040, '198106202010111001', 'Achmad Faisal Ghifari', 'L', '081358657809', 'Jl. Melati No. 31, RT 11/RW 05, Tapos, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2041, '197007041994081001', 'Bagas Hadi Saputra', 'L', '082340050455', 'Jl. Beringin No. 19, RT 04/RW 09, Pancoran Mas, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2042, '197603062000102001', 'Intania Maharani', 'P', '088269379237', 'Jl. Cendrawasih No. 59, RT 05/RW 01, Bojongsari, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2043, '198311182008022001', 'Isti Nurhasanah', 'P', '085859464743', 'Jl. Bahagia No. 62, RT 02/RW 04, Pancoran Mas, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26'),
(2044, '198710102014012001', 'Nurhayati', 'P', '088164090974', 'Jl. Pahlawan No. 78, RT 06/RW 04, Sawangan, Depok', '2026-06-28 11:31:26', '2026-06-28 11:31:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jadwal`
--

CREATE TABLE `tbl_jadwal` (
  `id_jadwal` int NOT NULL,
  `id_kelas` int NOT NULL COMMENT 'FK ke tbl_kelas',
  `id_mapel` int NOT NULL COMMENT 'FK ke tbl_mata_pelajaran',
  `id_guru` int NOT NULL COMMENT 'FK ke tbl_guru',
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_jadwal`
--

INSERT INTO `tbl_jadwal` (`id_jadwal`, `id_kelas`, `id_mapel`, `id_guru`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
(12, 21, 9, 1983, 'Senin', '07:00:00', '08:30:00', '2026-06-28 11:33:59', '2026-06-28 11:33:59'),
(13, 21, 10, 1984, 'Senin', '08:30:00', '10:00:00', '2026-06-28 11:33:59', '2026-06-28 11:33:59'),
(14, 21, 15, 1992, 'Senin', '10:15:00', '11:45:00', '2026-06-28 11:33:59', '2026-06-28 11:33:59'),
(15, 21, 11, 1985, 'Selasa', '07:00:00', '08:30:00', '2026-06-28 11:33:59', '2026-06-28 11:33:59'),
(16, 21, 12, 1986, 'Selasa', '08:30:00', '10:00:00', '2026-06-28 11:33:59', '2026-06-28 11:33:59'),
(17, 21, 16, 1993, 'Selasa', '10:15:00', '11:45:00', '2026-06-28 11:33:59', '2026-06-28 11:33:59'),
(18, 21, 17, 1996, 'Rabu', '07:00:00', '08:30:00', '2026-06-28 11:33:59', '2026-06-28 11:33:59'),
(19, 21, 18, 1997, 'Rabu', '08:30:00', '10:00:00', '2026-06-28 11:33:59', '2026-06-28 11:33:59'),
(20, 21, 13, 1991, 'Kamis', '07:00:00', '08:30:00', '2026-06-28 11:33:59', '2026-06-28 11:33:59'),
(21, 21, 14, 1989, 'Kamis', '08:30:00', '10:00:00', '2026-06-28 11:33:59', '2026-06-28 11:33:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jurusan`
--

CREATE TABLE `tbl_jurusan` (
  `id_jurusan` int NOT NULL,
  `kode_jurusan` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_jurusan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kaprog` int DEFAULT NULL COMMENT 'FK ke tbl_guru',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_jurusan`
--

INSERT INTO `tbl_jurusan` (`id_jurusan`, `kode_jurusan`, `nama_jurusan`, `id_kaprog`, `created_at`, `updated_at`) VALUES
(8, 'AK', 'Akuntansi Komputer', NULL, '2026-06-28 11:24:07', '2026-06-28 11:24:07'),
(9, 'FK', 'Farmasi Kesehatan', NULL, '2026-06-28 11:24:07', '2026-06-28 11:24:07'),
(10, 'TLM', 'Teknologi Laboratorium Medik', NULL, '2026-06-28 11:24:07', '2026-06-28 11:24:07'),
(11, 'TJK', 'Teknik Jaringan Komputer', NULL, '2026-06-28 11:28:47', '2026-06-28 11:28:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kelas`
--

CREATE TABLE `tbl_kelas` (
  `id_kelas` int NOT NULL,
  `id_jurusan` int NOT NULL COMMENT 'FK ke tbl_jurusan',
  `nama_kelas` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` enum('X','XI','XII') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_kelas` int DEFAULT NULL,
  `id_wali_kelas` int DEFAULT NULL COMMENT 'FK ke tbl_guru',
  `jumlah_siswa` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_kelas`
--

INSERT INTO `tbl_kelas` (`id_kelas`, `id_jurusan`, `nama_kelas`, `tingkat`, `nomor_kelas`, `id_wali_kelas`, `jumlah_siswa`, `created_at`, `updated_at`) VALUES
(21, 11, 'X TJK 1', 'X', 1, 1983, 32, '2026-06-28 11:28:59', '2026-06-28 11:34:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_mata_pelajaran`
--

CREATE TABLE `tbl_mata_pelajaran` (
  `id_mapel` int NOT NULL,
  `nama_mapel` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Produktif','Umum') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Umum',
  `id_guru` int DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_mata_pelajaran`
--

INSERT INTO `tbl_mata_pelajaran` (`id_mapel`, `nama_mapel`, `status`, `id_guru`, `created_at`, `updated_at`) VALUES
(9, 'Matematika', 'Umum', NULL, '2026-06-28 11:31:38', '2026-06-28 11:31:38'),
(10, 'Bahasa Indonesia', 'Umum', NULL, '2026-06-28 11:31:38', '2026-06-28 11:31:38'),
(11, 'Bahasa Inggris', 'Umum', NULL, '2026-06-28 11:31:38', '2026-06-28 11:31:38'),
(12, 'Pendidikan Pancasila', 'Umum', NULL, '2026-06-28 11:31:38', '2026-06-28 11:31:38'),
(13, 'Pendidikan Jasmani', 'Umum', NULL, '2026-06-28 11:31:38', '2026-06-28 11:31:38'),
(14, 'Sejarah Indonesia', 'Umum', NULL, '2026-06-28 11:31:38', '2026-06-28 11:31:38'),
(15, 'Dasar-Dasar Jaringan Komputer', 'Produktif', NULL, '2026-06-28 11:31:38', '2026-06-28 11:31:38'),
(16, 'Administrasi Sistem Jaringan', 'Produktif', NULL, '2026-06-28 11:31:38', '2026-06-28 11:31:38'),
(17, 'Keamanan Jaringan', 'Produktif', NULL, '2026-06-28 11:31:38', '2026-06-28 11:31:38'),
(18, 'Pemrograman Web', 'Produktif', NULL, '2026-06-28 11:31:38', '2026-06-28 11:31:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_siswa`
--

CREATE TABLE `tbl_siswa` (
  `id_siswa` int NOT NULL,
  `id_kelas` int NOT NULL COMMENT 'FK ke tbl_kelas',
  `nisn` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_siswa` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `no_hp` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_siswa`
--

INSERT INTO `tbl_siswa` (`id_siswa`, `id_kelas`, `nisn`, `nama_siswa`, `jenis_kelamin`, `alamat`, `no_hp`, `foto`, `created_at`, `updated_at`) VALUES
(24, 21, '0019347202', 'Qodir Maulana', 'L', 'Jl. Contoh No.1, Depok', '086988645550', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(25, 21, '0003991341', 'Fina Ramadhani', 'P', 'Jl. Contoh No.2, Depok', '084585335484', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(26, 21, '0045386591', 'Malik Ibrahim', 'L', 'Jl. Contoh No.3, Depok', '083826328748', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(27, 21, '0066741081', 'Naufal Rizky', 'L', 'Jl. Contoh No.4, Depok', '088795237981', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(28, 21, '0018303118', 'Ela Fitriana', 'P', 'Jl. Contoh No.5, Depok', '086206783618', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(29, 21, '0087856313', 'Gita Puspita', 'P', 'Jl. Contoh No.6, Depok', '089280197054', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(30, 21, '0053204400', 'Ucok Sitorus', 'L', 'Jl. Contoh No.7, Depok', '088545594524', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(31, 21, '0014917228', 'Joko Susilo', 'L', 'Jl. Contoh No.8, Depok', '084553792623', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(32, 21, '0014945565', 'Irfan Hakim', 'L', 'Jl. Contoh No.9, Depok', '081910553832', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(33, 21, '0057248597', 'Fajar Nugroho', 'L', 'Jl. Contoh No.10, Depok', '084423242857', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(34, 21, '0058469440', 'Surya Dinata', 'L', 'Jl. Contoh No.11, Depok', '083029327153', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(35, 21, '0073276406', 'Lutfi Hidayat', 'L', 'Jl. Contoh No.12, Depok', '086764775062', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(36, 21, '0033874499', 'Anisa Rahma', 'P', 'Jl. Contoh No.13, Depok', '083745332986', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(37, 21, '0041935917', 'Vino Bastian', 'L', 'Jl. Contoh No.14, Depok', '086989928550', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(38, 21, '0025969797', 'Bella Safitri', 'P', 'Jl. Contoh No.15, Depok', '084608565286', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(39, 21, '0008053624', 'Kevin Pratama', 'L', 'Jl. Contoh No.16, Depok', '089503510728', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(40, 21, '0019387336', 'Budi Santoso', 'L', 'Jl. Contoh No.17, Depok', '086680486357', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(41, 21, '0064288974', 'Hana Kristina', 'P', 'Jl. Contoh No.18, Depok', '082644299563', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(42, 21, '0010926904', 'Citra Dewi', 'P', 'Jl. Contoh No.19, Depok', '082954800028', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(43, 21, '0078737731', 'Dinda Ayu', 'P', 'Jl. Contoh No.20, Depok', '080049988377', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(44, 21, '0069672167', 'Wahyu Saputra', 'L', 'Jl. Contoh No.21, Depok', '089221343470', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(45, 21, '0005987562', 'Xander Febrian', 'L', 'Jl. Contoh No.22, Depok', '082717677317', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(46, 21, '0077269311', 'Hendra Kusuma', 'L', 'Jl. Contoh No.23, Depok', '082781682422', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(47, 21, '0043491670', 'Oscar Firmansyah', 'L', 'Jl. Contoh No.24, Depok', '089563258473', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(48, 21, '0044425419', 'Ahmad Fauzi', 'L', 'Jl. Contoh No.25, Depok', '089422971770', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(49, 21, '0082106688', 'Eko Wahyudi', 'L', 'Jl. Contoh No.26, Depok', '087453870970', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(50, 21, '0010603346', 'Gilang Ramadan', 'L', 'Jl. Contoh No.27, Depok', '083800825867', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(51, 21, '0053153144', 'Cahyo Prabowo', 'L', 'Jl. Contoh No.28, Depok', '084082574295', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(52, 21, '0071510046', 'Rizky Ananda', 'L', 'Jl. Contoh No.29, Depok', '081121085196', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(53, 21, '0006294567', 'Putra Aditya', 'L', 'Jl. Contoh No.30, Depok', '081143518352', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(54, 21, '0082693913', 'Dani Setiawan', 'L', 'Jl. Contoh No.31, Depok', '085353126846', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15'),
(55, 21, '0035054990', 'Taufik Hidayat', 'L', 'Jl. Contoh No.32, Depok', '084342387806', NULL, '2026-06-28 11:29:15', '2026-06-28 11:29:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id_user` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','guru','siswa','bk') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `id_guru` int DEFAULT NULL,
  `id_siswa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_users`
--

INSERT INTO `tbl_users` (`id_user`, `username`, `nama`, `password`, `email`, `role`, `id_guru`, `id_siswa`, `created_at`, `updated_at`, `status`) VALUES
(1, 'admin', 'Administrator', '$2y$10$SjgwuNpUMQZK3Ir.BNmeWO2qHv5cFSIe4tqCyvAh.G25A.t/bZPNW', 'admin@sekolah.local', 'admin', NULL, NULL, '2026-06-14 12:52:39', '2026-06-14 05:55:53', 'aktif'),
(10, '197201241999041001', 'Ali Imran', '$2y$10$ikTx1rwruYbucFUHuuQun.3RGeg9fvenRvr3qdt2f5ErYOqweNGq2', NULL, 'guru', 1983, NULL, '2026-06-28 04:49:08', '2026-06-28 04:49:08', 'aktif'),
(11, '198106202010111001', 'Achmad Faisal Ghifari', '$2y$10$/JXuQWZl2jcUn4E84fosNO6FhrARSoKLQyhUO9Mf7kwiL.Llf/TLK', NULL, 'bk', 2040, NULL, '2026-06-28 04:49:20', '2026-06-28 04:49:20', 'aktif');

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
  ADD KEY `idx_mapel_guru` (`id_guru`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  MODIFY `id_absensi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `tbl_guru`
--
ALTER TABLE `tbl_guru`
  MODIFY `id_guru` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2045;

--
-- AUTO_INCREMENT untuk tabel `tbl_jadwal`
--
ALTER TABLE `tbl_jadwal`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  MODIFY `id_jurusan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  MODIFY `id_kelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `tbl_mata_pelajaran`
--
ALTER TABLE `tbl_mata_pelajaran`
  MODIFY `id_mapel` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  MODIFY `id_siswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT untuk tabel `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- Ketidakleluasaan untuk tabel `tbl_mata_pelajaran`
--
ALTER TABLE `tbl_mata_pelajaran`
  ADD CONSTRAINT `fk_mapel_guru` FOREIGN KEY (`id_guru`) REFERENCES `tbl_guru` (`id_guru`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD CONSTRAINT `fk_siswa_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tbl_kelas` (`id_kelas`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
