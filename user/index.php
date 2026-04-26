<?php
include "../config/koneksi.php";
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$query = mysqli_query($koneksi, "
    SELECT a.*, k.nama_kategori 
    FROM artikel a
    LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
    WHERE a.status='publish'
    ORDER BY a.id_artikel DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Beranda User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- GOOGLE FONT -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #eef2ff, #f8fafc);
    font-family: 'Poppins', sans-serif;
}

/* NAVBAR */
.navbar {
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    margin: 15px;
}

.navbar a {
    color: #333 !important;
}

/* HERO */
.hero {
    background: linear-gradient(135deg, #9370DB, #7B68EE);
    color: white;
    padding: 50px;
    border-radius: 20px;
    margin-bottom: 40px;
    box-shadow: 0 10px 30px rgba(147,112,219,0.3);
}

/* CARD */
.card-artikel {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    background: white;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.card-body {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.card-artikel:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

/* GAMBAR */
.img-thumb {
    height: 200px;
    object-fit: cover;
    transition: 0.3s;
}

.card-artikel:hover .img-thumb {
    transform: scale(1.05);
}

/* KATEGORI */
.badge-kategori {
    background: #9370DB;
    font-size: 12px;
    padding: 6px 10px;
    border-radius: 10px;
}

/* JUDUL */
.card-body h6 {
    min-height: 48px;
}

.card-body p {
    min-height: 60px;
}

/* BUTTON */
.btn-primary {
    background: #9370DB;
    border: none;
    border-radius: 8px;
}

.btn-primary:hover {
    background: #7B68EE;
}

/* TEXT */
.text-muted {
    font-size: 13px;
}

/* ANIMASI HALUS */
.card-artikel,
.img-thumb {
    transition: all 0.3s ease-in-out;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg container px-3 py-2">
    <a class="navbar-brand fw-bold" href="#">📰 My Blog</a>

    <div class="ms-auto d-flex gap-2">
        <a href="index.php" class="btn btn-outline-dark btn-sm">Home</a>
        <a href="../profile/index.php" class="btn btn-outline-dark btn-sm">Profil</a>
        <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container">

<!-- HERO -->
<div class="hero text-center">
    <h2 class="fw-bold">✨ Selamat Datang di Blog</h2>
    <p class="mt-2">Temukan artikel menarik, inspiratif, dan informatif setiap hari</p>
</div>

<!-- LIST ARTIKEL -->
<div class="row">

<?php while ($d = mysqli_fetch_assoc($query)) { ?>

<div class="col-md-4 mb-4 d-flex">
    <div class="card card-artikel w-100 d-flex flex-column">

        <!-- GAMBAR -->
        <?php 
        $file = "../gambar/" . $d['gambar'];

        if (!empty($d['gambar']) && file_exists($file)) { ?>
            <img src="/blog/gambar/<?= htmlspecialchars($d['gambar']) ?>" class="img-thumb w-100">
        <?php } else { ?>
            <img src="/blog/gambar/default.png" class="img-thumb w-100">
        <?php } ?>

        <div class="card-body">

            <!-- KATEGORI -->
            <span class="badge badge-kategori">
                <?= htmlspecialchars($d['nama_kategori'] ?? 'Tanpa Kategori') ?>
            </span>

            <!-- JUDUL -->
            <h6><?= htmlspecialchars($d['judul']) ?></h6>

            <!-- ISI -->
            <p class="text-muted">
                <?= substr(strip_tags($d['isi']), 0, 90) ?>...
            </p>

            <!-- BUTTON -->
            <a href="detail.php?id=<?= $d['id_artikel'] ?>" 
                class="btn btn-primary btn-sm w-100 mt-auto">
                Baca Selengkapnya →
            </a>

        </div>

    </div>
</div>

<?php } ?>

</div>

</div>

</body>
</html>