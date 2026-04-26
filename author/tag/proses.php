<?php
include "../../config/koneksi.php";

if (isset($_POST['simpan'])) {

    $tag = trim($_POST['tag'] ?? '');

    // ================= VALIDASI =================
    if (empty($tag)) {
        header("Location: index.php?msg=Tag tidak boleh kosong");
        exit;
    }

    // ================= CEK DUPLIKAT =================
    $stmt = mysqli_prepare($koneksi, "
        SELECT id_tag FROM tag WHERE nama_tag = ?
    ");
    mysqli_stmt_bind_param($stmt, "s", $tag);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        header("Location: index.php?msg=Tag sudah ada!");
        exit;
    }

    // ================= INSERT =================
    $stmt = mysqli_prepare($koneksi, "
        INSERT INTO tag (nama_tag) VALUES (?)
    ");
    mysqli_stmt_bind_param($stmt, "s", $tag);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?msg=Tag berhasil ditambahkan");
    } else {
        header("Location: index.php?msg=Gagal menambahkan tag");
    }

    exit;
}

// fallback jika akses langsung
header("Location: index.php");
exit;