<?php
session_start();
include "config/koneksi.php";

// =======================
// CEK LOGIN
// =======================
if (!isset($_SESSION['id_user'])) {
    echo "login";
    exit;
}

$id_user = $_SESSION['id_user'];
$id_artikel = $_GET['id_artikel'] ?? null;

// =======================
// VALIDASI
// =======================
if (!$id_artikel || !is_numeric($id_artikel)) {
    echo "error";
    exit;
}

// =======================
// CEK LIKE
// =======================
$cek = mysqli_query($koneksi, "
    SELECT id_user FROM likes 
    WHERE id_user='$id_user' 
    AND id_artikel='$id_artikel'
");

if (mysqli_num_rows($cek) > 0) {

    // ===================
    // UNLIKE
    // ===================
    mysqli_query($koneksi, "
        DELETE FROM likes 
        WHERE id_user='$id_user' 
        AND id_artikel='$id_artikel'
    ");

    echo "unliked";

} else {

    // ===================
    // LIKE
    // ===================
    mysqli_query($koneksi, "
        INSERT INTO likes (id_user, id_artikel) 
        VALUES ('$id_user','$id_artikel')
    ");

    echo "liked";
}
?>