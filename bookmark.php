<?php
session_start();
include "config/koneksi.php";

header("Content-Type: application/json");

// cek login
if (!isset($_SESSION['id_user'])) {
    echo json_encode([
        "status" => "error",
        "message" => "login required"
    ]);
    exit;
}

$id_user = $_SESSION['id_user'];
$id_artikel = $_GET['id_artikel'] ?? null;

// validasi
if (!$id_artikel || !is_numeric($id_artikel)) {
    echo json_encode([
        "status" => "error",
        "message" => "invalid artikel"
    ]);
    exit;
}

// cek bookmark
$query = "
    SELECT 1 FROM bookmark 
    WHERE id_user = '$id_user' 
    AND id_artikel = '$id_artikel'
";
$cek = mysqli_query($koneksi, $query);

if (!$cek) {
    echo json_encode([
        "status" => "error",
        "message" => "query failed"
    ]);
    exit;
}

// jika sudah ada → hapus
if (mysqli_num_rows($cek) > 0) {

    $delete = mysqli_query($koneksi, "
        DELETE FROM bookmark 
        WHERE id_user = '$id_user' 
        AND id_artikel = '$id_artikel'
    ");

    if ($delete) {
        echo json_encode([
            "status" => "success",
            "action" => "removed"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "gagal hapus"
        ]);
    }

} else {

    // jika belum ada → tambah
    $insert = mysqli_query($koneksi, "
        INSERT INTO bookmark (id_user, id_artikel) 
        VALUES ('$id_user', '$id_artikel')
    ");

    if ($insert) {
        echo json_encode([
            "status" => "success",
            "action" => "added"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "gagal tambah"
        ]);
    }
}
?>