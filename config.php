<?php
// Mengatur zona waktu ke Asia/Makassar
date_default_timezone_set('Asia/Makassar');

// Konfigurasi database
$hostname   = "localhost";
$username   = "root";
$password   = "";
$dbname     = "sip";

$koneksi = mysqli_connect($hostname, $username, $password, $dbname);
