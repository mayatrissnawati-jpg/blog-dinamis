<?php
session_start();

// simpan role dulu
$role = $_SESSION['role'] ?? null;

// 🔥 hapus semua isi session
$_SESSION = [];

// 🔥 hapus cookie session (penting!)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 🔥 hancurkan session
session_destroy();

// redirect
if ($role == 'admin' || $role == 'author') {
    header("Location: /blog/auth/login.php");
} else {
    header("Location: /blog/index.php");
}
exit;