<?php
include "../../config/koneksi.php";

/* TAMBAH */
if (isset($_POST['simpan'])) {
    mysqli_query($koneksi, "
        INSERT INTO kategori(nama_kategori)
        VALUES('$_POST[kategori]')
    ");
}

/* UPDATE */
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $kategori = $_POST['kategori'];

    mysqli_query($koneksi, "
        UPDATE kategori 
        SET nama_kategori='$kategori' 
        WHERE id_kategori='$id'
    ");
}

/* HAPUS */
if (isset($_GET['hapus'])) {
    mysqli_query($koneksi, "
        DELETE FROM kategori 
        WHERE id_kategori='$_GET[hapus]'
    ");
}

header("Location: index.php");
?>