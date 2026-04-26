<?php
include "../../config/koneksi.php";
include "../../config/auth.php";

// SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CEK LOGIN & ROLE
cek_login();
if ($_SESSION['role'] !== 'author') {
    header("Location: ../../index.php");
    exit;
}

$id_user = $_SESSION['id_user'];

/* ================= UPLOAD GAMBAR ================= */
function uploadGambar() {

    if ($_FILES['gambar']['error'] == 4) {
        return null;
    }

    $namaFile = $_FILES['gambar']['name'];
    $tmp      = $_FILES['gambar']['tmp_name'];
    $size     = $_FILES['gambar']['size'];

    $extValid = ['jpg','jpeg','png'];
    $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    if (!in_array($ext, $extValid)) {
        return 'format';
    }

    if ($size > 2000000) {
        return 'size';
    }

    $namaBaru = uniqid() . '.' . $ext;

    if (!move_uploaded_file($tmp, "../../gambar/" . $namaBaru)) {
        return false;
    }

    return $namaBaru;
}

/* ================= TAMBAH ================= */
if (isset($_POST['simpan'])) {

    $judul = trim($_POST['judul']);
    $isi   = trim($_POST['isi']);
    $id_kategori = intval($_POST['id_kategori']);
    $id_tag      = intval($_POST['id_tag'] ?? 0);

    if ($judul == '' || $isi == '') {
        header("Location: tambah.php?msg=Judul & isi wajib diisi");
        exit;
    }

    if ($id_kategori <= 0) {
        header("Location: tambah.php?msg=Kategori wajib dipilih");
        exit;
    }

    $gambar = uploadGambar();

    if ($gambar === 'format') {
        header("Location: tambah.php?msg=Format gambar harus JPG/PNG");
        exit;
    }

    if ($gambar === 'size') {
        header("Location: tambah.php?msg=Ukuran max 2MB");
        exit;
    }

    $stmt = mysqli_prepare($koneksi, "
        INSERT INTO artikel 
        (judul, isi, gambar, id_user, id_kategori, id_tag, tanggal, status)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), 'pending')
    ");

    mysqli_stmt_bind_param($stmt, "sssiii",
        $judul, $isi, $gambar, $id_user, $id_kategori, $id_tag
    );

    mysqli_stmt_execute($stmt);

    header("Location: index.php?msg=Artikel berhasil ditambahkan");
    exit;
}

/* ================= UPDATE ================= */
if (isset($_POST['update'])) {

    $id = intval($_POST['id']);
    $judul = trim($_POST['judul']);
    $isi   = trim($_POST['isi']);
    $id_kategori = intval($_POST['id_kategori']);
    $id_tag      = intval($_POST['id_tag'] ?? 0);

    if ($judul == '' || $isi == '') {
        header("Location: edit.php?id=$id&msg=Judul & isi wajib diisi");
        exit;
    }

    $gambar = uploadGambar();

    // ambil gambar lama
    $old = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT gambar FROM artikel WHERE id_artikel='$id'
    "));

    if ($gambar && $gambar !== null) {

        if (!empty($old['gambar']) && file_exists("../../gambar/" . $old['gambar'])) {
            unlink("../../gambar/" . $old['gambar']);
        }

        $stmt = mysqli_prepare($koneksi, "
            UPDATE artikel 
            SET judul=?, isi=?, gambar=?, id_kategori=?, id_tag=?, status='pending'
            WHERE id_artikel=?
        ");

        mysqli_stmt_bind_param($stmt, "sssiii",
            $judul, $isi, $gambar, $id_kategori, $id_tag, $id
        );

    } else {

        $stmt = mysqli_prepare($koneksi, "
            UPDATE artikel 
            SET judul=?, isi=?, id_kategori=?, id_tag=?, status='pending'
            WHERE id_artikel=?
        ");

        mysqli_stmt_bind_param($stmt, "ssiii",
            $judul, $isi, $id_kategori, $id_tag, $id
        );
    }

    mysqli_stmt_execute($stmt);

    header("Location: index.php?msg=Artikel berhasil diupdate");
    exit;
}

/* ================= FALLBACK ================= */
header("Location: index.php");
exit;