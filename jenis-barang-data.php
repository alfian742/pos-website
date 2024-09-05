<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Jenis Barang</h3>
        <a href="dashboard.php?page=jenis-barang-tambah" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i> Tambah</a>
    </div>
    <div class="card rounded-4 border-0 shadow mb-4">
        <div class="card-body">
            <!-- Alers -->
            <?php include('alerts.php') ?>

            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Jenis Barang</th>
                        <th>Jenis Barang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $tampil_data = mysqli_query($koneksi, "SELECT * FROM jenis_barang");
                    while ($data = mysqli_fetch_array($tampil_data)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['id_jenis_barang'] ?></td>
                            <td><?= $data['jenis_barang'] ?></td>
                            <td>
                                <a href="dashboard.php?page=jenis-barang-ubah&id_jenis_barang=<?= $data['id_jenis_barang'] ?>" class="btn btn-success btn-sm"> <i class=" fa fa-pencil"></i> Ubah</a>
                                <a href="action.php?hapus_jenis_barang=<?= $data['id_jenis_barang'] ?>" onclick="return confirm('Apakah Anda yakin menghapus data dengan ID \'<?= $data['id_jenis_barang']; ?>\'?')" class="btn btn-danger btn-sm"> <i class=" fa fa-trash"></i> Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
</div>