<?php
// Memulai sesi
session_start();

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
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- Plugin -->
    <script src="assets/js/fontawesome-free.js"></script>
</head>

<body class="bg-light">
    <main class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                    <div class="card shadow-lg border-0 rounded-4 p-2 p-md-4">
                        <div class="card-body">
                            <img src="assets/img/logo-primary.png" alt="Logo" class="d-block mx-auto mb-2" height="75rem" width="75rem">
                            <h3 class="text-center font-weight-light text-uppercase mb-4"><?= $app_name; ?></h3>

                            <!-- Alerts -->
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success text-center mb-4" role="alert">
                                    <i class="fas fa-circle-check me-1"></i> <?= $_SESSION['success']; ?>
                                </div>
                                <?php unset($_SESSION['success']) ?>
                            <?php endif ?>

                            <?php if (isset($_SESSION['warning'])): ?>
                                <div class="alert alert-warning text-center mb-4" role="alert">
                                    <i class="fas fa-triangle-exclamation me-1"></i> <?= $_SESSION['warning']; ?>
                                </div>
                                <?php unset($_SESSION['warning']) ?>
                            <?php endif ?>

                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger text-center mb-4" role="alert">
                                    <i class="fas fa-circle-xmark me-1"></i> <?= $_SESSION['error']; ?>
                                </div>
                                <?php unset($_SESSION['error']) ?>
                            <?php endif ?>

                            <?php
                            // Konfigurasi
                            include('config.php');

                            // Ambil data di tabel user
                            $user = mysqli_query($koneksi, "SELECT * FROM user");

                            // Cek data
                            if (mysqli_num_rows($user) > 0) :
                            ?>
                                <form method="post" action="action.php?login">
                                    <div class="form-floating mb-4">
                                        <input class="form-control <?= (isset($_SESSION['errors']['username'])) ? 'is-invalid' : ''; ?>" id="username" name="username" type="text" placeholder="Nama Pengguna" />
                                        <label for="username">Nama Pengguna</label>
                                        <div class="invalid-feedback">
                                            <?= isset($_SESSION['errors']['username']) ? $_SESSION['errors']['username'] : '' ?>
                                        </div>
                                    </div>

                                    <div class="form-floating mb-4">
                                        <input class="form-control <?= (isset($_SESSION['errors']['password'])) ? 'is-invalid' : ''; ?>" id="password" name="password" type="password" placeholder="Kata Sandi" />
                                        <label for="password">Kata Sandi</label>
                                        <div class="invalid-feedback">
                                            <?= isset($_SESSION['errors']['password']) ? $_SESSION['errors']['password'] : '' ?>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <button class="btn btn-lg btn-success fs-3 fw-semibold w-100" type="submit">MASUK</button>
                                    </div>

                                    <div class="text-center">
                                        <span>&copy; <?= date('Y') . ' ' . $app_name; ?></span>
                                    </div>
                                </form>

                                <?php unset($_SESSION['errors']); ?>
                            <?php else: ?>
                                <?php
                                // Hanya muncul jika tabel user kosong
                                if (isset($_POST['buat_akun'])) {
                                    $id_user        = 'us' . uniqid();
                                    $nama           = "Admin";
                                    $username       = "admin";
                                    $password       = "admin";
                                    $password_hash  = password_hash($password, PASSWORD_DEFAULT);
                                    $level          = "Admin";

                                    $buat_akun       = mysqli_query($koneksi, "INSERT INTO user VALUES('$id_user', '$nama', '$username', '$password_hash', '$level')");

                                    if ($buat_akun) {
                                        $_SESSION['success'] = "<small>Akun berhasil dibuat, silahkan masuk dengan nama pengguna '$username' dan kata sandi '$password'.</small>";
                                    } else {
                                        $_SESSION['error'] = "Akun gagal dibuat, silahkan coba kembali!";
                                    }

                                    echo "<script>window.location.href = 'login.php';</script>";
                                    exit();
                                }
                                ?>

                                <form method="post" action="">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <button class="btn btn-lg btn-success fs-3 fw-semibold w-100" name="buat_akun" type="submit">Buat Akun</button>
                                    </div>

                                    <div class="text-center">
                                        <span>&copy; <?= date('Y') . ' ' . $app_name; ?></span>
                                    </div>
                                </form>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JS Core -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>