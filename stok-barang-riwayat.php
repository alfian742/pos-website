<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Riwayat Barang Masuk</h3>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#printModal"><i class="fas fa-print me-1"></i> Cetak Laporan</button>
    </div>
    <div class="card rounded-4 border-0 shadow mb-4">
        <div class="card-body">
            <!-- Alerts -->
            <?php include('alerts.php') ?>

            <div class="mb-4">
                <h6 class="mb-0"><span class="text-danger">*</span> Catatan: Sub Total = Harga Perunit ✕ Jumlah Masuk.</h6>
            </div>

            <table id="datatablesSimple">
                <thead>
                    <tr>
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
                    $no = 1;

                    $sql = "SELECT * FROM stok_barang 
                            INNER JOIN barang ON stok_barang.id_barang=barang.id_barang 
                            INNER JOIN jenis_barang ON barang.id_jenis_barang=jenis_barang.id_jenis_barang
                            WHERE jumlah_masuk>0
                            ORDER BY tanggal_masuk DESC";
                    $query = mysqli_query($koneksi, $sql);
                    while ($data = mysqli_fetch_array($query)) :
                    ?>
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
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Print Modal -->
<div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="stok-barang-cetak-laporan.php" target="printFrame" class="modal-content rounded-4 border-0">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="printModalLabel">Cetak Laporan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="periode_awal">Periode Awal <span class="text-danger">*</span></label>
                            <input class="form-control" name="periode_awal" id="periode_awal" type="date" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="periode_akhir">Periode Akhir <span class="text-danger">*</span></label>
                            <input class="form-control" name="periode_akhir" id="periode_akhir" type="date" required />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success" onclick="printPage()"><i class="fas fa-print me-1"></i> Cetak</button>
            </div>
        </form>
    </div>
</div>

<!-- iframe untuk print laporan -->
<iframe id="printFrame" name="printFrame" style="display: none;"></iframe>