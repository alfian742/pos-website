<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}

if (isset($_GET['id_barang'])) {
    $id_barang = $_GET['id_barang'];

    $ambil_data = mysqli_query($koneksi, "SELECT * FROM stok_barang WHERE id_barang='$id_barang'");

    if (mysqli_num_rows($ambil_data) > 0) {
        $data = mysqli_fetch_array($ambil_data);
    } else {
        $_SESSION['warning'] = "Data tidak ditemukan!";
        echo "<script>window.location.href = 'media.php?page=stok-barang-data';</script>";
        exit();
    }
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Detail Stok Barang</h3>
        <a href="dashboard.php?page=stok-barang-data" class="btn btn-warning btn-sm"><i class="fas fa-arrow-left me-1"></i> Lihat Semua Stok Barang</a>
    </div>
    <div class="card rounded-4 rounded-4 border-0 shadow mb-4">
        <div class="card-body">
            <!-- Alers -->
            <?php include('alerts.php') ?>

            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Masuk</th>
                        <th>ID Stok Barang</th>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Jenis Barang</th>
                        <th>Tanggal Kedaluwarsa</th>
                        <th>Jumlah Masuk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;

                    $sql = "SELECT * FROM stok_barang 
                            INNER JOIN barang ON stok_barang.id_barang=barang.id_barang 
                            INNER JOIN jenis_barang ON barang.id_jenis_barang=jenis_barang.id_jenis_barang
                            WHERE stok_barang.id_barang='$id_barang' AND stok_barang.jumlah_masuk>0
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
                                <a href="dashboard.php?page=stok-barang-ubah&id_stok_barang=<?= $data['id_stok_barang'] ?>&id_barang=<?= $data['id_barang'] ?>" class="btn btn-success btn-sm"> <i class="fas fa-pencil me-1"></i> Ubah</a>
                                <a href="action.php?hapus_stok_barang=<?= $data['id_stok_barang'] ?>" onclick="return confirm('Apakah Anda yakin menghapus data dengan ID \'<?= $data['id_stok_barang']; ?>\'?')" class="btn btn-danger btn-sm"> <i class="fas fa-trash me-1"></i> Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
</div>