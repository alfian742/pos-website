<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin' && $_SESSION['level'] != 'Kasir')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Riwayat Transaksi</h3>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#printModal"><i class="fas fa-print me-1"></i> Cetak Laporan</button>
    </div>
    <div class="card rounded-4 border-0 shadow mb-4">
        <div class="card-body">
            <!-- Alerts -->
            <?php include('alerts.php'); ?>

            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Transaksi</th>
                        <th>ID Struk</th>
                        <th>Nama Pelanggan</th>
                        <th>Total Transaksi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;

                    $sql = "SELECT 
                                penjualan.id_struk, 
                                penjualan.tanggal_pembelian, 
                                pelanggan.nama_pelanggan,
                                SUM(barang.harga * penjualan.jumlah_pembelian) AS total_transaksi
                            FROM penjualan
                            INNER JOIN pelanggan ON penjualan.id_pelanggan = pelanggan.id_pelanggan
                            INNER JOIN barang ON penjualan.id_barang = barang.id_barang
                            GROUP BY penjualan.id_struk, penjualan.tanggal_pembelian, pelanggan.nama_pelanggan
                            ORDER BY penjualan.tanggal_pembelian DESC";

                    $query = mysqli_query($koneksi, $sql);

                    while ($data = mysqli_fetch_array($query)) :
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($data['tanggal_pembelian'])); ?></td>
                            <td><?= $data['id_struk'] ?></td>
                            <td><?= $data['nama_pelanggan'] ?></td>
                            <td><?= 'Rp ' . number_format($data['total_transaksi'], 0, ',', '.') ?></td>
                            <td>
                                <a href="dashboard.php?page=penjualan-detail&id_struk=<?= $data['id_struk']; ?>" class="btn btn-sm btn-primary"><i class="fas fa-info-circle me-1"></i> Detail</a>
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
        <form method="post" action="penjualan-cetak-laporan.php" target="printFrame" class="modal-content rounded-4 border-0">
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