<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Stok Barang</h3>
        <a href="dashboard.php?page=stok-barang-tambah" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i> Tambah</a>
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
                        <th>Jumlah Masuk</th>
                        <th>Jumlah Terjual</th>
                        <th>Jumlah Saat Ini</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;

                    $sql = "SELECT 
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
                            ORDER BY MAX(stok_barang.tanggal_masuk) DESC";

                    $query = mysqli_query($koneksi, $sql);

                    while ($data = mysqli_fetch_array($query)) :
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
                            <td><?= $data['total_masuk'] ?></td>
                            <td><?= $data['total_terjual'] ?></td>
                            <td><?= $data['total_masuk'] - $data['total_terjual'] ?></td>
                            <td>
                                <a href="dashboard.php?page=stok-barang-detail&id_barang=<?= $data['id_barang'] ?>" class="btn btn-primary btn-sm"> <i class="fas fa-info-circle me-1"></i> Detail</a>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
</div>