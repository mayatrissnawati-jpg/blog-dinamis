<?php
include "../config/koneksi.php";
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_kategori = intval($_GET['id']);

// ambil nama kategori
$kat = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT * FROM kategori WHERE id_kategori='$id_kategori'
"));

// ambil artikel
$query = mysqli_query($koneksi, "
    SELECT a.*, k.nama_kategori 
    FROM artikel a
    LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
    WHERE a.id_kategori='$id_kategori' AND a.status='publish'
    ORDER BY a.id_artikel DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kategori</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f4f4f9;
    font-family: 'Segoe UI', sans-serif;
}

.header-box {
    background: linear-gradient(135deg, #9370DB, #7B68EE);
    color: white;
    padding: 20px;
    border-radius: 12px;
}

.card-artikel {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.img-thumb {
    height: 180px;
    object-fit: cover;
}

.badge-kategori {
    background: #9370DB;
}
</style>
</head>

<body>

<div class="container mt-4">

<div class="header-box mb-4">
    <h4>Kategori: <?= htmlspecialchars($kat['nama_kategori']) ?></h4>
    <a href="index.php" class="btn btn-light btn-sm mt-2">← Kembali</a>
</div>

<div class="row">

<?php while ($d = mysqli_fetch_assoc($query)) { ?>

<div class="col-md-4 mb-4">
    <div class="card card-artikel">

        <?php 
        $file = "../gambar/" . $d['gambar'];

        if (!empty($d['gambar']) && file_exists($file)) { ?>
            <img src="/blog/gambar/<?= htmlspecialchars($d['gambar']) ?>" class="img-thumb w-100">
        <?php } else { ?>
            <img src="/blog/gambar/default.png" class="img-thumb w-100">
        <?php } ?>

        <div class="card-body">

            <span class="badge badge-kategori mb-2">
                <?= htmlspecialchars($d['nama_kategori']) ?>
            </span>

            <h6><?= htmlspecialchars($d['judul']) ?></h6>

            <a href="detail.php?id=<?= $d['id_artikel'] ?>" class="btn btn-primary btn-sm">
                Baca →
            </a>

        </div>

    </div>
</div>

<?php } ?>

</div>

</div>

</body>
</html>