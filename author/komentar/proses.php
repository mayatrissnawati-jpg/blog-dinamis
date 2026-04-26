<?php
include "../../config/koneksi.php";

$id_artikel = $_POST['id_artikel'] ?? 0;
$parent_id  = $_POST['parent_id'] ?? 0;
$komentar   = trim($_POST['komentar'] ?? '');

if ($komentar != '') {

    $stmt = mysqli_prepare($koneksi, "
        INSERT INTO komentar (id_artikel, komentar, parent_id, status)
        VALUES (?, ?, ?, 'approved')
    ");

    mysqli_stmt_bind_param($stmt, "isi", $id_artikel, $komentar, $parent_id);
    mysqli_stmt_execute($stmt);
}

header("Location: index.php");
exit;