-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Des 2020 pada 10.04
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpus`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id` int(11) NOT NULL,
  `nama_buku` varchar(225) NOT NULL,
  `kode_buku` varchar(25) DEFAULT NULL,
  `kategori_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `foto` varchar(225) NOT NULL,
  `deskripsi` varchar(225) DEFAULT NULL,
  `pengarang` varchar(225) NOT NULL,
  `tahun_terbit` year(4) NOT NULL,
  `isbn` varchar(25) DEFAULT NULL,
  `penerbit` varchar(225) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id`, `nama_buku`, `kode_buku`, `kategori_id`, `jumlah`, `foto`, `deskripsi`, `pengarang`, `tahun_terbit`, `isbn`, `penerbit`, `tanggal`) VALUES
(1, 'Matematika Kelas IV', 'MTKIV001', 2, 40, '1608129454_8f76c06d88fe28c05d73.jpeg', 'Buku Matematika Kelas IV Kurikulum 2019', 'Kemendikbud', 2016, '972360276982', 'Erlangga', '2020-12-06'),
(2, 'Bahasa indonesia Kelas XII', 'BINDOXII001', 1, 40, '1608129441_2d3a369d35d21623c503.jpeg', 'Buku Bahasa Indonesia Kelas XII Kurikulum 2010', 'Pustaka Media', 2015, '9782346920634', 'Erlangga', '2020-12-11'),
(4, 'Bahasa Indonesia Kelas IX', 'BI109', 1, 0, '1608129418_9377369a0bd5fb6905b1.jpeg', 'Buku Bahasa Indonesia Kelas IX Kurikulum 2013', 'Pustaka Media', 2015, '978234052907', 'Erlangga', '2020-12-12'),
(5, 'Matematika Kelas V', 'MTKIV001', 1, 0, '1608129385_de6fbff7fe9f70a784c8.jpeg', 'Buku Matematika Kelas V Kurikulum 2013', 'Pustaka Media', 2017, '967823496893', 'Erlangga', '2020-12-12'),
(8, 'Bahasa Indonesia Kelas X', 'BINDOX120', 1, 0, '1608129271_cf9f491f4f7aff83bfd6.jpeg', 'Buku Bahasa Indonesia Kelas X Kurikulum 2013', 'Pustaka Media', 2017, '967823495802', 'Erlangga', '2020-12-14'),
(9, 'IPA Kelas VII', 'IPAVII689', 1, 43, '1608130067_6904c2a957bf9e373cb9.jpeg', 'Buku IPA Kelas VII Kurikulum 2019', 'Kemendikbud', 2016, '972360271414', 'Erlangga', '2020-12-06'),
(10, 'Matematika Kelas VI', 'MTKVI691', 1, 40, '1608130052_12fdddedf1d72542f4c8.jpeg', 'Buku Matematika Kelas VI Kurikulum 2010', 'Pustaka Media', 2015, '978234253453', 'Erlangga', '2020-12-11'),
(11, 'Tokoh Ahmad Yani', '-', 2, 0, '1608129990_b6398b8f716b769fa912.jpg', 'Kisah pahlawan nasional Ahmad Yani', 'Pustaka Media', 2015, '978234023423', 'Erlangga', '2020-12-12'),
(12, 'Bung Karno Bpk Proklamasi', '-', 2, 0, '1608129969_9f7b28a416977e274a15.jpg', '', 'Pustaka Media', 2017, '967703426094', 'Erlangga', '2020-12-12'),
(13, 'Kartini - Guru Wanita Indonesia', '-', 2, 0, '1608129945_668cdb73aa91ea40318f.jpg', 'Tokoh pahlawan wanita indonesia', 'Pustaka Media', 2017, '978619254014', 'Erlangga', '2020-12-14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku_pinjaman`
--

CREATE TABLE `buku_pinjaman` (
  `id` int(11) NOT NULL,
  `no_pinjam` varchar(25) NOT NULL,
  `buku_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `buku_pinjaman`
--

INSERT INTO `buku_pinjaman` (`id`, `no_pinjam`, `buku_id`) VALUES
(1, 'PJM202012120001', 1),
(2, 'PJM202012120001', 2),
(3, 'PJM202012120001', 3),
(12, 'PJM202012130001', 4),
(13, 'PJM202012130001', 5),
(14, 'PJM202012130001', 2),
(15, 'PJM202012160003', 2),
(16, 'PJM202012160003', 1),
(17, 'PJM202012160004', 2),
(18, 'PJM202012160004', 1),
(19, 'PJM202012160004', 4),
(20, 'PJM202012160005', 4),
(21, 'PJM202012160005', 5),
(22, 'PJM202012200001', 4),
(23, 'PJM202012200001', 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `denda`
--

CREATE TABLE `denda` (
  `id` int(11) NOT NULL,
  `denda` int(11) NOT NULL,
  `ket_denda` varchar(2255) NOT NULL,
  `no_pinjam` varchar(225) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `denda`
--

INSERT INTO `denda` (`id`, `denda`, `ket_denda`, `no_pinjam`, `tanggal`) VALUES
(1, 500000, '', 'PJM202012120001', '2020-12-14'),
(2, 20000, '', 'PJM202012130001', '2020-12-15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(225) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `tanggal`) VALUES
(1, 'Mata Pelajaran', '2020-12-11'),
(2, 'Tokoh Nasional', '2020-12-11'),
(3, 'Cerita Rakyat', '2020-12-11'),
(5, 'Sejarah', '2020-12-12'),
(7, 'Komik', '2020-12-20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang_buku`
--

CREATE TABLE `keranjang_buku` (
  `id` int(11) NOT NULL,
  `username` varchar(225) NOT NULL,
  `buku_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `keranjang_buku`
--

INSERT INTO `keranjang_buku` (`id`, `username`, `buku_id`) VALUES
(8, 'adit', 4),
(9, 'adit', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` int(11) NOT NULL,
  `nama_app` varchar(25) NOT NULL,
  `logo` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `nama_app`, `logo`) VALUES
(1, 'Perpustakaan', 'logoproduct2.svg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pinjaman`
--

CREATE TABLE `pinjaman` (
  `id` int(11) NOT NULL,
  `no_pinjam` varchar(225) NOT NULL,
  `username` varchar(225) NOT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `tgl_return` date DEFAULT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pinjaman`
--

INSERT INTO `pinjaman` (`id`, `no_pinjam`, `username`, `start`, `end`, `status`, `tgl_return`, `tanggal`) VALUES
(1, 'PJM202012120001', 'adit', '2020-12-12', '2020-12-14', 0, NULL, '2020-12-12'),
(13, 'PJM202012130001', 'adit', '2020-12-13', '2020-12-20', 0, NULL, '2020-12-15'),
(16, 'PJM202012160003', 'adit', '2020-12-16', '2020-12-27', 0, NULL, '2020-12-16'),
(17, 'PJM202012160004', 'adit', NULL, NULL, 2, NULL, '2020-12-16'),
(18, 'PJM202012160005', 'ahmad', '2020-12-16', '2020-12-23', 0, NULL, '2020-12-16'),
(19, 'PJM202012200001', 'adithadirizki', '2020-12-20', '2020-12-27', 0, '2020-12-20', '2020-12-20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(225) NOT NULL,
  `username` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `role` varchar(225) NOT NULL,
  `foto` varchar(225) NOT NULL,
  `status` int(11) NOT NULL,
  `token_aktivasi` varchar(225) NOT NULL,
  `alamat` varchar(225) DEFAULT NULL,
  `no_hp` varchar(25) DEFAULT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `email`, `password`, `role`, `foto`, `status`, `token_aktivasi`, `alamat`, `no_hp`, `tanggal`) VALUES
(1, 'Admin Perpus', 'admin', 'admin@perpus.com', '$2y$10$Ofv//IwmeD/Q6wGiXknyCeVNKRT6/OChSyoiHl4sxLqauCetJDrUG', 'admin', 'profile.jpg', 1, '', '', '0', '2020-12-10'),
(2, 'Adit Rizki', 'adit', 'adit@gmail.com', '$2y$10$752.wz9RI1EwgPgPW/m00uLGJ3HFBRoQ.K9TWOIjYG9PH38jcw5fS', 'user', '1608441789_7e9ddbac281a2204bf96.jpg', 1, '', '', '0', '2020-12-10'),
(4, 'Imelda', 'imelda', 'imelda@gmail.com', 'imelda', 'user', 'profile.jpg', 1, '', '', '0', '2020-12-14'),
(5, 'Ahmad', 'ahmad', 'ahmad@gmail.com', 'ahmad', 'user', 'profile.jpg', 0, '', '', '0', '2020-12-14'),
(8, 'Batu Keras', 'batu', 'adithadirizki@gmail.com', 'batu', 'user', 'avatar.png', 1, '456396930d48816770b2e8aee400f8b4', '', '0', '2020-12-16'),
(9, 'Adit Hadi Rizki', 'adithadirizki', 'adit.hadirizki@gmail.com', '$2y$10$wJol8nYXEEPwNK2csnpjROdg3exHcoLvbZNKXxJwSGRgZbQfbpQoK', 'user', 'avatar.png', 1, '', '', '0', '2020-12-19');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `buku_pinjaman`
--
ALTER TABLE `buku_pinjaman`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `denda`
--
ALTER TABLE `denda`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `keranjang_buku`
--
ALTER TABLE `keranjang_buku`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pinjaman`
--
ALTER TABLE `pinjaman`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `buku_pinjaman`
--
ALTER TABLE `buku_pinjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `denda`
--
ALTER TABLE `denda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `keranjang_buku`
--
ALTER TABLE `keranjang_buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pinjaman`
--
ALTER TABLE `pinjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
