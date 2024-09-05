<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}

if (isset($_GET['id_jenis_barang'])) {
    $id_jenis_barang = $_GET['id_jenis_barang'];

    $ambil_data = mysqli_query($koneksi, "SELECT * FROM jenis_barang WHERE id_jenis_barang='$id_jenis_barang'");

    if (mysqli_num_rows($ambil_data) > 0) {
        $data = mysqli_fetch_array($ambil_data);
    } else {
        $_SESSION['warning'] = "Data tidak ditemukan!";
        echo "<script>window.location.href = 'media.php?page=data_jenis_barang';</script>";
        exit();
    }
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Ubah Jenis Barang</h3>
    </div>
    <div class="card rounded-4 border-0 shadow mb-4">
        <div class="card-body">
            <!-- Alers -->
            <?php include('alerts.php') ?>

            <form method="post" action="action.php?ubah_jenis_barang=<?= $data['id_jenis_barang']; ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="id_jenis_barang">ID Jenis Barang <span class="text-danger">*</span></label>
                            <input class="form-control <?= isset($_SESSION['errors']['id_jenis_barang']) ? 'is-invalid' : ''; ?>" name="id_jenis_barang" value="<?= $data['id_jenis_barang']; ?>" id="id_jenis_barang" type="text" placeholder="Masukan ID Jenis Barang" disabled />
                            <small>Contoh: JB00001</small>
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['id_jenis_barang']) ? $_SESSION['errors']['id_jenis_barang'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="jenis_barang">Jenis Barang <span class="text-danger">*</span></label>
                            <input class="form-control <?= isset($_SESSION['errors']['jenis_barang']) ? 'is-invalid' : ''; ?>" name="jenis_barang" value="<?= $data['jenis_barang']; ?>" id="jenis_barang" type="text" placeholder="Masukan Jenis Barang" />
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['jenis_barang']) ? $_SESSION['errors']['jenis_barang'] : '' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="dashboard.php?page=jenis-barang-data" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left me-1"></i> Batal</a>
                    <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-save me-1"></i> Simpan</button>
                </div>
            </form>

            <?php unset($_SESSION['errors']); ?>
        </div>
    </div>
</div>