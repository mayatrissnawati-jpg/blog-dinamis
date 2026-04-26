<?php
include "../config/auth.php";
include "../config/koneksi.php";
cek_login('author');

$id_user = $_SESSION['id_user'] ?? 0;
$keyword = $_GET['keyword'] ?? '';

// ================= USER =================
$stmtUser = mysqli_prepare($koneksi, "SELECT nama FROM users WHERE id_user=?");
mysqli_stmt_bind_param($stmtUser, "i", $id_user);
mysqli_stmt_execute($stmtUser);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtUser));

// ================= FUNCTION =================
function countData($koneksi, $query, $types, ...$params) {
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['COUNT(*)'] ?? 0;
}

// ================= STAT =================
$artikel  = countData($koneksi, "SELECT COUNT(*) FROM artikel WHERE id_user=?", "i", $id_user);
$pending  = countData($koneksi, "SELECT COUNT(*) FROM artikel WHERE id_user=? AND status='pending'", "i", $id_user);
$komentar = countData($koneksi, "
    SELECT COUNT(k.id_komentar)
    FROM komentar k
    JOIN artikel a ON k.id_artikel=a.id_artikel
    WHERE a.id_user=?", "i", $id_user);

// cek like
$pakaiLike = mysqli_num_rows(mysqli_query($koneksi, "SHOW TABLES LIKE 'likes'")) > 0;

$like = $pakaiLike 
    ? countData($koneksi, "
        SELECT COUNT(l.id_like)
        FROM likes l
        JOIN artikel a ON l.id_artikel=a.id_artikel
        WHERE a.id_user=?", "i", $id_user)
    : 0;

// ================= LIST =================
$query = "
SELECT a.*, 
COUNT(DISTINCT k.id_komentar) as total_komentar,
".($pakaiLike ? "COUNT(DISTINCT l.id_like)" : "0")." as total_like
FROM artikel a
LEFT JOIN komentar k ON a.id_artikel=k.id_artikel
".($pakaiLike ? "LEFT JOIN likes l ON a.id_artikel=l.id_artikel" : "")."
WHERE a.id_user=?
";

if ($keyword) $query .= " AND a.judul LIKE ?";

$query .= " GROUP BY a.id_artikel ORDER BY total_like DESC";

$stmt = mysqli_prepare($koneksi, $query);

if ($keyword) {
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
<title>Dashboard</title>

<style>
*{box-sizing:border-box}
body{margin:0;font-family:'Segoe UI';background:#f4f6fb}

/* NAVBAR */
.navbar{
    height:60px;
    background:linear-gradient(135deg,#7B68EE,#9370DB);
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 20px;
    color:white;
    position:fixed;
    top:0;
    left:0;
    right:0;
    z-index:1000;
}

/* LEFT */
.nav-left{display:flex;align-items:center;gap:10px}
.menu-btn{cursor:pointer;font-size:22px}

/* RIGHT */
.nav-right{display:flex;align-items:center;gap:15px}

/* SEARCH */
.search-box{
    background:rgba(255,255,255,0.2);
    padding:5px 10px;
    border-radius:20px;
}
.search-box input{
    border:none;
    background:transparent;
    color:white;
    outline:none;
    width:140px;
}

/* PROFILE */
.profile-menu{position:relative;cursor:pointer;padding:5px 10px;border-radius:8px}
.profile-menu:hover{background:rgba(255,255,255,0.2)}

.dropdown{
    display:none;
    position:absolute;
    right:0;
    top:50px;
    background:white;
    border-radius:10px;
    box-shadow:0 8px 20px rgba(0,0,0,0.15);
    min-width:150px;
    z-index:9999;
}
.dropdown{
    display:none;
}
.dropdown.show{
    display:block;
}

.dropdown a{
    display:block;
    padding:10px;
    color:#333;
    text-decoration:none;
}
.dropdown a:hover{background:#f5f5ff}

/* SIDEBAR */
.sidebar{
    width:230px;
    height:100vh;
    background:white;
    position:fixed;
    top:60px;
    transition:.3s;
}
.sidebar.hide{left:-230px}

.sidebar a{
    display:block;
    padding:12px 20px;
    color:#555;
    text-decoration:none;
}
.sidebar a:hover{background:#f0f0ff}

/* MAIN */
.main{
    margin-left:230px;
    margin-top:60px;
    padding:25px;
    transition:.3s;
}
.main.full{margin-left:0}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:15px;
}
.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 3px 10px rgba(0,0,0,0.05);
}

/* ARTIKEL */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}
.artikel{
    background:white;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 3px 10px rgba(0,0,0,0.05);
}
.artikel img{
    width:100%;
    height:160px;
    object-fit:cover;
}
.artikel-content{padding:15px}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <div class="menu-btn" onclick="toggleSidebar()">☰</div>
        <b>Dashboard</b>
    </div>

    <div class="nav-right">
        <form method="GET" class="search-box">
            <input type="text" name="keyword" placeholder="Cari..." value="<?= htmlspecialchars($keyword) ?>">
        </form>

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
<div class="sidebar" id="sidebar">
    <a href="#">🏠 Dashboard</a>
    <a href="artikel/index.php">📝 Artikel</a>
    <a href="kategori/index.php">📂 Kategori</a>
    <a href="tag/index.php">🏷️ Tag</a>
    <a href="komentar/index.php">💬 Komentar</a>
</div>

<!-- MAIN -->
<div class="main" id="main">

<h2>Dashboard Author</h2>

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

<script>
function toggleSidebar(){
    document.getElementById("sidebar").classList.toggle("hide");
    document.getElementById("main").classList.toggle("full");
}
</script>

<script>
function toggleDropdown(e){
    e.stopPropagation(); // biar gak langsung nutup
    document.getElementById("dropdownMenu").classList.toggle("show");
}

// klik di luar = tutup dropdown
document.addEventListener("click", function(){
    document.getElementById("dropdownMenu").classList.remove("show");
});
</script>
</body>
</html>