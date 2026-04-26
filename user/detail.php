<?php
include "../config/koneksi.php";
session_start();

// 🔐 CEK LOGIN
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

// 🔍 VALIDASI ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// 🔎 AMBIL DATA ARTIKEL
$stmt = mysqli_prepare($koneksi, "
    SELECT a.*, k.nama_kategori 
    FROM artikel a
    LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
    WHERE a.id_artikel = ? AND a.status='publish'
");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($data['judul']) ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f4f4f9;
    font-family: 'Segoe UI', sans-serif;
}

.detail-box {
    max-width: 800px;
    margin: 50px auto;
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.img-detail {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 10px;
}

.badge-kategori {
    background: #9370DB;
}

.btn-back {
    margin-bottom: 15px;
}

.komentar-box {
    margin-top: 40px;
}

.komentar-item {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 10px;
    margin-bottom: 10px;
}
</style>
</head>

<body>

<div class="container">

<div class="detail-box">

<!-- BACK -->
<a href="index.php" class="btn btn-light btn-sm btn-back">← Kembali</a>

<!-- JUDUL -->
<h3><?= htmlspecialchars($data['judul']) ?></h3>

<!-- KATEGORI -->
<span class="badge badge-kategori mb-3">
    <?= htmlspecialchars($data['nama_kategori'] ?? 'Tanpa Kategori') ?>
</span>

<!-- GAMBAR -->
<?php 
$file = "../gambar/" . $data['gambar'];
if (!empty($data['gambar']) && file_exists($file)) { ?>
    <img src="/blog/gambar/<?= htmlspecialchars($data['gambar']) ?>" class="img-detail mb-3">
<?php } ?>

<!-- ISI -->
<p><?= nl2br(htmlspecialchars($data['isi'])) ?></p>

<!-- ================= KOMENTAR ================= -->
<div class="komentar-box">

<hr>
<h5>💬 Komentar</h5>

<!-- FORM KOMENTAR -->
<form method="POST" action="komentar.php" class="mt-3">
    <input type="hidden" name="id_artikel" value="<?= $data['id_artikel'] ?>">

    <textarea name="komentar" class="form-control mb-2" 
        placeholder="Tulis komentar..." required></textarea>

    <button type="submit" name="kirim" class="btn btn-primary btn-sm">
        Kirim Komentar
    </button>
</form>

<!-- LIST KOMENTAR -->
<?php
$komentar = mysqli_prepare($koneksi, "
    SELECT k.*, u.username 
    FROM komentar k
    JOIN users u ON k.id_user = u.id_user
    WHERE k.id_artikel = ?
    ORDER BY k.id_komentar DESC
");

mysqli_stmt_bind_param($komentar, "i", $id);
mysqli_stmt_execute($komentar);
$result_komen = mysqli_stmt_get_result($komentar);

while ($k = mysqli_fetch_assoc($result_komen)) {
?>
    <div class="komentar-item">
        <strong><?= htmlspecialchars($k['username']) ?></strong><br>
        <small><?= htmlspecialchars($k['komentar']) ?></small>
    </div>
<?php } ?>

</div>

</div>

</div>

</body>
</html>