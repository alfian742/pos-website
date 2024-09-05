<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin' && $_SESSION['level'] != 'Kasir')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Data Barang</h3>
        <?php if ($_SESSION['level'] == "Admin") : ?>
            <a href="dashboard.php?page=barang-tambah" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i> Tambah</a>
        <?php endif ?>
    </div>
    <div class="card rounded-4 border-0 rounded-4 shadow mb-4">
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
                        <?= ($_SESSION['level'] == "Admin") ? '<th>Aksi</th>' : '' ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $tampil_data = mysqli_query($koneksi, "SELECT * FROM barang INNER JOIN jenis_barang ON barang.id_jenis_barang=jenis_barang.id_jenis_barang ORDER BY nama_barang ASC");
                    while ($data = mysqli_fetch_array($tampil_data)) :
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
                            <?php if ($_SESSION['level'] == "Admin") : ?>
                                <td>
                                    <a href="dashboard.php?page=barang-ubah&id_barang=<?= $data['id_barang'] ?>" class="btn btn-success btn-sm me-1"> <i class="fas fa-pencil"></i> Ubah</a>
                                    <a href="action.php?hapus_barang=<?= $data['id_barang'] ?>" onclick="return confirm('Apakah Anda yakin menghapus data dengan ID \'<?= $data['id_barang']; ?>\'?')" class="btn btn-danger btn-sm me-1"> <i class="fas fa-trash"></i> Hapus</a>
                                </td>
                            <?php endif ?>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
</div>