<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Tambah Stok Barang</h3>
    </div>
    <div class="card rounded-4 border-0 shadow mb-4">
        <div class="card-body">
            <!-- Alers -->
            <?php include('alerts.php') ?>

            <?php
            $idBarangValue = isset($_SESSION['form_data']['id_barang']) ? $_SESSION['form_data']['id_barang'] : '';
            $tanggalValue = isset($_SESSION['form_data']['tanggal']) ? $_SESSION['form_data']['tanggal'] : '';
            $waktuValue = isset($_SESSION['form_data']['waktu']) ? $_SESSION['form_data']['waktu'] : '';
            $jumlahMasukValue = isset($_SESSION['form_data']['jumlah_masuk']) ? $_SESSION['form_data']['jumlah_masuk'] : '';
            ?>

            <form method="post" action="action.php">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="id_barang">Nama Barang <span class="text-danger">*</span></label>
                            <select class="form-select select2 <?= isset($_SESSION['errors']['id_barang']) ? 'is-invalid' : ''; ?>" name="id_barang" id="id_barang">
                                <option value="" disabled <?= empty($idBarangValue) ? 'selected' : ''; ?>>-- Pilih Barang --</option>

                                <?php
                                $queryBarang = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang ASC");
                                while ($hasil = mysqli_fetch_array($queryBarang)):
                                ?>
                                    <option value="<?= $hasil['id_barang']; ?>" <?= $idBarangValue == $hasil['id_barang'] ? 'selected' : ''; ?>><?= '[' . $hasil['id_barang'] . '] ' . $hasil['nama_barang']; ?></option>
                                <?php endwhile ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['id_barang']) ? $_SESSION['errors']['id_barang'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="jumlah_masuk">Jumlah Barang Masuk <span class="text-danger">*</span></label>
                            <input class="form-control <?= isset($_SESSION['errors']['jumlah_masuk']) ? 'is-invalid' : ''; ?>" name="jumlah_masuk" value="<?= $jumlahMasukValue; ?>" id="jumlah_masuk" type="number" placeholder="Masukan Jumlah Barang Masuk" />
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['jumlah_masuk']) ? $_SESSION['errors']['jumlah_masuk'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="tanggal">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input class="form-control <?= isset($_SESSION['errors']['tanggal']) ? 'is-invalid' : ''; ?>" name="tanggal" value="<?= $tanggalValue; ?>" id="tanggal" type="date" />
                            <div class=" invalid-feedback">
                                <?= isset($_SESSION['errors']['tanggal']) ? $_SESSION['errors']['tanggal'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="waktu">Waktu Masuk <span class="text-danger">*</span></label>
                            <input class="form-control <?= isset($_SESSION['errors']['waktu']) ? 'is-invalid' : ''; ?>" name="waktu" value="<?= $waktuValue; ?>" id="waktu" type="time" />
                            <div class=" invalid-feedback">
                                <?= isset($_SESSION['errors']['waktu']) ? $_SESSION['errors']['waktu'] : '' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="dashboard.php?page=stok-barang-data" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left me-1"></i> Batal</a>
                    <button class="btn btn-success btn-sm" name="tambah_stok_barang" type="submit"><i class="fa fa-save me-1"></i> Simpan</button>
                </div>
            </form>

            <?php
            unset($_SESSION['errors']);
            unset($_SESSION['form_data']);
            ?>
        </div>
    </div>
</div>