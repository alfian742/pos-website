<?php
session_start();
include('config.php');

if (!isset($_SESSION['username']) || !isset($_SESSION['level'])) {
    $_SESSION['warning'] = "Halaman tidak dapat diakses. Silahkan masuk!";
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}

if (isset($_POST['periode_awal']) && isset($_POST['periode_akhir'])) {
    $periode_awal = $_POST['periode_awal'] . " 00:00:00";
    $periode_akhir = $_POST['periode_akhir'] . " 23:59:59";

    $sqlPrintLaporan = "SELECT 
                            penjualan.id_struk, 
                            penjualan.tanggal_pembelian, 
                            pelanggan.nama_pelanggan,
                            SUM(barang.harga * penjualan.jumlah_pembelian) AS total_transaksi
                        FROM penjualan
                        INNER JOIN pelanggan ON penjualan.id_pelanggan = pelanggan.id_pelanggan
                        INNER JOIN barang ON penjualan.id_barang = barang.id_barang
                        WHERE tanggal_pembelian BETWEEN '$periode_awal' AND '$periode_akhir'
                        GROUP BY penjualan.id_struk, penjualan.tanggal_pembelian, pelanggan.nama_pelanggan
                        ORDER BY penjualan.tanggal_pembelian ASC";
    $queryPrintLaporan = mysqli_query($koneksi, $sqlPrintLaporan);

    // Nama Aplikasi
    $app_name = "Sistem Informasi Penjualan";
} else {
    $_SESSION['warning'] = "Silahkan masukan kembali periode!";
    echo "<script>window.location.href = 'media.php?page=penjualan-data';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $app_name; ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/logo-primary.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/img/logo-primary.png">

    <!-- CSS Core -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        table {
            font-size: 12px !important;
        }
    </style>
</head>

<body>
    <main class="d-flex justify-content-center">
        <div class="container-fluid">
            <div class="pb-2 mb-4 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <img src="assets/img/logo-primary.png" alt="Logo" width="64" height="64">
                    <div class="d-flex flex-column gap-1">
                        <h4 class="mb-0 text-uppercase"><?= $app_name; ?></h4>
                        <small class="fst-italic">Jl. Nusantara No.123, Mataram, Nusa Tenggara Barat, 83322</small>
                    </div>
                </div>
            </div>

            <div class="mb-4 text-center">
                <h4>Laporan Penjualan</h4>
                <span>Priode: <?= date('d F Y', strtotime($periode_awal)); ?> s/d <?= date('d F Y', strtotime($periode_akhir)); ?></span>
            </div>

            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Tanggal Transaksi</th>
                        <th>ID Struk</th>
                        <th>Nama Pelanggan</th>
                        <th>Total Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    $no = 1;

                    if (mysqli_num_rows($queryPrintLaporan) > 0):
                    ?>
                        <?php while ($data = mysqli_fetch_array($queryPrintLaporan)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($data['tanggal_pembelian'])); ?></td>
                                <td><?= $data['id_struk'] ?></td>
                                <td><?= $data['nama_pelanggan'] ?></td>
                                <td>
                                    <?php
                                    $totalTransaksi = $data['total_transaksi'];

                                    echo 'Rp ' . number_format($totalTransaksi, 0, ',', '.')
                                    ?>
                                </td>
                            </tr>
                            <?php $total += $totalTransaksi; ?>
                        <?php endwhile ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak Ada Data</td>
                        </tr>
                    <?php endif ?>
                    <tr>
                        <th colspan="4" class="text-center">Total Keseluruhan</th>
                        <th><?= 'Rp ' . number_format($total, 0, ',', '.'); ?></th>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-between gap-2">
                <small>Dicetak oleh: <span class="fw-bold"><?= $_SESSION['nama_user'] . ' [' . $_SESSION['level'] . ']' ?></span></small>
                <small>Dicetak pada: <span class="fw-bold"><?= date('d F Y'); ?></span></small>
            </div>
        </div>
    </main>

    <!-- JS Core -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>