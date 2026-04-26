<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// cek login umum
function cek_login() {
    if (!isset($_SESSION['id_user'])) {
        header("Location: ../auth/login.php");
        exit;
    }
}

// cek role spesifik
function cek_role($role) {
    cek_login();

    if ($_SESSION['role'] !== $role) {
        // redirect sesuai role
        if ($_SESSION['role'] == 'admin') {
            header("Location: ../admin/dashboard.php");
        } elseif ($_SESSION['role'] == 'author') {
            header("Location: ../author/dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit;
    }
}