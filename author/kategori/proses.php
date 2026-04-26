<?php
include "../../config/koneksi.php";
include "../../config/auth.php";
cek_login('author');

if (isset($_POST['simpan'])) {

    $kategori = trim($_POST['kategori']);

    // validasi kosong
    if (empty($kategori)) {
        header("Location: tambah.php?msg=Kategori tidak boleh kosong");
        exit;
    }

    // insert
    $stmt = mysqli_prepare($koneksi, "
        INSERT INTO kategori (nama_kategori)
        VALUES (?)
    ");

    mysqli_stmt_bind_param($stmt, "s", $kategori);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        header("Location: index.php?msg=Kategori berhasil ditambahkan");
    } else {
        header("Location: tambah.php?msg=Gagal menambahkan kategori");
    }

    exit;
}

// fallback kalau akses langsung
header("Location: index.php");
exit;