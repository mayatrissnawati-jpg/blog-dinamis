<?php
session_start();
include "../config/auth.php";
include "../config/koneksi.php";

cek_role('author');

$id_user = $_SESSION['id_user'] ?? 0;
$keyword = $_GET['keyword'] ?? '';

// ================= USER =================
$stmtUser = mysqli_prepare($koneksi, "SELECT nama FROM users WHERE id_user=?");
mysqli_stmt_bind_param($stmtUser, "i", $id_user);
mysqli_stmt_execute($stmtUser);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtUser));

// ================= FUNCTION =================
function countData($koneksi, $query, $types = "", ...$params) {
    $stmt = mysqli_prepare($koneksi, $query);

    if ($types && $params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_fetch_row(mysqli_stmt_get_result($stmt));

    return $result[0] ?? 0;
}

// ================= STAT =================
$artikel = countData($koneksi,
    "SELECT COUNT(*) FROM artikel WHERE id_user=?",
    "i", $id_user
);

$pending = countData($koneksi,
    "SELECT COUNT(*) FROM artikel WHERE id_user=? AND status='pending'",
    "i", $id_user
);

$komentar = countData($koneksi,
    "SELECT COUNT(*)
     FROM komentar k
     JOIN artikel a ON k.id_artikel = a.id_artikel
     WHERE a.id_user=?",
    "i", $id_user
);

// cek tabel likes
$pakaiLike = mysqli_num_rows(mysqli_query($koneksi, "SHOW TABLES LIKE 'likes'")) > 0;

$like = $pakaiLike
    ? countData($koneksi,
        "SELECT COUNT(*)
         FROM likes l
         JOIN artikel a ON l.id_artikel = a.id_artikel
         WHERE a.id_user=?",
        "i", $id_user
    )
    : 0;

// ================= LIST ARTIKEL =================
$query = "
SELECT a.*,
COUNT(DISTINCT k.id_komentar) AS total_komentar,
" . ($pakaiLike ? "COUNT(DISTINCT l.id_like)" : "0") . " AS total_like
FROM artikel a
LEFT JOIN komentar k ON a.id_artikel = k.id_artikel
" . ($pakaiLike ? "LEFT JOIN likes l ON a.id_artikel = l.id_artikel" : "") . "
WHERE a.id_user = ?
";

if (!empty($keyword)) {
    $query .= " AND a.judul LIKE ?";
}

$query .= " GROUP BY a.id_artikel ORDER BY a.id_artikel DESC";

$stmt = mysqli_prepare($koneksi, $query);

if (!empty($keyword)) {
    $search = "%$keyword%";
    mysqli_stmt_bind_param($stmt, "is", $id_user, $search);
} else {
    mysqli_stmt_bind_param($stmt, "i", $id_user);
}

mysqli_stmt_execute($stmt);
$listArtikel = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Author</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI';
    background: #f5f6fa;
}

/* NAVBAR */
.navbar {
    height: 60px;
    background: linear-gradient(135deg,#7B68EE,#9370DB);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    color: white;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

.nav-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.menu-btn {
    font-size: 22px;
    cursor: pointer;
}

/* RIGHT */
.nav-right {
    display: flex;
    align-items: center;
}

/* PROFILE */
.profile-menu {
    position: relative;
    cursor: pointer;
    padding: 6px 12px;
    border-radius: 8px;
    background: rgba(255,255,255,0.2);
}

.profile-menu:hover {
    background: rgba(255,255,255,0.3);
}

/* DROPDOWN */
.dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 45px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    min-width: 150px;
    overflow: hidden;
}

.dropdown a {
    display: block;
    padding: 10px;
    text-decoration: none;
    color: #333;
}

.dropdown a:hover {
    background: #f5f5ff;
}

.dropdown.show {
    display: block;
}

/* SIDEBAR */
.sidebar {
    width: 220px;
    height: 100vh;
    background: white;
    position: fixed;
    top: 60px;
    left: 0;
    transition: 0.3s;
}

.sidebar.hide {
    left: -220px;
}

.sidebar a {
    display: block;
    padding: 12px 20px;
    text-decoration: none;
    color: #555;
}

.sidebar a:hover {
    background: #f0f0ff;
}

/* MAIN */
.main {
    margin-left: 220px;
    margin-top: 70px;
    padding: 20px;
    transition: 0.3s;
}

.main.full {
    margin-left: 0;
}

/* CARDS */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(180px,1fr));
    gap: 15px;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

/* ARTIKEL */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(250px,1fr));
    gap: 20px;
}

.artikel {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.artikel img {
    width: 100%;
    height: 170px;
    object-fit: cover;
}

.artikel-content {
    padding: 15px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <span class="menu-btn" onclick="toggleSidebar()">☰</span>
        <b>Dashboard Author</b>
    </div>

    <div class="nav-right">
        <div class="profile-menu" onclick="toggleDropdown(event)">
            👤 <?= htmlspecialchars($user['nama'] ?? 'Author') ?>

            <div class="dropdown" id="dropdownMenu">
                <a href="profil/index.php">Profil</a>
                <a href="../auth/logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- SIDEBAR -->
<div class="sidebar">
    <a href="#">🏠 Dashboard</a>
    <a href="artikel/index.php">📝 Artikel</a>
    <a href="kategori/index.php">📂 Kategori</a>
    <a href="tag/index.php">🏷️ Tag</a>
    <a href="komentar/index.php">💬 Komentar</a>
</div>

<!-- MAIN -->
<div class="main">

<h2>Dashboard</h2>

<div class="cards">
    <div class="card">📝 Artikel<br><b><?= $artikel ?></b></div>
    <div class="card">⏳ Pending<br><b><?= $pending ?></b></div>
    <div class="card">💬 Komentar<br><b><?= $komentar ?></b></div>
    <div class="card">❤️ Like<br><b><?= $like ?></b></div>
</div>

<hr>

<h3>Artikel Saya</h3>

<div class="grid">
<?php while($a = mysqli_fetch_assoc($listArtikel)) { 
    $gambar = (!empty($a['gambar']) && file_exists("../gambar/".$a['gambar'])) 
        ? "../gambar/".$a['gambar'] 
        : "https://via.placeholder.com/300x160";
?>
<div class="artikel">
    <img src="<?= $gambar ?>">
    <div class="artikel-content">
        <h4><?= htmlspecialchars($a['judul']) ?></h4>
        <p><?= substr(strip_tags($a['isi']),0,70) ?>...</p>
        <small>❤️ <?= $a['total_like'] ?> | 💬 <?= $a['total_komentar'] ?></small>
    </div>
</div>
<?php } ?>
</div>

</div>

<!-- JS -->
<script>
function toggleSidebar(){
    document.querySelector(".sidebar").classList.toggle("hide");
    document.querySelector(".main").classList.toggle("full");
}

function toggleDropdown(e){
    e.stopPropagation();
    document.getElementById("dropdownMenu").classList.toggle("show");
}

document.addEventListener("click", function(e){
    const menu = document.getElementById("dropdownMenu");
    const profile = document.querySelector(".profile-menu");

    if (!profile.contains(e.target)) {
        menu.classList.remove("show");
    }
});
</script>

</body>
</html>