<?php
session_start();
include "../config/koneksi.php";

// ambil data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? '';
$captcha  = $_POST['captcha'] ?? '';

// 🔥 VALIDASI CAPTCHA
if ($captcha != ($_SESSION['captcha'] ?? '')) {
    header("Location: login.php?error=Captcha salah");
    exit;
}

// 🔥 CEK USER (AMAN)
$stmt = mysqli_prepare($koneksi, "
    SELECT * FROM users 
    WHERE username = ? AND role = ?
");
mysqli_stmt_bind_param($stmt, "ss", $username, $role);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

// 🔥 CEK DATA
if ($data) {

    // 🔥 CEK PASSWORD
    if ($password == $data['password']) {

        // 🔥 PENTING (biar session tidak bentrok)
        session_regenerate_id(true);

        // 🔥 SIMPAN SESSION
        $_SESSION['id_user']  = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role']     = $data['role'];
        $_SESSION['nama'] = $data['nama'];

        // DEBUG (hapus nanti)
        // var_dump($_SESSION); exit;

        // 🔥 REDIRECT SESUAI ROLE
        if ($role == 'admin') {
            header("Location: ../admin/dashboard.php");
        } elseif ($role == 'author') {
            header("Location: ../author/dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit;

    } else {
        header("Location: login.php?error=Password salah");
        exit;
    }

} else {
    header("Location: login.php?error=User tidak ditemukan");
    exit;
}