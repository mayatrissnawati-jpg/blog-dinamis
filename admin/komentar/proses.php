<?php
include "../../config/koneksi.php";

// APPROVE KOMENTAR
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);

    mysqli_query($koneksi, "UPDATE komentar SET status='approved' WHERE id_komentar='$id'");

    header("Location: index.php?msg=Komentar berhasil disetujui");
    exit;
}

// HAPUS KOMENTAR
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);

    mysqli_query($koneksi, "DELETE FROM komentar WHERE id_komentar='$id'");

    header("Location: index.php?msg=Komentar berhasil dihapus");
    exit;
}
?>