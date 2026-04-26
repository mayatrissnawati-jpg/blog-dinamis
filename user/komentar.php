<?php
include "../config/koneksi.php";
session_start();

// cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['kirim'])) {

    $id_user    = $_SESSION['id_user'];
    $id_artikel = intval($_POST['id_artikel']);
    $isi        = trim($_POST['komentar']);
    $nama       = $_SESSION['username']; // ambil dari session

    // validasi
    if (empty($isi)) {
        header("Location: detail.php?id=$id_artikel&msg=Komentar tidak boleh kosong");
        exit;
    }

    // insert
    $stmt = mysqli_prepare($koneksi, "
        INSERT INTO komentar (id_user, id_artikel, nama, komentar, tanggal)
        VALUES (?, ?, ?, ?, NOW())
    ");

    mysqli_stmt_bind_param($stmt, "iiss", $id_user, $id_artikel, $nama, $isi);
    mysqli_stmt_execute($stmt);

    header("Location: detail.php?id=$id_artikel&msg=Komentar berhasil");
    exit;
}

header("Location: index.php");
exit;