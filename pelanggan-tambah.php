<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin' && $_SESSION['level'] != 'Kasir')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Tambah Data Pelanggan</h3>
    </div>
    <div class="card rounded-4 border-0 shadow mb-4">
        <div class="card-body">
            <!-- Alers -->
            <?php include('alerts.php') ?>

            <?php
            $namaPelangganValue = isset($_SESSION['form_data']['nama_pelanggan']) ? $_SESSION['form_data']['nama_pelanggan'] : '';
            $jenisKelaminValue = isset($_SESSION['form_data']['jenis_kelamin']) ? $_SESSION['form_data']['jenis_kelamin'] : '';
            $nomorHPValue = isset($_SESSION['form_data']['nomor_hp']) ? $_SESSION['form_data']['nomor_hp'] : '';
            $alamatValue = isset($_SESSION['form_data']['alamat']) ? $_SESSION['form_data']['alamat'] : '';
            $statusValue = isset($_SESSION['form_data']['status']) ? $_SESSION['form_data']['status'] : '';
            ?>

            <form method="post" action="action.php">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="nama_pelanggan">Nama Lengkap <span class="text-danger">*</span></label>
                            <input class="form-control <?= isset($_SESSION['errors']['nama_pelanggan']) ? 'is-invalid' : ''; ?>" name="nama_pelanggan" value="<?= $namaPelangganValue; ?>" id="nama_pelanggan" type="text" placeholder="Masukan Nama Lengkap" />
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['nama_pelanggan']) ? $_SESSION['errors']['nama_pelanggan'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select <?= isset($_SESSION['errors']['jenis_kelamin']) ? 'is-invalid' : ''; ?>" name="jenis_kelamin" id="jenis_kelamin">
                                <option value="" disabled <?= empty($jenisKelaminValue) ? 'selected' : ''; ?>>-- Pilih Jenis Kelamin --</option>
                                <option value="L" <?= $jenisKelaminValue === 'L' ? 'selected' : ''; ?>>Laki-Laki</option>
                                <option value="p" <?= $jenisKelaminValue === 'p' ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['jenis_kelamin']) ? $_SESSION['errors']['jenis_kelamin'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="nomor_hp">Nomor HP <span class="text-danger">*</span></label>
                            <input class="form-control <?= isset($_SESSION['errors']['nomor_hp']) ? 'is-invalid' : ''; ?>" name="nomor_hp" value="<?= $nomorHPValue; ?>" id="nomor_hp" type="teL" placeholder="Masukan Nomor HP" />
                            <small>Contoh: 081123456789</small>
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['nomor_hp']) ? $_SESSION['errors']['nomor_hp'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="alamat">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control <?= isset($_SESSION['errors']['alamat']) ? 'is-invalid' : ''; ?>" id="alamat" name="alamat" placeholder="Masukan Alamat" rows="1"><?= $alamatValue; ?></textarea>
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['alamat']) ? $_SESSION['errors']['alamat'] : '' ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="dashboard.php?page=pelanggan-data" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left me-1"></i> Batal</a>
                    <button class="btn btn-success btn-sm" name="tambah_pelanggan" type="submit"><i class="fa fa-save me-1"></i> Simpan</button>
                </div>
            </form>

            <?php
            unset($_SESSION['errors']);
            unset($_SESSION['form_data']);
            ?>
        </div>
    </div>
</div>