<?php
session_start();
include('config.php');

if (!isset($_SESSION['username']) || $_SESSION['level'] != 'Admin') {
    $_SESSION['warning'] = "Halaman tidak dapat diakses. Silahkan masuk!";
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}

if (isset($_POST['periode_awal']) && isset($_POST['periode_akhir'])) {
    $periode_awal = $_POST['periode_awal'] . " 00:00:00";
    $periode_akhir = $_POST['periode_akhir'] . " 23:59:59";

    $sqlPrintLaporan = "SELECT * FROM stok_barang 
                        INNER JOIN barang ON stok_barang.id_barang=barang.id_barang 
                        INNER JOIN jenis_barang ON barang.id_jenis_barang=jenis_barang.id_jenis_barang
                        WHERE jumlah_masuk>0 AND tanggal_masuk BETWEEN '$periode_awal' AND '$periode_akhir'
                        ORDER BY tanggal_masuk ASC";
    $queryPrintLaporan = mysqli_query($koneksi, $sqlPrintLaporan);
} else {
    $_SESSION['warning'] = "Silahkan masukan kembali periode!";
    echo "<script>window.location.href = 'media.php?page=data_riwayat_barang';</script>";
    exit();
}

// Nama aplikasi
$app_name = "Sistem Informasi Penjualan";
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
                <h4>Laporan Barang Masuk</h4>
                <span>Priode: <?= date('d F Y', strtotime($periode_awal)); ?> s/d <?= date('d F Y', strtotime($periode_akhir)); ?></span>
            </div>

            <div class="mb-4">
                <small class="mb-0"><span class="text-danger">*</span> Catatan: Subtotal = Harga Perunit ✕ Jumlah Masuk.</small>
            </div>

            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr class="text-center align-middle">
                        <th>No</th>
                        <th>Tanggal Masuk</th>
                        <th>ID Stok Barang</th>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Jenis Barang</th>
                        <th>Harga Perunit</th>
                        <th>Unit</th>
                        <th>Tanggal Kedaluwarsa</th>
                        <th>Jumlah Masuk</th>
                        <th>Sub Total</th>
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
                                <td><?= date('d-m-Y H:i', strtotime($data['tanggal_masuk'])); ?></td>
                                <td><?= $data['id_stok_barang'] ?></td>
                                <td><?= $data['id_barang'] ?></td>
                                <td><?= $data['nama_barang'] ?></td>
                                <td><?= $data['jenis_barang'] ?></td>
                                <td><?= 'Rp ' . number_format($data['harga'], 0, ',', '.') ?></td>
                                <td><?= $data['unit'] ?></td>
                                <td>
                                    <?php
                                    if ($data['kedaluwarsa'] != NULL) {
                                        echo date('d-m-Y', strtotime($data['kedaluwarsa']));
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?= $data['jumlah_masuk']; ?></td>
                                <td>
                                    <?php
                                    $subtotal = $data['harga'] * $data['jumlah_masuk'];

                                    echo 'Rp ' . number_format($subtotal, 0, ',', '.')
                                    ?>
                                </td>
                            </tr>
                            <?php $total += $subtotal; ?>
                        <?php endwhile ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="11" class="text-center">Tidak Ada Data</td>
                        </tr>
                    <?php endif ?>
                    <tr>
                        <th colspan="10" class="text-center">Total</th>
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