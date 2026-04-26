<?php
$host     = "localhost";
$user     = "root";
$password = "";
$database = "blog";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    error_log("Koneksi gagal: " . mysqli_connect_error());
    die("Terjadi kesalahan pada sistem.");
}

date_default_timezone_set("Asia/Jakarta");
?>