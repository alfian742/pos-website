<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}

if (isset($_GET['id_barang'])) {
    $id_barang = $_GET['id_barang'];

    $ambil_data = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang='$id_barang'");

    if (mysqli_num_rows($ambil_data) > 0) {
        $data = mysqli_fetch_array($ambil_data);
    } else {
        $_SESSION['warning'] = "Data tidak ditemukan!";
        echo "<script>window.location.href = 'media.php?page=data_barang';</script>";
        exit();
    }
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Ubah Data Barang</h3>
    </div>
    <div class="card rounded-4 border-0 shadow mb-4">
        <div class="card-body">
            <!-- Alers -->
            <?php include('alerts.php') ?>

            <form method="post" action="action.php?ubah_barang=<?= $data['id_barang']; ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="id_barang">ID Barang <span class="text-danger">*</span></label>
                            <input class="form-control <?= isset($_SESSION['errors']['id_barang']) ? 'is-invalid' : ''; ?>" name="id_barang" value="<?= $data['id_barang']; ?>" id="id_barang" type="text" placeholder="Masukan ID Barang" disabled />
                            <small>Contoh: BA00001</small>
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['id_barang']) ? $_SESSION['errors']['id_barang'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                            <input class="form-control <?= isset($_SESSION['errors']['nama_barang']) ? 'is-invalid' : ''; ?>" name="nama_barang" value="<?= $data['nama_barang']; ?>" id="nama_barang" type="text" placeholder="Masukan Nama Barang" />
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['nama_barang']) ? $_SESSION['errors']['nama_barang'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="unit">Unit <span class="text-danger">*</span></label>
                            <input class="form-control <?= isset($_SESSION['errors']['unit']) ? 'is-invalid' : ''; ?>" name="unit" value="<?= $data['unit'] ?>" id="unit" type="text" placeholder="Masukan unit Barang" />
                            <small>Contoh: 10kg, 100g, 10 butir, dan lain-lain</small>
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['unit']) ? $_SESSION['errors']['unit'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="harga">Harga (Rp) <span class="text-danger">*</span></label>
                            <input class="form-control <?= isset($_SESSION['errors']['harga']) ? 'is-invalid' : ''; ?>" name="harga" value="<?= $data['harga']; ?>" id="harga" type="number" placeholder="Masukan Harga Barang" />
                            <small>Contoh: 100000</small>
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['harga']) ? $_SESSION['errors']['harga'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="id_jenis_barang">Jenis Barang <span class="text-danger">*</span></label>
                            <select class="form-select select2 <?= isset($_SESSION['errors']['id_jenis_barang']) ? 'is-invalid' : ''; ?>" name="id_jenis_barang" id="id_jenis_barang">
                                <option value="" disabled>-- Pilih Jenis Barang --</option>

                                <?php
                                $queryJenisBarang = mysqli_query($koneksi, "SELECT * FROM jenis_barang ORDER BY jenis_barang ASC");
                                while ($hasil = mysqli_fetch_array($queryJenisBarang)):
                                ?>
                                    <option value="<?= $hasil['id_jenis_barang']; ?>" <?= $data['id_jenis_barang'] == $hasil['id_jenis_barang'] ? 'selected' : ''; ?>><?= $hasil['jenis_barang']; ?></option>
                                <?php endwhile ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= isset($_SESSION['errors']['id_jenis_barang']) ? $_SESSION['errors']['id_jenis_barang'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="kedaluwarsa">Tanggal Kedaluwarsa</label>
                            <input class="form-control" name="kedaluwarsa" value="<?= $data['kedaluwarsa']; ?>" id="kedaluwarsa" type="date" />
                            <small>Kosongkan jika tidak ada tanggal kedaluwarsa</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="dashboard.php?page=barang-data" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left me-1"></i> Batal</a>
                    <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-save me-1"></i> Simpan</button>
                </div>
            </form>

            <?php unset($_SESSION['errors']); ?>
        </div>
    </div>
</div>