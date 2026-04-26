<?php
session_start();
include "../config/auth.php";
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_SESSION['id_user'];

// ambil data user
$stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// tentukan tombol kembali berdasarkan role
$role = $_SESSION['role'] ?? '';

if ($role == 'admin') {
    $back = '/blog/admin/dashboard.php';
} elseif ($role == 'author') {
    $back = '/blog/author/dashboard.php';
} else {
    $back = '/blog/index.php';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Saya</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #eef2ff, #dbeafe, #f8fafc);
}

/* CARD */
.box {
    max-width: 500px;
    margin: 80px auto;
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.box:hover {
    transform: translateY(-5px);
}

/* HEADER */
.header {
    text-align: center;
    margin-bottom: 20px;
}

.header img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #6366f1;
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

.header h3 {
    margin-top: 10px;
}

/* TABLE */
table {
    width: 100%;
    margin-top: 20px;
}

td {
    padding: 8px;
}

td:first-child {
    font-weight: 600;
    color: #555;
}

/* BUTTON */
.btn {
    display: inline-block;
    padding: 10px 15px;
    border-radius: 10px;
    color: white;
    text-decoration: none;
    margin: 10px 5px 0;
    transition: 0.3s;
}

.edit {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
}

.back {
    background: #6b7280;
}

.btn:hover {
    opacity: 0.85;
    transform: scale(1.05);
}
</style>
</head>

<body>

<div class="box">

<div class="header">
<?php
$path = "../gambar/";
$foto = (!empty($user['foto']) && file_exists($path . $user['foto']))
    ? $user['foto']
    : 'default.png';
?>

<img src="<?= $path . htmlspecialchars($foto) ?>">
<h3><?= htmlspecialchars($user['nama']) ?></h3>
</div>

<table>
<tr>
<td>Nama</td>
<td>: <?= htmlspecialchars($user['nama']) ?></td>
</tr>
<tr>
<td>Email</td>
<td>: <?= htmlspecialchars($user['email']) ?></td>
</tr>
<tr>
<td>Role</td>
<td>: <?= htmlspecialchars($user['role']) ?></td>
</tr>
</table>

<center>
<a href="edit.php" class="btn edit">Edit Profil</a>
<a href="<?= $back ?>" class="btn back">Kembali</a>
</center>

</div>

</body>
</html>