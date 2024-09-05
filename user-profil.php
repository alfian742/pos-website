<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin' && $_SESSION['level'] != 'Kasir')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    $ambil_data = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user='$id_user'");

    if (mysqli_num_rows($ambil_data) > 0) {
        $data = mysqli_fetch_array($ambil_data);
    } else {
        $_SESSION['warning'] = "Data tidak ditemukan!";
        echo "<script>window.location.href = 'dashboard.php';</script>";
        exit();
    }
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Ubah Profil</h3>
    </div>
    <div class="card rounded-4 border-0 shadow mb-4">
        <div class="card-body">
            <!-- Alers -->
            <?php include('alerts.php'); ?>

            <form method="post" action="action.php?profil=<?= $data['id_user'] ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="nama_user">Nama Lengkap <span class="text-danger">*</span></label>
                            <input class="form-control <?= (isset($_SESSION['errors']['nama_user'])) ? 'is-invalid' : ''; ?>" name="nama_user" value="<?= $data['nama_user'] ?>" id="nama_user" type="text" placeholder="Masukan Nama Lengkap" />
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['nama_user']) ? $_SESSION['errors']['nama_user'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="username">Nama Pengguna <span class="text-danger">*</span></label>
                            <input class="form-control <?= (isset($_SESSION['errors']['username'])) ? 'is-invalid' : ''; ?>" name="username" value="<?= $data['username'] ?>" id="username" type="text" placeholder="Masukan Nama Pengguna" />
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['username']) ? $_SESSION['errors']['username'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="password">Kata Sandi</label>
                            <input class="form-control" id="password" name="password" type="password" placeholder="Masukan Kata Sandi" />
                            <small>Silahkan isi jika ingin mengubah kata sandi</small>
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['username']) ? $_SESSION['errors']['username'] : '' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="dashboard.php" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left me-1"></i> Batal</a>
                    <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-save me-1"></i> Simpan</button>
                </div>
            </form>

            <?php unset($_SESSION['errors']); ?>
        </div>
    </div>
</div>