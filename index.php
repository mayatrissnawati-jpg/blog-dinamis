<?php
session_start();
include "config/koneksi.php";

// =======================
// TRACKING PENGUNJUNG
// =======================
$ip = $_SERVER['REMOTE_ADDR'];
$tanggal = date('Y-m-d');

// cek apakah sudah dihitung hari ini
$cek = mysqli_query($koneksi,"
    SELECT * FROM pengunjung 
    WHERE ip='$ip' AND tanggal='$tanggal'
");

if (mysqli_num_rows($cek) == 0) {
    mysqli_query($koneksi,"
        INSERT INTO pengunjung (tanggal, ip)
        VALUES ('$tanggal','$ip')
    ");
}

// =======================
// CEK LOGIN
// =======================
$isLogin = isset($_SESSION['id_user']);

// =======================
// QUERY ARTIKEL
// =======================
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
<title>My Blog</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #eef2ff, #f8fafc);
    font-family: 'Poppins', sans-serif;
}

.navbar {
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    margin: 15px auto;
    max-width: 1100px;
}

.hero {
    background: linear-gradient(135deg, #9370DB, #7B68EE);
    color: white;
    padding: 50px;
    border-radius: 20px;
    margin-bottom: 40px;
    text-align: center;
}

.card-artikel {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    background: white;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    transition: 0.3s;
}

.card-artikel:hover {
    transform: translateY(-10px);
}

.img-thumb {
    height: 200px;
    object-fit: cover;
}

.badge-kategori {
    background: #9370DB;
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 8px;
}

.btn-primary {
    background: #9370DB;
    border: none;
}

.card-body {
    display: flex;
    flex-direction: column;
}

.card-body h6 { min-height: 50px; }
.card-body p { min-height: 60px; }

.interaksi {
    margin-top: auto;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar px-3 py-2">
    <div class="container-fluid">

        <span class="navbar-brand fw-bold">📰 My Blog</span>

        <div class="ms-auto d-flex gap-2">

            <a href="index.php" class="btn btn-outline-dark btn-sm">Home</a>

            <?php if ($isLogin): ?>

                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <a href="admin/dashboard.php" class="btn btn-warning btn-sm">Dashboard</a>
                <?php elseif ($_SESSION['role'] == 'author'): ?>
                    <a href="author/dashboard.php" class="btn btn-warning btn-sm">Dashboard</a>
                <?php endif; ?>

                <a href="profile/index.php" class="btn btn-outline-dark btn-sm">Profil</a>
                <a href="auth/logout.php" class="btn btn-danger btn-sm">Logout</a>

            <?php else: ?>

                <a href="auth/login.php" class="btn btn-primary btn-sm">Login</a>
                <a href="auth/register.php" class="btn btn-success btn-sm">Daftar</a>

            <?php endif; ?>

        </div>

    </div>
</nav>

<!-- CONTENT -->
<div class="container">

<!-- HERO -->
<div class="hero">
    <h2>✨ Selamat Datang di Blog</h2>
    <p>Temukan artikel menarik setiap hari</p>
</div>

<!-- LIST ARTIKEL -->
<div class="row">

<?php if (mysqli_num_rows($query) > 0): ?>
<?php while ($d = mysqli_fetch_assoc($query)): ?>

<div class="col-md-4 mb-4 d-flex" id="artikel-<?= $d['id_artikel'] ?>">
    <div class="card card-artikel w-100">

        <!-- GAMBAR -->
        <?php
        $file = "gambar/" . $d['gambar'];
        if (!empty($d['gambar']) && file_exists($file)): ?>
            <img src="gambar/<?= htmlspecialchars($d['gambar']) ?>" class="img-thumb w-100">
        <?php else: ?>
            <img src="gambar/default.png" class="img-thumb w-100">
        <?php endif; ?>

        <div class="card-body">

            <span class="badge badge-kategori mb-2">
                <?= htmlspecialchars($d['nama_kategori'] ?? 'Tanpa Kategori') ?>
            </span>

            <h6><?= htmlspecialchars($d['judul']) ?></h6>

            <p class="text-muted">
                <?= htmlspecialchars(substr(strip_tags($d['isi']), 0, 90)) ?>...
            </p>

            <a href="detail.php?id=<?= $d['id_artikel'] ?>" 
               class="btn btn-primary btn-sm mt-auto">
               Baca Selengkapnya →
            </a>

            <?php
            $id_artikel = $d['id_artikel'];
            $id_user = $_SESSION['id_user'] ?? 0;

            // Hitung like
            $q_like = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM likes WHERE id_artikel='$id_artikel'");
            $data_like = mysqli_fetch_assoc($q_like);

            // Cek like
            $cek_like = mysqli_query($koneksi, "SELECT * FROM likes WHERE id_user='$id_user' AND id_artikel='$id_artikel'");

            // Cek bookmark
            $cek_bookmark = mysqli_query($koneksi, "SELECT * FROM bookmark WHERE id_user='$id_user' AND id_artikel='$id_artikel'");
            ?>

            <div class="interaksi mt-2 d-flex gap-2">
                <?php if ($isLogin): ?>

                    <!-- LIKE -->
                    <a href="#" onclick="likeArtikel(<?= $id_artikel ?>); return false;" class="btn btn-outline-danger btn-sm">

                        <span id="like-icon-<?= $id_artikel ?>">
                            <?php if (mysqli_num_rows($cek_like) > 0): ?>
                                ❤️
                            <?php else: ?>
                                🤍
                            <?php endif; ?>
                        </span>

                        <span id="like-count-<?= $id_artikel ?>">
                            <?= $data_like['total']; ?>
                        </span>

                    </a>

                    <!-- BOOKMARK -->
                    <a href="#" onclick="toggleBookmark(<?= $id_artikel ?>, this); return false;" class="btn btn-sm">

                    <span id="bookmark-icon-<?= $id_artikel ?>">
                        <?php if (mysqli_num_rows($cek_bookmark) > 0): ?>
                            🔖
                        <?php else: ?>
                            📑
                        <?php endif; ?>
                    </span>

                </a>

                <?php else: ?>
                    <a href="auth/login.php" class="btn btn-outline-secondary btn-sm w-100">
                        Login untuk interaksi
                    </a>
                <?php endif; ?>
            </div>

        </div>

    </div>
</div>

<?php endwhile; ?>
<?php else: ?>

<div class="text-center mt-5">
    <h5>Belum ada artikel 😢</h5>
</div>

<?php endif; ?>

</div>
</div>

<script>
function likeArtikel(id) {
    fetch("like.php?id_artikel=" + id)
    .then(res => res.text())
    .then(res => {

        let icon = document.getElementById("like-icon-" + id);
        let count = document.getElementById("like-count-" + id);

        let total = parseInt(count.innerText);

        if (res === "liked") {
            icon.innerHTML = "❤️";
            count.innerText = total + 1;
        } else if (res === "unliked") {
            icon.innerHTML = "🤍";
            count.innerText = total - 1;
        }

    });
}

function toggleBookmark(id, el) {
    fetch("bookmark.php?id_artikel=" + id)
    .then(res => res.json())
    .then(res => {
        let icon = document.getElementById("bookmark-icon-" + id);
        if (res.status === "success") {
            if (res.action === "added") {
                icon.innerHTML = "🔖";
            } else if (res.action === "removed") {
                icon.innerHTML = "📑";
            }
        }
    });
}
</script>
</body>
</html>