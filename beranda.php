<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center gap-2 my-4">
        <h3 class="mb-0">Dashboard</h3>
        <div class="rounded-2 p-2" style="background-color: rgba(25, 135, 84, 0.2);">
            <h6 class="mb-0">Role: <?= $_SESSION['level']; ?></h6>
        </div>
    </div>

    <!-- Alers -->
    <?php include('alerts.php'); ?>

    <div class="row justify-content-center">
        <?php if ($_SESSION['level'] == 'Admin'): ?>
            <div class="col-xl-3 col-md-6">
                <div class="card rounded-4 border-0 text-bg-success shadow mb-4">
                    <div class="card-body fw-semibold">
                        Data Pengguna
                        <h1 class="text-center fs-custom">
                            <?php
                            $user = mysqli_query($koneksi, "SELECT id_user FROM user");
                            $jumlahUser = mysqli_num_rows($user);

                            echo $jumlahUser;
                            ?>
                        </h1>
                        <div class="d-flex align-items-center justify-content-between border-top border-white pt-3">
                            <a class="small text-white text-decoration-none stretched-link" href="dashboard.php?page=user-data">Selengkapnya</a>
                            <div class="small text-white"><i class="fas fa-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <div class="<?= ($_SESSION['level'] == 'Admin') ? 'col-xl-3' : 'col-xl-4'; ?> col-md-6">
            <div class="card rounded-4 border-0 text-bg-success shadow mb-4">
                <div class="card-body fw-semibold">
                    Data Pelanggan
                    <h1 class="text-center fs-custom">
                        <?php
                        $pelanggan = mysqli_query($koneksi, "SELECT id_pelanggan FROM pelanggan");
                        $jumlahPelanggan = mysqli_num_rows($pelanggan);

                        echo $jumlahPelanggan;
                        ?>
                    </h1>
                    <div class="d-flex align-items-center justify-content-between border-top border-white pt-3">
                        <a class="small text-white text-decoration-none stretched-link" href="dashboard.php?page=pelanggan-data">Selengkapnya</a>
                        <div class="small text-white"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="<?= ($_SESSION['level'] == 'Admin') ? 'col-xl-3' : 'col-xl-4'; ?> col-md-6">
            <div class="card rounded-4 border-0 text-bg-success shadow mb-4">
                <div class="card-body fw-semibold">
                    Data Barang
                    <h1 class="text-center fs-custom">
                        <?php
                        $barang = mysqli_query($koneksi, "SELECT id_barang FROM barang");
                        $jumlahBarang = mysqli_num_rows($barang);

                        echo $jumlahBarang;
                        ?>
                    </h1>
                    <div class="d-flex align-items-center justify-content-between border-top border-white pt-3">
                        <a class="small text-white text-decoration-none stretched-link" href="dashboard.php?page=barang-data">Selengkapnya</a>
                        <div class="small text-white"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="<?= ($_SESSION['level'] == 'Admin') ? 'col-xl-3' : 'col-xl-4'; ?> col-md-6">
            <div class="card rounded-4 border-0 text-bg-success shadow mb-4">
                <div class="card-body fw-semibold">
                    Riwayat Transaksi
                    <h1 class="text-center fs-custom">
                        <?php
                        $penjualan = mysqli_query($koneksi, "SELECT id_struk, COUNT(*) AS jumlah_penjualan FROM penjualan GROUP BY id_struk");
                        $jumlahPenjualan = mysqli_num_rows($penjualan);

                        echo $jumlahPenjualan;
                        ?>
                    </h1>
                    <div class="d-flex align-items-center justify-content-between border-top border-white pt-3">
                        <a class="small text-white text-decoration-none stretched-link" href="dashboard.php?page=penjualan-data">Selengkapnya</a>
                        <div class="small text-white"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card rounded-4 border-0 shadow mb-4">
                <div class="card-body">
                    <?php
                    $currentMonth = date('m'); // Bulan saat ini
                    $currentYear = date('Y'); // Tahun saat ini
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

                    // Inisialisasi array tanggal dan total transaksi
                    $dates = [];
                    $totals = [];

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $date = sprintf('%02d-%02d-%04d', $day, $currentMonth, $currentYear);
                        $dates[$date] = 0; // Set total transaksi awal ke 0
                    }

                    // Query SQL
                    $sqlPenjualanBulanan = "SELECT 
                                                DATE_FORMAT(penjualan.tanggal_pembelian, '%d-%m-%Y') AS tanggal_pembelian, 
                                                SUM(barang.harga * penjualan.jumlah_pembelian) AS total_transaksi
                                            FROM penjualan
                                            INNER JOIN barang ON penjualan.id_barang = barang.id_barang
                                            WHERE MONTH(penjualan.tanggal_pembelian) = $currentMonth
                                            AND YEAR(penjualan.tanggal_pembelian) = $currentYear
                                            GROUP BY DATE_FORMAT(penjualan.tanggal_pembelian, '%d-%m-%Y')";

                    // Eksekusi query
                    $queryPenjualanBulanan = mysqli_query($koneksi, $sqlPenjualanBulanan);

                    // Cek apakah query berhasil
                    if (!$queryPenjualanBulanan) {
                        die('Query Error: ' . mysqli_error($koneksi));
                    }

                    // Isi total transaksi pada array dates
                    while ($row = mysqli_fetch_assoc($queryPenjualanBulanan)) {
                        $dates[$row['tanggal_pembelian']] = $row['total_transaksi'];
                    }

                    // Pisahkan tanggal dan total untuk digunakan dalam chart
                    $chartDates = array_keys($dates);
                    $chartTotals = array_values($dates);
                    ?>

                    <h4 class="mb-5">
                        <i class="fas fa-chart-area me-1"></i>
                        Penjualan Bulan <?= date('F Y'); ?>
                    </h4>

                    <canvas id="myAreaChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart -->
<script src="assets/js/chart.min.js"></script>
<!-- Chart -->
<script src="assets/js/chart.min.js"></script>
<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Fungsi untuk memformat angka menjadi format Rupiah
    function formatRupiah(value) {
        return 'Rp ' + value.toLocaleString('id-ID');
    }

    // Fungsi untuk memformat tanggal menjadi format d F (misalnya 01 Januari)
    function formatTanggal(value) {
        const [day, month, year] = value.split('-');
        const date = new Date(`${year}-${month}-${day}`);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long'
        });
    }

    // Area Chart Example
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chartDates); ?>.map(formatTanggal), // Ubah format tanggal
            datasets: [{
                label: "Total Transaksi",
                lineTension: 0.3,
                backgroundColor: "rgba(25, 135, 84, 0.2)", // Background color dengan transparansi
                borderColor: "rgba(25, 135, 84, 1)", // Garis border dengan warna solid
                pointRadius: 5,
                pointBackgroundColor: "rgba(25, 135, 84, 1)", // Warna titik
                pointBorderColor: "rgba(255, 255, 255, 0.8)", // Border titik dengan warna putih transparan
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(25, 135, 84, 1)", // Warna titik saat hover
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: <?php echo json_encode($chartTotals); ?>,
            }],
        },
        options: {
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        min: 0,
                        max: Math.max(...<?php echo json_encode($chartTotals); ?>) + 10000, // Sesuaikan nilai maksimum
                        maxTicksLimit: 5,
                        callback: function(value) {
                            return formatRupiah(value); // Format yAxis menjadi Rupiah
                        }
                    },
                    gridLines: {
                        color: "rgba(0, 0, 0, .125)",
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem) {
                        return 'Total Transaksi: ' + formatRupiah(tooltipItem.yLabel); // Format tooltip menjadi Rupiah
                    }
                }
            }
        }
    });
</script>