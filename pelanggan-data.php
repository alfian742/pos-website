<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin' && $_SESSION['level'] != 'Kasir')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Data Pelanggan</h3>
        <a href="dashboard.php?page=pelanggan-tambah" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i> Tambah</a>
    </div>
    <div class="card rounded-4 border-0 shadow mb-4">
        <div class="card-body">
            <!-- Alers -->
            <?php include('alerts.php') ?>

            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>L/P</th>
                        <th>Nomor HP</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $tampil_data = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC");
                    while ($data = mysqli_fetch_array($tampil_data)) :
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['nama_pelanggan'] ?></td>
                            <td><?= $data['jenis_kelamin'] ?></td>
                            <td><?= $data['nomor_hp'] ?></td>
                            <td><?= $data['alamat']; ?></td>
                            <td>
                                <a href="dashboard.php?page=pelanggan-ubah&id_pelanggan=<?= $data['id_pelanggan'] ?>" class="btn btn-success btn-sm"> <i class="fas fa-pencil me-1"></i> Ubah</a>
                                <?php if ($_SESSION['level'] == 'Admin'): ?>
                                    <a href="action.php?hapus_pelanggan=<?= $data['id_pelanggan'] ?>" onclick="return confirm('Apakah Anda yakin menghapus \'<?= $data['nama_pelanggan']; ?>\' dari data pelangggan?')" class="btn btn-danger btn-sm"> <i class="fas fa-trash me-1"></i> Hapus</a>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
</div>