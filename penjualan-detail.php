<?php
// Cek level
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 'Admin' && $_SESSION['level'] != 'Kasir')) {
    echo "<script>window.location.href = '404.php';</script>";
    exit();
}

if (isset($_GET['id_struk'])) {
    $id_struk = $_GET['id_struk'];

    $ambil_data = mysqli_query($koneksi, "SELECT * FROM penjualan WHERE id_struk='$id_struk'");

    if (mysqli_num_rows($ambil_data) > 0) {
        $data = mysqli_fetch_array($ambil_data);
    } else {
        $_SESSION['warning'] = "Data tidak ditemukan!";
        echo "<script>window.location.href = 'dashboard.php?page=penjualan-data';</script>";
        exit();
    }
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Detail Transaksi</h3>
        <div class="d-flex gap-2">
            <a href="dashboard.php?page=penjualan-data" class="btn btn-warning btn-sm"><i class="fas fa-arrow-left me-1"></i> Lihat Semua Transaksi</a>
            <button type="button" onclick="printNota('penjualan-cetak-nota.php?id_struk=<?= $data['id_struk']; ?>');" class="btn btn-sm btn-success"><i class="fas fa-print me-1"></i> Cetak Nota</button>
        </div>
    </div>
    <div class="card rounded-4 border-0 shadow mb-4">
        <div class="card-body">
            <!-- Alerts -->
            <?php include('alerts.php') ?>

            <div class="d-flex justify-content-between gap-2 mb-4">
                <div class="table-responsive">
                    <table class="table table-borderless table-nowrap">
                        <tr>
                            <td style="width: 10rem;">ID Struk</td>
                            <td style="width: 1rem;">:</td>
                            <th><?= $data['id_struk']; ?></th>
                        </tr>
                        <tr>
                            <td>Tanggal Transaksi</td>
                            <td>:</td>
                            <th><?= date('d-m-Y H:i', strtotime($data['tanggal_pembelian'])); ?></th>
                        </tr>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless table-nowrap">
                        <tr>
                            <td style="width: 10rem;">Nama Pelanggan</td>
                            <td style="width: 1rem;">:</td>
                            <th>
                                <?php
                                $id_pelanggan = $data['id_pelanggan'];
                                $pelanggan = mysqli_query($koneksi, "SELECT nama_pelanggan FROM pelanggan WHERE id_pelanggan='$id_pelanggan'");
                                $data_pelanggan = mysqli_fetch_array($pelanggan);
                                echo $data_pelanggan['nama_pelanggan'];
                                ?>
                            </th>
                        </tr>
                        <tr>
                            <td>Dibuat oleh</td>
                            <td>:</td>
                            <th>
                                <?php
                                $id_user = $data['id_user'];
                                $user = mysqli_query($koneksi, "SELECT nama_user, level FROM user WHERE id_user='$id_user'");
                                $data_user = mysqli_fetch_array($user);
                                echo $data_user['nama_user'] . ' [' . $data_user['level'] . ']';
                                ?>
                            </th>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered table-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Unit</th>
                            <th>Harga Perunit</th>
                            <th>Jumlah</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;

                        $total = 0;

                        $sqlNota = "SELECT * FROM penjualan
                                    INNER JOIN barang ON penjualan.id_barang=barang.id_barang
                                    WHERE id_struk='$id_struk'
                                    ORDER BY nama_barang ASC";

                        $queryNota = mysqli_query($koneksi, $sqlNota);

                        while ($hasil = mysqli_fetch_array($queryNota)) :
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $hasil['id_barang']; ?></td>
                                <td><?= $hasil['nama_barang']; ?></td>
                                <td><?= $hasil['unit']; ?></td>
                                <td><?= 'Rp ' . number_format($hasil['harga'], 0, ',', '.') ?></td>
                                <td><?= $hasil['jumlah_pembelian']; ?></td>
                                <td>
                                    <?php
                                    $subTotal = $hasil['harga'] * $hasil['jumlah_pembelian'];

                                    echo 'Rp ' . number_format($subTotal, 0, ',', '.')
                                    ?>
                                </td>
                            </tr>

                            <?php $total += $subTotal; ?>
                        <?php endwhile ?>

                        <tr>
                            <th colspan="6" class="text-center">Total</th>
                            <th><?= 'Rp ' . number_format($total, 0, ',', '.') ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>