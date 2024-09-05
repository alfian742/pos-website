<?php
// Memulai sesi
session_start();

// Konfigurasi
include('config.php');

// Cek Login
if (!isset($_SESSION['username']) && !isset($_SESSION['level'])) {
    $_SESSION['warning'] = "Halaman tidak dapat diakses, silahkan masuk.";
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}

// Nama Aplikasi
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
    <link rel="stylesheet" href="assets/css/datatables-simple.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- Plugin -->
    <link rel="stylesheet" href="assets/css/select2.min.css">
    <link rel="stylesheet" href="assets/css/select2-bootstrap-5-theme.min.css">
    <script src="assets/js/fontawesome-free.js"></script>
</head>

<body class="sb-nav-fixed bg-light">
    <nav class="sb-topnav navbar navbar-expand navbar-light bg-white shadow-sm">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3 fw-semibold" href="dashboard.php">
            <div class="d-flex align-items-center gap-2">
                <img src="assets/img/logo-primary.png" alt="Logo" width="34" height="34">
                <small class="d-none d-lg-inline text-uppercase"><?= $app_name; ?></small>
            </div>
        </a>

        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 d-lg-none" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>

        <!-- Navbar-->
        <ul class="navbar-nav ms-auto me-2 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle dropdown-toggle-no-caret" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <div class="d-flex justify-content-end align-items-center gap-2">
                        <span class="d-none d-lg-inline fw-semibold"><?= $_SESSION['nama_user']; ?></span>
                        <div class="user-avatar d-flex justify-content-center align-items-center text-bg-success text-center fw-bold p-3">
                            <?= substr(explode(' ', $_SESSION['nama_user'])[0], 0, 1); ?>
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow" aria-labelledby="navbarDropdown">
                    <li>
                        <a class="dropdown-item" href="dashboard.php?page=user-profil&id_user=<?= $_SESSION['id_user']; ?>">
                            <i class="dropdown-icons fas fa-user me-2"></i> Profil
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="dropdown-icons fas fa-right-from-bracket me-2"></i> Keluar
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark bg-success" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav pb-4">
                        <div class="sb-sidenav-menu-heading">Dashboard</div>
                        <a class="nav-link <?= (!isset($_GET['page']) || $_GET['page'] == '') ? 'active' : '' ?>" href="dashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        <?php if ($_SESSION['level'] == 'Admin'): ?>
                            <div class="sb-sidenav-menu-heading">Pengguna</div>
                            <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'user-data') ? 'active' : '' ?>" href="dashboard.php?page=user-data">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-tie"></i></div>
                                Data Pengguna
                            </a>
                        <?php endif ?>

                        <div class="sb-sidenav-menu-heading">Pelanggan</div>
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'pelanggan-data') ? 'active' : '' ?>" href="dashboard.php?page=pelanggan-data">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Data Pelanggan
                        </a>

                        <div class="sb-sidenav-menu-heading">Barang</div>
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'barang-data') ? 'active' : '' ?>" href="dashboard.php?page=barang-data">
                            <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                            Data Barang
                        </a>
                        <?php if ($_SESSION['level'] == 'Admin'): ?>
                            <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'jenis-barang-data') ? 'active' : '' ?>" href="dashboard.php?page=jenis-barang-data">
                                <div class="sb-nav-link-icon"><i class="fas fa-cubes"></i></div>
                                Jenis Barang
                            </a>
                            <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'stok-barang-data') ? 'active' : '' ?>" href="dashboard.php?page=stok-barang-data">
                                <div class="sb-nav-link-icon"><i class="fas fa-boxes-stacked"></i></div>
                                Stok Barang
                            </a>
                        <?php endif ?>

                        <div class="sb-sidenav-menu-heading">Transaksi</div>
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'penjualan-transaksi') ? 'active' : '' ?>" href="dashboard.php?page=penjualan-transaksi">
                            <div class="sb-nav-link-icon"><i class="fas fa-money-bill"></i></div>
                            Transaksi
                        </a>

                        <div class="sb-sidenav-menu-heading">Laporan</div>
                        <?php if ($_SESSION['level'] == 'Admin'): ?>
                            <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'stok-barang-riwayat') ? 'active' : '' ?>" href="dashboard.php?page=stok-barang-riwayat">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Barang Masuk
                            </a>
                        <?php endif ?>
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'penjualan-data') ? 'active' : '' ?>" href="dashboard.php?page=penjualan-data">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Penjualan
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <!-- Content -->
                <?php include('content.php'); ?>
            </main>

            <footer class="py-4 mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-center">
                        <span class="text-muted">&copy; <?= date('Y') . ' ' . $app_name; ?></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="logoutModalLabel">Keluar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <strong class="text-capitalize"><?= $_SESSION['nama_user']; ?></strong>, apakah anda ingin mengakhiri sesi ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <a type="button" class="btn btn-danger" href="action.php?logout">Keluar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Core -->
    <script src="assets/js/jquery.slim.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/datatables-simple.min.js"></script>

    <!-- Plugin -->
    <script src="assets/js/select2.full.min.js"></script>
    <script src="assets/js/scripts.js"></script>
</body>

</html>