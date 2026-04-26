<?php
include "../../config/koneksi.php";
include "../../config/auth.php";
cek_login('author');

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// ambil data dulu (untuk hapus gambar)
$stmt = mysqli_prepare($koneksi, "SELECT gambar FROM artikel WHERE id_artikel=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: index.php?msg=Artikel tidak ditemukan");
    exit;
}

// hapus gambar jika ada
if (!empty($data['gambar'])) {
    $file = "../../gambar/" . $data['gambar'];
    if (file_exists($file)) {
        unlink($file);
    }
}

// hapus artikel
$stmt = mysqli_prepare($koneksi, "DELETE FROM artikel WHERE id_artikel=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: index.php?msg=Artikel berhasil dihapus");
exit;