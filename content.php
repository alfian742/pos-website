<?php
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    if (file_exists("$page.php")) {
        include "$page.php";
    } else {
        echo "<script>window.location.href = '404.php';</script>";
    }
} else {
    include "beranda.php";
}
