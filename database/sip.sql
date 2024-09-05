-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 04 Sep 2024 pada 03.41
-- Versi server: 8.0.30
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sip`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` char(7) NOT NULL,
  `id_jenis_barang` char(7) DEFAULT NULL,
  `nama_barang` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `harga` bigint DEFAULT NULL,
  `kedaluwarsa` date DEFAULT NULL
);

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `id_jenis_barang`, `nama_barang`, `unit`, `harga`, `kedaluwarsa`) VALUES
('BA00001', 'JB00001', 'Pupuk ZA', '50kg', 150000, '2025-12-31'),
('BA00002', 'JB00001', 'Pupuk Organik', '25kg', 120000, '2025-11-30'),
('BA00003', 'JB00001', 'Pupuk NPK', '1kg', 50000, '2024-10-15'),
('BA00004', 'JB00001', 'Pupuk Kandang', '40kg', 130000, '2024-08-01'),
('BA00005', 'JB00001', 'Pupuk Cair', '1 liter', 40000, '2024-09-20'),
('BA00006', 'JB00002', 'Benih Jagung', '100g', 30000, '2024-12-01'),
('BA00007', 'JB00002', 'Benih Cabai', '50g', 25000, '2025-01-15'),
('BA00008', 'JB00002', 'Benih Tomat', '75g', 27000, '2024-11-10'),
('BA00009', 'JB00002', 'Benih Kacang Panjang', '100g', 32000, '2024-10-20'),
('BA00010', 'JB00002', 'Benih Terong', '60g', 28000, '2024-12-31'),
('BA00011', 'JB00003', 'Obat Hama A', '100ml', 55000, '2024-09-25'),
('BA00012', 'JB00003', 'Obat Hama B', '200ml', 65000, '2024-10-30'),
('BA00013', 'JB00003', 'Obat Hama C', '150ml', 60000, '2025-03-15'),
('BA00014', 'JB00003', 'Obat Hama D', '250ml', 70000, '2025-06-01'),
('BA00015', 'JB00003', 'Obat Hama E', '50ml', 40000, '2024-12-01'),
('BA00016', 'JB00004', 'Alat Penyiram A', '1 unit', 75000, NULL),
('BA00017', 'JB00004', 'Alat Penyiram B', '1 unit', 85000, NULL),
('BA00018', 'JB00005', 'Cangkul', '1 unit', 45000, NULL),
('BA00019', 'JB00005', 'Traktor Kecil', '1 unit', 1200000, NULL),
('BA00020', 'JB00006', 'Sistem Irigasi A', '1 set', 500000, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_barang`
--

CREATE TABLE `jenis_barang` (
  `id_jenis_barang` char(7) NOT NULL,
  `jenis_barang` varchar(255) DEFAULT NULL
);

--
-- Dumping data untuk tabel `jenis_barang`
--

INSERT INTO `jenis_barang` (`id_jenis_barang`, `jenis_barang`) VALUES
('JB00001', 'Pupuk'),
('JB00002', 'Benih'),
('JB00003', 'Obat Hama'),
('JB00004', 'Alat Penyiram'),
('JB00005', 'Alat Pertanian'),
('JB00006', 'Sistem Irigasi'),
('JB00007', 'Mulsa'),
('JB00008', 'Kompos'),
('JB00009', 'Kumbung'),
('JB00010', 'Teralis Tanaman');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` char(15) NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `nomor_hp` char(15) NOT NULL,
  `alamat` text NOT NULL
);

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `jenis_kelamin`, `nomor_hp`, `alamat`) VALUES
('cs66d79f44135ba', 'Viona', 'P', '08123456789', 'Mataram'),
('cs66d79f6f672e0', 'Willia', 'P', '08123456789', 'Mataram');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int NOT NULL,
  `id_struk` char(15) DEFAULT NULL,
  `id_pelanggan` char(15) DEFAULT NULL,
  `id_user` char(15) DEFAULT NULL,
  `id_barang` char(7) DEFAULT NULL,
  `tanggal_pembelian` datetime DEFAULT NULL,
  `jumlah_pembelian` int DEFAULT NULL,
  `status_pembelian` enum('Terjual','Belum Terjual') NOT NULL
);

--
-- Dumping data untuk tabel `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `id_struk`, `id_pelanggan`, `id_user`, `id_barang`, `tanggal_pembelian`, `jumlah_pembelian`, `status_pembelian`) VALUES
(1, 'tr66d7a745c7a14', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00016', '2024-09-01 08:18:00', 5, 'Terjual'),
(2, 'tr66d7a745c7a14', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00007', '2024-09-01 08:18:00', 5, 'Terjual'),
(3, 'tr66d7a745c7a14', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00011', '2024-09-01 08:18:00', 5, 'Terjual'),
(4, 'tr66d7a8bd002a6', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00007', '2024-09-02 08:18:00', 20, 'Terjual'),
(5, 'tr66d7a8bd002a6', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00006', '2024-09-02 08:18:00', 20, 'Terjual'),
(6, 'tr66d7a8bd002a6', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00009', '2024-09-02 08:18:00', 20, 'Terjual'),
(7, 'tr66d7a8bd002a6', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00010', '2024-09-02 08:18:00', 20, 'Terjual'),
(8, 'tr66d7a8f5b258e', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00017', '2024-09-03 08:18:00', 10, 'Terjual'),
(9, 'tr66d7a8f5b258e', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00011', '2024-09-03 08:18:00', 10, 'Terjual'),
(10, 'tr66d7a8f5b258e', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00012', '2024-09-03 08:18:00', 10, 'Terjual'),
(11, 'tr66d7a8f5b258e', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00001', '2024-09-03 08:18:00', 10, 'Terjual'),
(12, 'tr66d7a9585715c', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00018', '2024-09-04 08:27:00', 20, 'Terjual'),
(13, 'tr66d7a9585715c', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00019', '2024-09-04 08:27:00', 5, 'Terjual'),
(14, 'tr66d7ace246926', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00016', '2024-09-05 08:42:00', 5, 'Terjual'),
(15, 'tr66d7ace246926', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00018', '2024-09-05 08:42:00', 5, 'Terjual'),
(16, 'tr66d7c2c30d86d', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00016', '2024-09-06 08:42:00', 5, 'Terjual'),
(17, 'tr66d7c2c30d86d', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00017', '2024-09-06 08:42:00', 5, 'Terjual'),
(18, 'tr66d7c7df65160', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00019', '2024-09-07 08:42:00', 5, 'Terjual'),
(19, 'tr66d7c7df65160', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00020', '2024-09-07 08:42:00', 5, 'Terjual'),
(20, 'tr66d7c7df65160', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00001', '2024-09-07 08:42:00', 5, 'Terjual'),
(21, 'tr66d7c7df65160', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00002', '2024-09-07 08:42:00', 5, 'Terjual'),
(22, 'tr66d7c84242ed5', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00005', '2024-09-08 08:42:00', 10, 'Terjual'),
(23, 'tr66d7c84242ed5', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00003', '2024-09-08 08:42:00', 5, 'Terjual'),
(24, 'tr66d7c84242ed5', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00004', '2024-09-08 08:42:00', 5, 'Terjual'),
(25, 'tr66d7c84242ed5', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00002', '2024-09-08 08:42:00', 5, 'Terjual'),
(26, 'tr66d7c84242ed5', 'cs66d79f44135ba', 'us66d79ed2d36ff', 'BA00001', '2024-09-08 08:42:00', 5, 'Terjual'),
(27, 'tr66d7c8b020439', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00013', '2024-09-09 08:42:00', 5, 'Terjual'),
(28, 'tr66d7c8b020439', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00014', '2024-09-09 08:42:00', 5, 'Terjual'),
(29, 'tr66d7c8b020439', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00015', '2024-09-09 08:42:00', 10, 'Terjual'),
(30, 'tr66d7c8b020439', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00012', '2024-09-09 08:42:00', 10, 'Terjual'),
(31, 'tr66d7c8b020439', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00008', '2024-09-09 08:42:00', 10, 'Terjual'),
(32, 'tr66d7c8d3ef9b8', 'cs66d79f6f672e0', 'us66d79ed2d36ff', 'BA00019', '2024-09-10 08:42:00', 10, 'Terjual');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_barang`
--

CREATE TABLE `stok_barang` (
  `id_stok_barang` char(15) NOT NULL,
  `id_barang` char(7) DEFAULT NULL,
  `tanggal_masuk` datetime DEFAULT NULL,
  `jumlah_masuk` int DEFAULT NULL,
  `terjual` int DEFAULT NULL
);

--
-- Dumping data untuk tabel `stok_barang`
--

INSERT INTO `stok_barang` (`id_stok_barang`, `id_barang`, `tanggal_masuk`, `jumlah_masuk`, `terjual`) VALUES
('st66b9c7a1f2e9g', 'BA00002', '2024-09-02 08:00:00', 75, 0),
('st66c8d6a3e7f5h', 'BA00003', '2024-09-03 08:00:00', 80, 0),
('st66d5a9b2c4e6j', 'BA00004', '2024-09-04 08:00:00', 90, 0),
('st66d7a4844c5f8', 'BA00001', '2024-09-01 08:00:00', 100, 0),
('ST66D7A761DDAB3', 'BA00016', '2024-09-04 08:18:41', 0, 5),
('ST66D7A761DF4EA', 'BA00007', '2024-09-04 08:18:41', 0, 5),
('ST66D7A761E146E', 'BA00011', '2024-09-04 08:18:41', 0, 5),
('ST66D7A8D7CA9FA', 'BA00007', '2024-09-04 08:24:55', 0, 20),
('ST66D7A8D7CC5A1', 'BA00006', '2024-09-04 08:24:55', 0, 20),
('ST66D7A8D7CDA88', 'BA00009', '2024-09-04 08:24:55', 0, 20),
('ST66D7A8D7CEFD6', 'BA00010', '2024-09-04 08:24:55', 0, 20),
('ST66D7A9197ACBA', 'BA00017', '2024-09-04 08:26:01', 0, 10),
('ST66D7A9197C605', 'BA00011', '2024-09-04 08:26:01', 0, 10),
('ST66D7A9197DB74', 'BA00012', '2024-09-04 08:26:01', 0, 10),
('ST66D7A9197F651', 'BA00001', '2024-09-04 08:26:01', 0, 10),
('ST66D7A96771F3C', 'BA00018', '2024-09-04 08:27:19', 0, 20),
('ST66D7A967737E3', 'BA00019', '2024-09-04 08:27:19', 0, 5),
('ST66D7ACFC36B55', 'BA00016', '2024-09-04 08:42:36', 0, 5),
('ST66D7ACFC384E1', 'BA00018', '2024-09-04 08:42:36', 0, 5),
('ST66D7C2D3A1F30', 'BA00016', '2024-09-04 10:15:47', 0, 5),
('ST66D7C2D3A36DE', 'BA00017', '2024-09-04 10:15:47', 0, 5),
('ST66D7C7F7D8601', 'BA00002', '2024-09-04 10:37:43', 0, 5),
('ST66D7C7F7DA04E', 'BA00001', '2024-09-04 10:37:43', 0, 5),
('ST66D7C7F7DC2B7', 'BA00020', '2024-09-04 10:37:43', 0, 5),
('ST66D7C7F7DD9C9', 'BA00019', '2024-09-04 10:37:43', 0, 5),
('ST66D7C85953F2C', 'BA00005', '2024-09-04 10:39:21', 0, 10),
('ST66D7C85955AFE', 'BA00004', '2024-09-04 10:39:21', 0, 5),
('ST66D7C85956FD2', 'BA00003', '2024-09-04 10:39:21', 0, 5),
('ST66D7C85958641', 'BA00002', '2024-09-04 10:39:21', 0, 5),
('ST66D7C8595A5D7', 'BA00001', '2024-09-04 10:39:21', 0, 5),
('ST66D7C8C40A361', 'BA00008', '2024-09-04 10:41:08', 0, 10),
('ST66D7C8C40B9B9', 'BA00012', '2024-09-04 10:41:08', 0, 10),
('ST66D7C8C40D667', 'BA00013', '2024-09-04 10:41:08', 0, 5),
('ST66D7C8C40ED5A', 'BA00014', '2024-09-04 10:41:08', 0, 5),
('ST66D7C8C41082F', 'BA00015', '2024-09-04 10:41:08', 0, 10),
('ST66D7C8DDCBF23', 'BA00019', '2024-09-04 10:41:33', 0, 10),
('st66e4b7c1d9g8k', 'BA00005', '2024-09-01 08:00:00', 120, 0),
('st66f3c5a2d9g7l', 'BA00006', '2024-09-02 08:00:00', 60, 0),
('st66g7a2b9e3d6m', 'BA00007', '2024-09-03 08:00:00', 50, 0),
('st66h4d5a1b7g8n', 'BA00008', '2024-09-04 08:00:00', 65, 0),
('st66i2e6c9d3g4o', 'BA00009', '2024-09-01 08:00:00', 70, 0),
('st66j5f7a2b9d1p', 'BA00010', '2024-09-02 08:00:00', 55, 0),
('st66k3c9a1d7e2q', 'BA00011', '2024-09-03 08:00:00', 85, 0),
('st66l4d8a2b9e5r', 'BA00012', '2024-09-04 08:00:00', 95, 0),
('st66m5e7c1d2g9s', 'BA00013', '2024-09-01 08:00:00', 40, 0),
('st66n6d4a9b2g8t', 'BA00014', '2024-09-02 08:00:00', 55, 0),
('st66o7a1b5c3d9u', 'BA00015', '2024-09-03 08:00:00', 70, 0),
('st66p8d2a6b9e1v', 'BA00016', '2024-09-04 08:00:00', 85, 0),
('st66q9e1c3d4g2w', 'BA00017', '2024-09-01 08:00:00', 100, 0),
('st66r2d4a9b5e3x', 'BA00018', '2024-09-02 08:00:00', 120, 0),
('st66s3e7c1d8g4y', 'BA00019', '2024-09-03 08:00:00', 80, 0),
('st66t4d9a2b5g6z', 'BA00020', '2024-09-04 08:00:00', 90, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` char(15) NOT NULL,
  `nama_user` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('Admin','Kasir') NOT NULL
);

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama_user`, `username`, `password`, `level`) VALUES
('us66d79ed2d36ff', 'Jhon Doe', 'admin', '$2y$10$gpoTq4hsi6ai7T/OhC2as.ka0wKNV3YcW5bLBG4w/pKFvob.t.wFK', 'Admin'),
('us66d7ae3b2f367', 'Jane Doe', 'kasir', '$2y$10$VrPKMbxmyD.SLzlYx87rT.n8QWFdn7Nyu9PdPy9Us7hjY4DpALlLa', 'Kasir');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indeks untuk tabel `jenis_barang`
--
ALTER TABLE `jenis_barang`
  ADD PRIMARY KEY (`id_jenis_barang`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indeks untuk tabel `stok_barang`
--
ALTER TABLE `stok_barang`
  ADD PRIMARY KEY (`id_stok_barang`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
