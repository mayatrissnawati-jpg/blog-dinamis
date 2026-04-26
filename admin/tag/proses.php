<?php
include "../../config/koneksi.php";

/* =======================
   TAMBAH TAG
======================= */
if (isset($_POST['simpan'])) {

    $tag = trim($_POST['tag']);

    if (empty($tag)) {
        header("Location: index.php?msg=Tag tidak boleh kosong");
        exit;
    }

    // cek duplikat
    $cek = mysqli_prepare($koneksi, "SELECT id_tag FROM tag WHERE nama_tag = ?");
    mysqli_stmt_bind_param($cek, "s", $tag);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if (mysqli_stmt_num_rows($cek) == 0) {

        $insert = mysqli_prepare($koneksi, "INSERT INTO tag(nama_tag) VALUES(?)");
        mysqli_stmt_bind_param($insert, "s", $tag);
        mysqli_stmt_execute($insert);

        header("Location: index.php?msg=Tag berhasil ditambahkan");

    } else {
        header("Location: index.php?msg=Tag sudah ada");
    }

    exit;
}


/* =======================
   UPDATE TAG
======================= */
if (isset($_POST['update'])) {

    $id  = intval($_POST['id']);
    $tag = trim($_POST['tag']);

    if (empty($tag)) {
        header("Location: index.php?msg=Tag tidak boleh kosong");
        exit;
    }

    // cek duplikat (kecuali dirinya sendiri)
    $cek = mysqli_prepare($koneksi, "
        SELECT id_tag FROM tag 
        WHERE nama_tag = ? AND id_tag != ?
    ");
    mysqli_stmt_bind_param($cek, "si", $tag, $id);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if (mysqli_stmt_num_rows($cek) == 0) {

        $update = mysqli_prepare($koneksi, "
            UPDATE tag SET nama_tag=? WHERE id_tag=?
        ");
        mysqli_stmt_bind_param($update, "si", $tag, $id);
        mysqli_stmt_execute($update);

        header("Location: index.php?msg=Tag berhasil diupdate");

    } else {
        header("Location: index.php?msg=Tag sudah ada");
    }

    exit;
}


/* =======================
   HAPUS TAG
======================= */
if (isset($_GET['hapus'])) {

    $id = intval($_GET['hapus']);

    $hapus = mysqli_prepare($koneksi, "DELETE FROM tag WHERE id_tag = ?");
    mysqli_stmt_bind_param($hapus, "i", $id);
    mysqli_stmt_execute($hapus);

    header("Location: index.php?msg=Tag berhasil dihapus");
    exit;
}


/* =======================
   DEFAULT
======================= */
header("Location: index.php");
exit;