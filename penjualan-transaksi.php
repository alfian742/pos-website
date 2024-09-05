<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin' && $_SESSION['level'] != 'Kasir')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}
?>

<div class="container-fluid px-4">
    <div class="row g-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center gap-2 my-4">
                <h3 class="mb-0">Penjualan Barang</h3>
            </div>
            <div class="card rounded-4 border-0 shadow mb-4">
                <div class="card-body">
                    <!-- Alers -->
                    <?php include('alerts.php') ?>

                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Jenis</th>
                                <th>Harga Perunit</th>
                                <th>Unit</th>
                                <th>Tanggal Kedaluwarsa</th>
                                <th>Stok Tersedia</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;

                            $sqlBarang = "SELECT 
                                            stok_barang.id_barang, 
                                            barang.nama_barang, 
                                            jenis_barang.jenis_barang,
                                            barang.harga,
                                            barang.unit,
                                            barang.kedaluwarsa,
                                            SUM(stok_barang.jumlah_masuk) AS total_masuk,
                                            SUM(stok_barang.terjual) AS total_terjual
                                        FROM stok_barang 
                                        INNER JOIN barang ON stok_barang.id_barang = barang.id_barang
                                        INNER JOIN jenis_barang ON jenis_barang.id_jenis_barang = barang.id_jenis_barang 
                                        GROUP BY stok_barang.id_barang, barang.nama_barang, jenis_barang.jenis_barang, barang.harga, barang.unit, barang.kedaluwarsa
                                        HAVING (SUM(stok_barang.jumlah_masuk) - SUM(stok_barang.terjual)) > 0
                                        ORDER BY barang.nama_barang ASC";

                            // Pilihan ORDER BY
                            // ORDER BY MAX(stok_barang.tanggal_masuk) DESC
                            // ORDER BY ORDER BY barang.nama_barang ASC

                            $queryBarang = mysqli_query($koneksi, $sqlBarang);

                            while ($data = mysqli_fetch_array($queryBarang)) :
                                // Query untuk memeriksa apakah barang sudah ada di keranjang
                                $id_barang = $data['id_barang'];
                                $sqlCekKeranjang = "SELECT COUNT(*) AS count 
                                                    FROM penjualan 
                                                    WHERE id_barang='$id_barang' 
                                                    AND status_pembelian='Belum Terjual'";
                                $queryCekKeranjang = mysqli_query($koneksi, $sqlCekKeranjang);
                                $dataKeranjang = mysqli_fetch_array($queryCekKeranjang);

                                $keranjang = $dataKeranjang['count'] > 0;
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
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
                                    <td><span id="stokTersedia_<?= $data['id_barang'] ?>"><?= $data['total_masuk'] - $data['total_terjual'] ?></span></td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <?php if ($keranjang): ?>
                                                <h4 class="text-info mb-0"><i class="fas fa-circle-check"></i></h4>
                                            <?php else: ?>
                                                <a href="action.php?tambah_keranjang=<?= $data['id_barang'] ?>" class="btn btn-success btn-sm me-1">
                                                    <i class="fas fa-cart-shopping"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-4">
                <h3 class="mb-0">Keranjang Belanja</h3>
            </div>
            <div class="card rounded-4 border-0 shadow mb-4">
                <div class="card-body">
                    <?php if (isset($_SESSION['success_cart'])): ?>
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-circle-check me-1"></i> <?= $_SESSION['success_cart']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                        <?php unset($_SESSION['success_cart']) ?>
                    <?php endif ?>

                    <?php if (isset($_SESSION['error_cart'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-xmark me-1"></i> <?= $_SESSION['error_cart']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                        <?php unset($_SESSION['error_cart']) ?>
                    <?php endif ?>

                    <?php
                    $sqlPenjualan = "SELECT * FROM penjualan
                                        INNER JOIN barang ON penjualan.id_barang=barang.id_barang
                                        INNER JOIN jenis_barang ON barang.id_jenis_barang=jenis_barang.id_jenis_barang
                                        WHERE status_pembelian='Belum Terjual'
                                        ORDER BY nama_barang ASC";
                    $queryPenjualan = mysqli_query($koneksi, $sqlPenjualan);

                    if (mysqli_num_rows($queryPenjualan) > 0):
                        $id_struk = 'tr' . uniqid();
                    ?>
                        <form method="post" action="action.php">
                            <input type="hidden" name="id_user" value="<?= $_SESSION['id_user']; ?>">
                            <input type="hidden" name="id_struk" value="<?= $id_struk; ?>">

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="tanggal_pembelian">Tanggal Pembelian <span class="text-danger">*</span></label>
                                        <input class="form-control bg-light" name="tanggal_pembelian" value="<?= date('d-m-Y H:i'); ?>" id="tanggal_pembelian" type="datetime" readonly />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="id_pelanggan">Nama Pelanggan <span class="text-danger">*</span></label>
                                        <select class="form-select select2" name="id_pelanggan" id="id_pelanggan" required>
                                            <option value="" disabled selected>-- Pilih Pelanggan --</option>
                                            <?php
                                            $queryPelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC");
                                            while ($hasil = mysqli_fetch_array($queryPelanggan)):
                                            ?>
                                                <option value="<?= $hasil['id_pelanggan']; ?>"><?= $hasil['nama_pelanggan'] . ' [' . $hasil['nomor_hp'] . ']'; ?></option>
                                            <?php endwhile ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-nowrap">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>ID Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Jenis</th>
                                            <th>Harga Perunit</th>
                                            <th>Stok Tersedia</th>
                                            <th style="width: 10rem;">Jumlah Pembelian</th>
                                            <th>Sub Total</th>
                                            <th>Sisa Stok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;

                                        while ($data = mysqli_fetch_array($queryPenjualan)) :
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $data['id_barang'] ?></td>
                                                <td><?= $data['nama_barang'] ?></td>
                                                <td><?= $data['jenis_barang'] ?></td>
                                                <td><?= 'Rp ' . number_format($data['harga'], 0, ',', '.') ?></td>
                                                <td><span id="salinanStokTersedia_<?= $data['id_barang'] ?>"></span></td>
                                                <td>
                                                    <input class="form-control" type="number" name="jumlah_pembelian[<?= $data['id_barang'] ?>]" id="jumlah_pembelian_<?= $data['id_barang'] ?>" value="<?= $jumlahPembelian ?>" min="0" required style="width: 10rem;">
                                                </td>
                                                <td>
                                                    <span id="subTotal_<?= $data['id_barang'] ?>">Rp 0</span>
                                                </td>
                                                <td><span id="sisaStokTersedia_<?= $data['id_barang'] ?>"></span></td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a href="action.php?hapus_keranjang=<?= $data['id_penjualan'] ?>" class="btn btn-danger btn-sm me-1" onclick="return confirm('Apakah Anda ingin menghapus \'<?= $data['nama_barang']; ?>\' dari keranjang?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>

                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    var jumlahPembelian = document.getElementById('jumlah_pembelian_<?= $data['id_barang'] ?>');
                                                    var subTotal = document.getElementById('subTotal_<?= $data['id_barang'] ?>');
                                                    var hargaPerUnit = <?= $data['harga']; ?>;
                                                    var salinanStokTersedia = document.getElementById('salinanStokTersedia_<?= $data['id_barang'] ?>');
                                                    var sisaStokTersedia = document.getElementById('sisaStokTersedia_<?= $data['id_barang'] ?>');
                                                    var stokTersedia = parseInt(document.getElementById('stokTersedia_<?= $data['id_barang'] ?>').textContent) || 0;

                                                    salinanStokTersedia.textContent = stokTersedia;

                                                    function updateSubTotal_<?= $data['id_barang'] ?>() {
                                                        var jumlah = parseInt(jumlahPembelian.value) || 0;

                                                        if (jumlah <= 0) {
                                                            jumlahPembelian.classList.remove('is-valid');
                                                            jumlahPembelian.classList.add('is-invalid');
                                                        } else {
                                                            jumlahPembelian.classList.remove('is-invalid');
                                                            jumlahPembelian.classList.add('is-valid');
                                                        }

                                                        var total = Math.max(jumlah, 0) * hargaPerUnit;
                                                        subTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');

                                                        // Hitung sisa stok tersedia
                                                        var sisaStok = stokTersedia - jumlah;
                                                        sisaStokTersedia.textContent = sisaStok >= 0 ? sisaStok : 0;

                                                        // Hapus kelas sebelumnya
                                                        sisaStokTersedia.classList.remove('text-warning', 'text-danger');
                                                        jumlahPembelian.classList.remove('is-invalid');

                                                        // Tambahkan kelas berdasarkan nilai sisa stok
                                                        if (sisaStok === 0) {
                                                            sisaStokTersedia.classList.add('text-warning');
                                                        } else if (sisaStok < 0) {
                                                            sisaStokTersedia.classList.add('text-danger');
                                                            jumlahPembelian.classList.add('is-invalid');
                                                        }

                                                        updateTotalBayar();
                                                    }

                                                    jumlahPembelian.addEventListener('input', updateSubTotal_<?= $data['id_barang'] ?>);

                                                    subTotal.textContent = 'Rp 0';
                                                    sisaStokTersedia.textContent = stokTersedia;
                                                });
                                            </script>
                                        <?php endwhile ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row g-4 align-items-center">
                                <div class="col-md-10">
                                    <div class="row justify-content-start">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex gap-2">
                                                        <h4 class="mb-0">Total Bayar:</h4>
                                                        <h4 id="totalBayar" class="mb-0">Rp 0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button id="prosesButton" class="btn btn-lg btn-primary w-100 fw-semibold" type="submit" name="tambah_penjualan" disabled>
                                        <i class="fas fa-arrows-rotate me-1"></i> PROSES
                                    </button>
                                </div>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Fungsi untuk memeriksa validitas semua input dengan id 'jumlah_pembelian_<id_barang>'
                                function checkInputsValidity() {
                                    var allValid = true;
                                    var inputs = document.querySelectorAll('[id^="jumlah_pembelian_"]');

                                    inputs.forEach(function(input) {
                                        // Cek apakah ada input yang memiliki kelas 'is-invalid' atau tidak memiliki kelas 'is-valid'
                                        if (input.classList.contains('is-invalid') || !input.classList.contains('is-valid')) {
                                            allValid = false;
                                        }
                                    });

                                    // Mengaktifkan atau menonaktifkan tombol berdasarkan status 'allValid'
                                    document.getElementById('prosesButton').disabled = !allValid;
                                }

                                // Menambahkan event listener untuk setiap input dengan id yang dimulai dengan 'jumlah_pembelian_'
                                document.querySelectorAll('[id^="jumlah_pembelian_"]').forEach(function(input) {
                                    input.addEventListener('input', checkInputsValidity);
                                });

                                // Memeriksa validitas input saat pertama kali halaman dimuat
                                checkInputsValidity();
                            });

                            function updateTotalBayar() {
                                var totalBayar = 0;

                                <?php
                                $queryPenjualan = mysqli_query($koneksi, $sqlPenjualan);
                                while ($data = mysqli_fetch_array($queryPenjualan)) :
                                ?>
                                    var subTotalValue = document.getElementById('subTotal_<?= $data['id_barang'] ?>').textContent.replace(/[^\d]/g, '');
                                    totalBayar += parseInt(subTotalValue) || 0;
                                <?php endwhile ?>

                                document.getElementById('totalBayar').textContent = 'Rp ' + totalBayar.toLocaleString('id-ID');
                            }
                        </script>
                    <?php else: ?>
                        <div class="alert alert-warning text-center my-4" role="alert">
                            <i class="fas fa-triangle-exclamation me-1"></i> Belum ada barang di keranjang!
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>