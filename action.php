<?php
// Koneksi Database
include('config.php');

// Auth
if (isset($_GET['login'])) {
    session_start();

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $errors = [];
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (empty($username)) {
            $errors['username'] = 'Nama pengguna wajib diisi!';
        }

        if (empty($password)) {
            $errors['password'] = 'Kata sandi wajib diisi!';
        }

        if (empty($errors)) {
            // Cek username
            $sql = "SELECT * FROM user WHERE username='$username'";
            $query = mysqli_query($koneksi, $sql);
            $result = mysqli_fetch_array($query);

            if ($result) {
                // Verifikasi password
                if (password_verify($password, $result['password'])) {
                    $_SESSION['id_user'] = $result['id_user'];
                    $_SESSION['nama_user'] = $result['nama_user'];
                    $_SESSION['username'] = $result['username'];
                    $_SESSION['level'] = $result['level'];
                    $_SESSION['success'] = "Selamat Datang " . $_SESSION['nama_user'] . ' [' . $_SESSION['level'] . ']';

                    echo "<script>window.location.href = 'dashboard.php';</script>";
                    exit();
                } else {
                    $_SESSION['error'] = "Nama Pengguna atau Kata Sandi salah!";
                }
            } else {
                $_SESSION['error'] = "Nama Pengguna atau Kata Sandi salah!";
            }
        } else {
            $_SESSION['errors'] = $errors;
        }
    }

    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}

if (isset($_GET['logout'])) {
    session_start();
    session_destroy();
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}
// Akhir Auth

// Data Jenis Barang
if (isset($_POST['tambah_jenis_barang'])) {
    session_start();

    $errors = [];
    $id_jenis_barang = isset($_POST['id_jenis_barang']) ? $_POST['id_jenis_barang'] : '';
    $jenis_barang = isset($_POST['jenis_barang']) ? $_POST['jenis_barang'] : '';

    if (empty($id_jenis_barang)) {
        $errors['id_jenis_barang'] = 'ID jenis barang wajib diisi!';
    } elseif (strlen($id_jenis_barang) != 7) {
        $errors['id_jenis_barang'] = 'ID jenis barang harus 7 karakter!';
    } else {
        $cek_id_jenis_barang = mysqli_query($koneksi, "SELECT id_jenis_barang FROM jenis_barang WHERE id_jenis_barang='$id_jenis_barang'");
        if (mysqli_num_rows($cek_id_jenis_barang)) {
            $errors['id_jenis_barang'] = 'ID jenis barang sudah ada!';
        }
    }

    if (empty($jenis_barang)) {
        $errors['jenis_barang'] = 'Jenis barang wajib diisi!';
    } else {
        $cek_jenis_barang = mysqli_query($koneksi, "SELECT jenis_barang FROM jenis_barang WHERE jenis_barang='$jenis_barang'");
        if (mysqli_num_rows($cek_jenis_barang)) {
            $errors['jenis_barang'] = 'Jenis barang sudah ada!';
        }
    }

    if (empty($errors)) {
        $sql = "INSERT INTO jenis_barang VALUES('$id_jenis_barang', '$jenis_barang')";
        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            $_SESSION['success'] = "Data berhasil disimpan!";
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['error'] = "Data gagal disimpan!";
        }
        echo "<script>window.location.href = 'dashboard.php?page=jenis-barang-data';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        echo "<script>window.location.href = 'dashboard.php?page=jenis-barang-tambah';</script>";
        exit();
    }
}

if (isset($_GET['ubah_jenis_barang'])) {
    session_start();

    $errors = [];
    $id_jenis_barang = $_GET['ubah_jenis_barang']; // Pengambilan ID melalui url action pada form
    $jenis_barang = $_POST['jenis_barang'];

    $ambil_jenis_barang = mysqli_query($koneksi, "SELECT jenis_barang FROM jenis_barang WHERE id_jenis_barang='$id_jenis_barang'");
    $jenis_barang_lama = mysqli_fetch_array($ambil_jenis_barang)['jenis_barang'];
    if (empty($jenis_barang)) {
        $errors['jenis_barang'] = 'Jenis barang wajib diisi!';
    } else {
        if ($jenis_barang !== $jenis_barang_lama) {
            $cek_jenis_barang = mysqli_query($koneksi, "SELECT jenis_barang FROM jenis_barang WHERE jenis_barang='$jenis_barang'");
            if (mysqli_num_rows($cek_jenis_barang)) {
                $errors['jenis_barang'] = 'Jenis barang sudah ada!';
            }
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE jenis_barang SET jenis_barang='$jenis_barang' WHERE id_jenis_barang='$id_jenis_barang'";

        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            $_SESSION['success'] = "Data berhasil disimpan!";
        } else {
            $_SESSION['error'] = "Data gagal disimpan!";
        }
        echo "<script>window.location.href = 'dashboard.php?page=jenis-barang-data';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        echo "<script>window.location.href = 'dashboard.php?page=jenis-barang-ubah&id_jenis_barang=$id_jenis_barang';</script>";
        exit();
    }
}

if (isset($_GET['hapus_jenis_barang'])) {
    session_start();

    $id_jenis_barang = $_GET['hapus_jenis_barang'];

    $query = mysqli_query($koneksi, "DELETE FROM jenis_barang WHERE id_jenis_barang='$id_jenis_barang'");

    if ($query) {
        $_SESSION['success'] = "Data berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Data gagal dihapus!";
    }
    echo "<script>window.location.href = 'dashboard.php?page=jenis-barang-data';</script>";
    exit();
}
// Akhir Jenis Barang

// Barang
if (isset($_POST['tambah_barang'])) {
    session_start();

    $errors = [];
    $id_barang = isset($_POST['id_barang']) ? $_POST['id_barang'] : '';
    $id_jenis_barang = isset($_POST['id_jenis_barang']) ? $_POST['id_jenis_barang'] : '';
    $nama_barang = isset($_POST['nama_barang']) ? $_POST['nama_barang'] : '';
    $unit = isset($_POST['unit']) ? $_POST['unit'] : '';
    $harga = isset($_POST['harga']) ? $_POST['harga'] : '';
    $kedaluwarsa = isset($_POST['kedaluwarsa']) ? $_POST['kedaluwarsa'] : '';

    $cek_id_barang = mysqli_query($koneksi, "SELECT id_barang FROM barang WHERE id_barang='$id_barang'");
    if (mysqli_num_rows($cek_id_barang)) {
        $errors['id_barang'] = 'ID barang sudah ada!';
    } elseif (empty($id_barang)) {
        $errors['id_barang'] = 'ID barang wajib diisi!';
    } elseif (strlen($id_barang) != 7) {
        $errors['id_barang'] = 'ID barang harus 7 karakter!';
    }

    if (empty($id_jenis_barang)) {
        $errors['id_jenis_barang'] = 'Jenis barang wajib dipilih!';
    }

    if (empty($nama_barang)) {
        $errors['nama_barang'] = 'Nama barang wajib diisi!';
    }

    if (empty($unit)) {
        $errors['unit'] = 'unit wajib diisi!';
    }

    if (empty($harga)) {
        $errors['harga'] = 'Harga wajib diisi!';
    } elseif (!is_numeric($harga)) {
        $errors['harga'] = 'Harga harus berupa angka!';
    }

    // Set jadi '1970-01-01' jika tidak diisi
    if (empty($kedaluwarsa)) {
        $kedaluwarsa = '1970-01-01';
    }

    if (empty($errors)) {
        $sql = "INSERT INTO barang VALUES('$id_barang', '$id_jenis_barang', '$nama_barang', '$unit', '$harga', '$kedaluwarsa')";
        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            // Jika kedaluwarsa '1970-01-01', set menjadi NULL
            if ($kedaluwarsa === '1970-01-01') {
                $querySetNull = mysqli_query($koneksi, "UPDATE barang SET kedaluwarsa=NULL WHERE id_barang='$id_barang'");
            }

            $_SESSION['success'] = "Data berhasil disimpan!";
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['error'] = "Data gagal disimpan!" . mysqli_error($koneksi);
        }
        echo "<script>window.location.href = 'dashboard.php?page=barang-data';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        echo "<script>window.location.href = 'dashboard.php?page=barang-tambah';</script>";
        exit();
    }
}

if (isset($_GET['ubah_barang'])) {
    session_start();

    $errors = [];
    $id_barang = $_GET['ubah_barang']; // Pengambilan ID melalui url action pada form
    $id_jenis_barang = $_POST['id_jenis_barang'];
    $nama_barang = $_POST['nama_barang'];
    $unit = $_POST['unit'];
    $harga = $_POST['harga'];
    $kedaluwarsa = $_POST['kedaluwarsa'];

    if (empty($id_jenis_barang)) {
        $errors['id_jenis_barang'] = 'Jenis barang wajib dipilih!';
    }

    if (empty($nama_barang)) {
        $errors['nama_barang'] = 'Nama barang wajib diisi!';
    }

    if (empty($unit)) {
        $errors['unit'] = 'Unit wajib diisi!';
    }

    if (empty($harga)) {
        $errors['harga'] = 'Harga wajib diisi!';
    } elseif (!is_numeric($harga)) {
        $errors['harga'] = 'Harga harus berupa angka!';
    }

    // Set jadi '1970-01-01' jika dikosongkan
    if (empty($kedaluwarsa)) {
        $kedaluwarsa = '1970-01-01';
    }

    if (empty($errors)) {
        $sql = "UPDATE barang SET 
                id_jenis_barang='$id_jenis_barang',
                nama_barang='$nama_barang', 
                unit='$unit',
                harga='$harga',
                kedaluwarsa='$kedaluwarsa'
                WHERE id_barang='$id_barang'";

        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            // Jika kedaluwarsa '1970-01-01', set menjadi NULL
            if ($kedaluwarsa === '1970-01-01') {
                $querySetNull = mysqli_query($koneksi, "UPDATE barang SET kedaluwarsa=NULL WHERE id_barang='$id_barang'");
            }

            $_SESSION['success'] = "Data berhasil disimpan!";
        } else {
            $_SESSION['error'] = "Data gagal disimpan!";
        }
        echo "<script>window.location.href = 'dashboard.php?page=barang-data';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        echo "<script>window.location.href = 'dashboard.php?page=barang-ubah&id_barang=$id_barang';</script>";
        exit();
    }
}

if (isset($_GET['hapus_barang'])) {
    session_start();

    $id_barang = $_GET['hapus_barang'];

    $query = mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang='$id_barang'");

    if ($query) {
        $_SESSION['success'] = "Data berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Data gagal dihapus!";
    }
    echo "<script>window.location.href = 'dashboard.php?page=barang-data';</script>";
    exit();
}
// Akhir Barang

// Stok Barang
if (isset($_POST['tambah_stok_barang'])) {
    session_start();

    $errors = [];
    $id_barang = isset($_POST['id_barang']) ? $_POST['id_barang'] : '';
    $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
    $waktu = isset($_POST['waktu']) ? $_POST['waktu'] : '';
    $jumlah_masuk = isset($_POST['jumlah_masuk']) ? $_POST['jumlah_masuk'] : '';

    if (empty($id_barang)) {
        $errors['id_barang'] = 'Nama Barang wajib dipilih!';
    }

    if (empty($tanggal)) {
        $errors['tanggal'] = 'Tanggal barang masuk wajib diisi!';
    }

    if (empty($waktu)) {
        $errors['waktu'] = 'Waktu barang masuk wajib diisi!';
    }

    if (empty($jumlah_masuk)) {
        $errors['jumlah_masuk'] = 'Jumlah barang masuk wajib diisi!';
    } elseif (!is_numeric($jumlah_masuk)) {
        $errors['jumlah_masuk'] = 'Jumlah barang masuk harus berupa angka!';
    }

    if (empty($errors)) {
        // Generate ID Stok Barang
        $id_stok_barang = 'st' . uniqid();

        // Format tanggal dan waktu
        $format_tanggal = date('Y-m-d', strtotime($tanggal));
        $format_waktu = date('H:i', strtotime($waktu));
        $tanggal_masuk = $format_tanggal . ' ' . $format_waktu;

        $sql = "INSERT INTO stok_barang VALUES('$id_stok_barang', '$id_barang', '$tanggal_masuk', '$jumlah_masuk', 0)";
        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            $_SESSION['success'] = "Data berhasil disimpan!";
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['error'] = "Data gagal disimpan!";
        }
        echo "<script>window.location.href = 'dashboard.php?page=stok-barang-data';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        echo "<script>window.location.href = 'dashboard.php?page=stok-barang-tambah';</script>";
        exit();
    }
}

if (isset($_GET['ubah_stok_barang'])) {
    session_start();

    $errors = [];
    $id_stok_barang = $_GET['ubah_stok_barang']; // Pengambilan ID melalui url action pada form
    $get_id_barang = $_POST['get_id_barang']; // Diperoleh dari form untuk mempermudah redirect halaman
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $jumlah_masuk = $_POST['jumlah_masuk'];

    if (empty($tanggal)) {
        $errors['tanggal'] = 'Tanggal barang masuk wajib diisi!';
    }

    if (empty($waktu)) {
        $errors['waktu'] = 'Waktu barang masuk wajib diisi!';
    }

    if (empty($jumlah_masuk)) {
        $errors['jumlah_masuk'] = 'Jumlah barang masuk wajib diisi!';
    } elseif (!is_numeric($jumlah_masuk)) {
        $errors['jumlah_masuk'] = 'Jumlah barang masuk harus berupa angka!';
    }

    if (empty($errors)) {
        // Format tanggal dan waktu
        $format_tanggal = date('Y-m-d', strtotime($tanggal));
        $format_waktu = date('H:i', strtotime($waktu));
        $tanggal_masuk = $format_tanggal . ' ' . $format_waktu;

        $sql = "UPDATE stok_barang SET
                tanggal_masuk='$tanggal_masuk', 
                jumlah_masuk='$jumlah_masuk'
                WHERE id_stok_barang='$id_stok_barang'";
        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            $_SESSION['success'] = "Data berhasil disimpan!";
        } else {
            $_SESSION['error'] = "Data gagal disimpan!";
        }
        echo "<script>window.location.href = 'dashboard.php?page=stok-barang-detail&id_barang=$get_id_barang';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        echo "<script>window.location.href = 'dashboard.php?page=ubah_stok_barang&id_stok_barang=$id_stok_barang&id_barang=$get_id_barang';</script>";
        exit();
    }
}

if (isset($_GET['hapus_stok_barang'])) {
    session_start();

    $id_stok_barang = $_GET['hapus_stok_barang'];

    // Ambil id_barang terkait dengan id_stok_barang
    $cek_stok_barang = mysqli_query($koneksi, "SELECT id_barang FROM stok_barang WHERE id_stok_barang='$id_stok_barang'");
    $stok_barang = mysqli_fetch_array($cek_stok_barang);

    if ($stok_barang) {
        $id_barang = $stok_barang['id_barang'];

        // Hapus stok barang
        $query = mysqli_query($koneksi, "DELETE FROM stok_barang WHERE id_stok_barang='$id_stok_barang'");

        if ($query) {
            $_SESSION['success'] = "Data berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Data gagal dihapus!";
        }

        // Cek sisa stok untuk id_barang setelah penghapusan
        $cek_stok_sisa = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM stok_barang WHERE id_barang='$id_barang'");
        $stok_sisa = mysqli_fetch_array($cek_stok_sisa)['count'];

        if ($stok_sisa > 0) {
            // Jika masih ada stok
            echo "<script>window.location.href = 'dashboard.php?page=stok-barang-detail&id_barang=$id_barang';</script>";
        } else {
            // Jika tidak ada stok lagi
            echo "<script>window.location.href = 'dashboard.php?page=stok-barang-data';</script>";
        }
        exit();
    }
}
// Akhir Stok Barang

// Penjualan
if (isset($_GET['tambah_keranjang'])) {
    session_start();

    $id_barang = $_GET['tambah_keranjang']; // Pengambilan ID melalui url

    $sql = "INSERT INTO penjualan VALUES(NULL, NULL, NULL, NULL, '$id_barang', NULL, NULL, 'Belum Terjual')";
    $query = mysqli_query($koneksi, $sql);

    if ($query) {
        $_SESSION['success'] = "Barang berhasil ditambah ke keranjang!";
    } else {
        $_SESSION['error'] = "Barang gagal ditambah ke keranjang!";
    }
    echo "<script>window.location.href = 'dashboard.php?page=penjualan-transaksi';</script>";
    exit();
}

if (isset($_GET['hapus_keranjang'])) {
    session_start();

    $id_penjualan = $_GET['hapus_keranjang'];

    $query = mysqli_query($koneksi, "DELETE FROM penjualan WHERE id_penjualan='$id_penjualan'");

    if ($query) {
        $_SESSION['success_cart'] = "Barang berhasil dihapus dari keranjang!";
    } else {
        $_SESSION['error_cart'] = "Barang gagal dihapus dari keranjang!";
    }
    echo "<script>window.location.href = 'dashboard.php?page=penjualan-transaksi';</script>";
    exit();
}

if (isset($_POST['tambah_penjualan'])) {
    session_start();

    $id_struk = $_POST['id_struk'];
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_user = $_POST['id_user'];
    $tanggal_pembelian = date('Y-m-d H:i:s', strtotime($_POST['tanggal_pembelian']));
    $jumlah_pembelian = $_POST['jumlah_pembelian'];
    $transaksi = '';

    foreach ($jumlah_pembelian as $id_barang => $jumlah) {
        $jumlah = intval($jumlah); // Validasi jumlah pembelian
        if ($jumlah > 0) { // Update hanya jika jumlah lebih dari 0
            $sql = "UPDATE penjualan SET
                    id_struk='$id_struk',
                    id_pelanggan='$id_pelanggan',
                    id_user='$id_user',
                    tanggal_pembelian='$tanggal_pembelian',
                    jumlah_pembelian='$jumlah',
                    status_pembelian='Terjual'
                    WHERE id_barang='$id_barang' AND status_pembelian='Belum Terjual'";
            $query = mysqli_query($koneksi, $sql);

            if ($query) {
                $transaksi = 'Berhasil';

                $tanggal_masuk = date('Y-m-d H:i:s');

                $id_stok_barang = 'ST' . strtoupper(uniqid());
                $tambah_stok_barang = mysqli_query($koneksi, "INSERT INTO stok_barang VALUES('$id_stok_barang', '$id_barang', '$tanggal_masuk', 0, '$jumlah')");
            } else {
                $transaksi = 'Gagal';
            }
        }
    }

    if ($transaksi == 'Berhasil') {
        $_SESSION['success'] = "Transaksi berhasil disimpan!";
        echo "<script>
                window.onload = function() {
                    var iframe = document.createElement('iframe');
                    iframe.style.height = '0';
                    iframe.style.width = '0';
                    iframe.style.border = '0';
                    iframe.style.position = 'absolute';
                
                    iframe.src = 'penjualan-cetak-nota.php?id_struk=" . $id_struk . "';
                    document.body.appendChild(iframe);
                
                    iframe.onload = function () {
                        iframe.contentWindow.print();
                        setTimeout(function () {
                            document.body.removeChild(iframe);
                            window.location.href = 'dashboard.php?page=penjualan-transaksi';
                        }, 1000);
                    };
                };
            </script>";
        exit();
    } else {
        $_SESSION['error'] = "Transaksi gagal disimpan!";
        echo "<script>window.location.href = 'dashboard.php?page=penjualan-transaksi';</script>";
        exit();
    }
}
// Akhir Penjualan

// User
if (isset($_POST['tambah_user'])) {
    session_start();

    $errors = [];
    $nama_user = isset($_POST['nama_user']) ? $_POST['nama_user'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $level = isset($_POST['level']) ? $_POST['level'] : '';

    if (empty($nama_user)) {
        $errors['nama_user'] = 'Nama lengkap wajib diisi!';
    }

    if (empty($username)) {
        $errors['username'] = 'Nama pengguna wajib diisi!';
    } else {
        $cek_username = mysqli_query($koneksi, "SELECT username FROM user WHERE username='$username'");
        if (mysqli_num_rows($cek_username)) {
            $errors['username'] = 'Nama pengguna sudah ada!';
        }
    }

    if (empty($level)) {
        $errors['level'] = 'Level wajib dipilih!';
    }

    if (empty($password)) {
        $errors['password'] = 'Kata sandi wajib diisi!';
    }

    if (empty($errors)) {
        $id_user = 'us' . uniqid();
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user VALUES('$id_user', '$nama_user', '$username', '$password_hash', '$level')";
        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            $_SESSION['success'] = "Data berhasil disimpan!";
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['error'] = "Data gagal disimpan!";
        }
        echo "<script>window.location.href = 'dashboard.php?page=user-data';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        echo "<script>window.location.href = 'dashboard.php?page=user-tambah';</script>";
        exit();
    }
}

if (isset($_GET['ubah_user'])) {
    session_start();

    $errors = [];
    $id_user = $_GET['ubah_user']; // Pengambilan ID melalui url action pada form
    $nama_user = $_POST['nama_user'];
    $username = $_POST['username'];
    $level = $_POST['level'];

    if (empty($nama_user)) {
        $errors['nama_user'] = 'Nama lengkap wajib diisi!';
    }

    $ambil_username = mysqli_query($koneksi, "SELECT username FROM user WHERE id_user='$id_user'");
    $username_lama = mysqli_fetch_array($ambil_username)['username'];
    if (empty($username)) {
        $errors['username'] = 'Nama pengguna wajib diisi!';
    } else {
        // Jika username baru berbeda dari username lama, cek ketersediaan username baru
        if ($username !== $username_lama) {
            $cek_username = mysqli_query($koneksi, "SELECT username FROM user WHERE username='$username'");
            if (mysqli_num_rows($cek_username)) {
                $errors['username'] = 'Nama pengguna sudah ada!';
            }
        }
    }

    if (empty($level)) {
        $errors['level'] = 'Level wajib dipilih!';
    }

    if (empty($errors)) {
        $sql = "UPDATE user SET 
                nama_user='$nama_user', 
                username='$username',
                level='$level'
                WHERE id_user='$id_user'";

        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            $_SESSION['success'] = "Data berhasil disimpan!";

            // Perbarui Session jika data yang diubah sesuai dengan data user yang login
            if ($id_user === $_SESSION['id_user']) {
                $_SESSION['nama_user'] = $nama_user;
                $_SESSION['username'] = $username;
            }
        } else {
            $_SESSION['error'] = "Data gagal disimpan!";
        }
        echo "<script>window.location.href = 'dashboard.php?page=user-data';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        echo "<script>window.location.href = 'dashboard.php?page=user-ubah&id_user=$id_user';</script>";
        exit();
    }
}

if (isset($_GET['hapus_user'])) {
    session_start();

    $id_user = $_GET['hapus_user']; // Pengambilan ID melalui url action pada form

    $query = mysqli_query($koneksi, "DELETE FROM user WHERE id_user='$id_user'");

    if ($query) {
        $_SESSION['success'] = "Data berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Data gagal dihapus!";
    }
    echo "<script>window.location.href = 'dashboard.php?page=user-data';</script>";
    exit();
}

if (isset($_GET['reset_user'])) {
    session_start();

    $id_user = $_GET['reset_user']; // Pengambilan ID melalui url action pada form

    $password = "12345678";
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = mysqli_query($koneksi, "UPDATE user SET password='$password_hash' WHERE id_user='$id_user'");

    if ($query) {
        $_SESSION['success'] = "Kata sandi berhasil direset menjadi '$password'!";
    } else {
        $_SESSION['error'] = "Kata sandi gagal direset!";
    }
    echo "<script>window.location.href = 'dashboard.php?page=user-data';</script>";
    exit();
}

if (isset($_GET['profil'])) {
    session_start();

    $errors = [];
    $id_user = $_GET['profil']; // Pengambilan ID melalui url action pada form
    $nama_user = $_POST['nama_user'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($nama_user)) {
        $errors['nama_user'] = 'Nama lengkap wajib diisi!';
    }

    $ambil_username = mysqli_query($koneksi, "SELECT username FROM user WHERE id_user='$id_user'");
    $username_lama = mysqli_fetch_array($ambil_username)['username'];
    if (empty($username)) {
        $errors['username'] = 'Nama pengguna wajib diisi!';
    } else {
        // Jika username baru berbeda dari username lama, cek ketersediaan username baru
        if ($username !== $username_lama) {
            $cek_username = mysqli_query($koneksi, "SELECT username FROM user WHERE username='$username'");
            if (mysqli_num_rows($cek_username)) {
                $errors['username'] = 'Nama pengguna sudah ada!';
            }
        }
    }

    if (empty($errors)) {
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE user SET 
                    nama_user='$nama_user', 
                    username='$username',
                    password='$password_hash'
                    WHERE id_user='$id_user'";
        } else {
            $sql = "UPDATE user SET 
                    nama_user='$nama_user', 
                    username='$username'
                    WHERE id_user='$id_user'";
        }

        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            $_SESSION['success'] = "Profil berhasil diperbarui!";

            $_SESSION['nama_user'] = $nama_user;
            $_SESSION['username'] = $username;
        } else {
            $_SESSION['error'] = "Profil gagal diperbarui!";
        }
        echo "<script>window.location.href = 'dashboard.php';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        echo "<script>window.location.href = 'dashboard.php?page=user-profil&id_user=$id_user';</script>";
        exit();
    }
}
// Akhir User

// Pelanggan
if (isset($_POST['tambah_pelanggan'])) {
    session_start();

    $errors = [];
    $nama_pelanggan = isset($_POST['nama_pelanggan']) ? $_POST['nama_pelanggan'] : '';
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : '';
    $nomor_hp = isset($_POST['nomor_hp']) ? $_POST['nomor_hp'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';

    if (empty($nama_pelanggan)) {
        $errors['nama_pelanggan'] = 'Nama lengkap wajib diisi!';
    }

    if (empty($jenis_kelamin)) {
        $errors['jenis_kelamin'] = 'Jenis kelamin wajib dipilih!';
    }

    if (empty($nomor_hp)) {
        $errors['nomor_hp'] = 'Nomor HP wajib diisi!';
    }

    if (empty($alamat)) {
        $errors['alamat'] = 'Alamat wajib diisi!';
    }

    if (empty($errors)) {
        $id_pelanggan = 'cs' . uniqid();

        $sql = "INSERT INTO pelanggan VALUES('$id_pelanggan', '$nama_pelanggan', '$jenis_kelamin', '$nomor_hp', '$alamat')";
        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            $_SESSION['success'] = "Data berhasil disimpan!";
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['error'] = "Data gagal disimpan!";
        }
        echo "<script>window.location.href = 'dashboard.php?page=pelanggan-data';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        echo "<script>window.location.href = 'dashboard.php?page=pelanggan-tambah';</script>";
        exit();
    }
}

if (isset($_GET['ubah_pelanggan'])) {
    session_start();

    $errors = [];
    $id_pelanggan = $_GET['ubah_pelanggan']; // Pengambilan ID melalui url action pada form
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $nomor_hp = $_POST['nomor_hp'];
    $alamat = $_POST['alamat'];

    if (empty($nama_pelanggan)) {
        $errors['nama_pelanggan'] = 'Nama lengkap wajib diisi!';
    }

    if (empty($jenis_kelamin)) {
        $errors['jenis_kelamin'] = 'Jenis kelamin wajib dipilih!';
    }

    if (empty($nomor_hp)) {
        $errors['nomor_hp'] = 'Nomor HP wajib diisi!';
    }

    if (empty($alamat)) {
        $errors['alamat'] = 'Alamat wajib diisi!';
    }

    if (empty($errors)) {
        $sql = "UPDATE pelanggan SET 
                nama_pelanggan='$nama_pelanggan', 
                jenis_kelamin='$jenis_kelamin',
                nomor_hp='$nomor_hp',
                alamat='$alamat'
                WHERE id_pelanggan='$id_pelanggan'";

        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            $_SESSION['success'] = "Data berhasil disimpan!";
        } else {
            $_SESSION['error'] = "Data gagal disimpan!";
        }
        echo "<script>window.location.href = 'dashboard.php?page=pelanggan-data';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        echo "<script>window.location.href = 'dashboard.php?page=pelanggan-ubah&id_pelanggan=$id_pelanggan';</script>";
        exit();
    }
}

if (isset($_GET['hapus_pelanggan'])) {
    session_start();

    $id_pelanggan = $_GET['hapus_pelanggan'];

    $query = mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id_pelanggan='$id_pelanggan'");

    if ($query) {
        $_SESSION['success'] = "Data berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Data gagal dihapus!";
    }
    echo "<script>window.location.href = 'dashboard.php?page=pelanggan-data';</script>";
    exit();
}
// Akhir Pelanggan