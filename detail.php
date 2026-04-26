<?php
include "config/koneksi.php";
session_start();

// 🔥 FIX SESSION NAMA
if (!isset($_SESSION['nama'])) {
    $_SESSION['nama'] = $_SESSION['username'] ?? 'User';
}

$isLogin = isset($_SESSION['id_user']);

// AMBIL ID ARTIKEL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Artikel tidak ditemukan");
}

$id = (int) $_GET['id'];

// ======================
// PROSES KOMENTAR
// ======================
if (isset($_POST['kirim_komentar']) && $isLogin) {

    $isi = trim($_POST['isi'] ?? '');

    if (!empty($isi)) {

    $nama = $_SESSION['nama'] ?? $_SESSION['username'] ?? 'User';

    mysqli_query($koneksi, "
        INSERT INTO komentar (id_user, id_artikel, nama, komentar, tanggal, parent_id)
        VALUES (
            '{$_SESSION['id_user']}',
            '$id',
            '$nama',
            '".mysqli_real_escape_string($koneksi, $isi)."',
            NOW(),
            NULL
        )
    ");

    // 🔥 PENTING: mencegah komentar dobel
    header("Location: detail.php?id=$id");
    exit;
}
}

// ======================
// QUERY ARTIKEL
// ======================
$query = mysqli_query($koneksi, "
    SELECT a.*, k.nama_kategori 
    FROM artikel a
    LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
    WHERE a.id_artikel = '$id' AND a.status='publish'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Artikel tidak ditemukan");
}

// ======================
// KOMENTAR UTAMA
// ======================
$stmtKomen = mysqli_prepare($koneksi, "
    SELECT * FROM komentar 
    WHERE id_artikel=? AND parent_id IS NULL
    ORDER BY id_komentar DESC
");
mysqli_stmt_bind_param($stmtKomen, "i", $id);
mysqli_stmt_execute($stmtKomen);
$komentar = mysqli_stmt_get_result($stmtKomen);

// 🔥 BACK BUTTON SOURCE
$from = $_GET['from'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($data['judul']) ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background: #f8fafc; }
.container-detail { max-width: 800px; margin: auto; }
.img-detail { width: 100%; border-radius: 15px; margin-bottom: 20px; }
.badge-kategori { background: #9370DB; }
.btn-primary { background: #9370DB; border: none; }
.komentar-box {
    background: white;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 12px;
}
.reply-box {
    margin-left: 30px;
    background: #f1f1f1;
    padding: 10px;
    border-radius: 8px;
    margin-top: 8px;
}
</style>
</head>

<body>

<div class="container mt-4 container-detail">

    <!-- BACK -->
    <a href="<?= ($from == 'admin') ? 'admin/dashboard.php' : 'index.php' ?>" 
       class="btn btn-outline-secondary btn-sm mb-3">
        ← Kembali
    </a>

    <!-- JUDUL -->
    <h2><?= htmlspecialchars($data['judul']) ?></h2>

    <!-- KATEGORI -->
    <span class="badge badge-kategori mb-3">
        <?= htmlspecialchars($data['nama_kategori'] ?? 'Tanpa Kategori') ?>
    </span>

    <!-- GAMBAR -->
    <?php 
    $file = "gambar/" . $data['gambar'];
    if (!empty($data['gambar']) && file_exists($file)): ?>
        <img src="<?= $file ?>" class="img-detail">
    <?php else: ?>
        <img src="gambar/default.png" class="img-detail">
    <?php endif; ?>

    <!-- ISI -->
    <p style="line-height:1.8;">
        <?= nl2br(htmlspecialchars($data['isi'])) ?>
    </p>

    <!-- INTERAKSI -->
    <div class="mt-4">
        <?php if ($isLogin): ?>
            <a href="#" onclick="likeArtikel(<?= $data['id_artikel'] ?>)" class="btn btn-outline-danger btn-sm">❤️ Like</a>
            <a href="#" onclick="bookmarkArtikel(<?= $data['id_artikel'] ?>)" class="btn btn-outline-warning btn-sm">
                🔖 Bookmark
            </a>
        <?php else: ?>
            <a href="auth/login.php" class="btn btn-secondary btn-sm">
                Login untuk Like & Bookmark
            </a>
        <?php endif; ?>
    </div>

    <hr>

    <!-- KOMENTAR -->
    <h5>Komentar</h5>

    <?php if ($isLogin): ?>
    <form method="POST">
        <textarea name="isi" class="form-control mb-2" placeholder="Tulis komentar..." required></textarea>
        <button type="submit" name="kirim_komentar" class="btn btn-primary btn-sm">
            Kirim
        </button>
    </form>
    <?php else: ?>
        <p><a href="auth/login.php">Login</a> untuk komentar</p>
    <?php endif; ?>

    <br>

    <!-- LIST KOMENTAR -->
    <?php if (mysqli_num_rows($komentar) > 0): ?>
        <?php while ($k = mysqli_fetch_assoc($komentar)): ?>

        <div class="komentar-box">
            <b><?= htmlspecialchars($k['nama']) ?></b><br>
            <small><?= $k['tanggal'] ?></small>
            <p><?= htmlspecialchars($k['komentar']) ?></p>

            <?php
            $replyStmt = mysqli_prepare($koneksi, "
                SELECT * FROM komentar 
                WHERE parent_id=? 
                ORDER BY id_komentar ASC
            ");
            mysqli_stmt_bind_param($replyStmt, "i", $k['id_komentar']);
            mysqli_stmt_execute($replyStmt);
            $reply = mysqli_stmt_get_result($replyStmt);

            while ($r = mysqli_fetch_assoc($reply)) {
            ?>
                <div class="reply-box">
                    <b style="color:#6A5ACD;">👤 Author</b><br>
                    <small><?= $r['tanggal'] ?></small>
                    <p><?= htmlspecialchars($r['komentar']) ?></p>
                </div>
            <?php } ?>
        </div>

        <?php endwhile; ?>
    <?php else: ?>
        <p>Belum ada komentar 😢</p>
    <?php endif; ?>

</div>

<script>
function likeArtikel(id) {
    fetch("like.php?id_artikel=" + id)
    .then(res => res.text())
    .then(data => {
        if (data === "login") {
            alert("Silakan login dulu!");
            window.location.href = "auth/login.php";
        } else {
            location.reload();
        }
    });
}

function bookmarkArtikel(id) {
    fetch("bookmark.php?id_artikel=" + id)
    .then(res => res.text())
    .then(data => {
        if (data === "login") {
            alert("Silakan login dulu!");
            window.location.href = "auth/login.php";
        } else {
            location.reload();
        }
    });
}
</script>

</body>
</html>