<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../config/koneksi.php";

/* =======================
   APPROVE ARTIKEL
======================= */
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];

    mysqli_query($koneksi, "
        UPDATE artikel SET status='publish' 
        WHERE id_artikel='$id'
    ");

    header("Location: index.php");
    exit;
}


/* =======================
   HAPUS ARTIKEL
======================= */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // ambil gambar dulu
    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT gambar FROM artikel WHERE id_artikel='$id'
    "));

    // hapus file gambar (jika ada)
    if (!empty($cek['gambar']) && file_exists("../../gambar/" . $cek['gambar'])) {
        unlink("../../gambar/" . $cek['gambar']);
    }

    // hapus data
    mysqli_query($koneksi, "
        DELETE FROM artikel WHERE id_artikel='$id'
    ");

    header("Location: index.php");
    exit;
}


/* =======================
   UPDATE ARTIKEL
======================= */
if (isset($_POST['update'])) {

    $id     = $_POST['id'];
    $judul  = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi    = mysqli_real_escape_string($koneksi, $_POST['isi']);

    // ambil gambar lama
    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT gambar FROM artikel WHERE id_artikel='$id'
    "));
    $gambar_lama = $cek['gambar'];

    // cek apakah upload gambar baru
    if (!empty($_FILES['gambar']['name'])) {

        $nama_file = $_FILES['gambar']['name'];
        $tmp       = $_FILES['gambar']['tmp_name'];

        // ambil ekstensi
        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        // validasi tipe file
        if (!in_array($ext, $allowed)) {
            echo "Format gambar tidak valid!";
            exit;
        }

        // nama unik
        $nama_baru = time() . "_" . $nama_file;

        // upload
        move_uploaded_file($tmp, "../../gambar/" . $nama_baru);

        // hapus gambar lama
        if (!empty($gambar_lama) && file_exists("../../gambar/" . $gambar_lama)) {
            unlink("../../gambar/" . $gambar_lama);
        }

        $gambar = $nama_baru;

    } else {
        // pakai gambar lama
        $gambar = $gambar_lama;
    }

    // update database
    mysqli_query($koneksi, "
        UPDATE artikel SET
            judul = '$judul',
            isi = '$isi',
            gambar = '$gambar'
        WHERE id_artikel = '$id'
    ");

    header("Location: index.php");
    exit;
}
?>