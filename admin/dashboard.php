<?php
include "../config/auth.php";
cek_role('admin'); // 🔥 WAJIB DI SINI

include "../config/koneksi.php";

// jumlah artikel
$data_artikel = mysqli_fetch_assoc(mysqli_query($koneksi,"
SELECT COUNT(*) as total FROM artikel
"));
$artikel = $data_artikel['total'];

// jumlah pending
$data_pending = mysqli_fetch_assoc(mysqli_query($koneksi,"
SELECT COUNT(*) as total FROM artikel WHERE status='pending'
"));
$pending = $data_pending['total'];

// jumlah user
$data_user = mysqli_fetch_assoc(mysqli_query($koneksi,"
SELECT COUNT(*) as total FROM users
"));
$user = $data_user['total'];

// jumlah komentar
$data_komentar = mysqli_fetch_assoc(mysqli_query($koneksi,"
SELECT COUNT(*) as total FROM komentar
"));
$komentar = $data_komentar['total'];

// =======================
// ARTIKEL TERPOPULER (LIKE TERBANYAK)
// =======================
$q_populer = mysqli_query($koneksi, "
SELECT a.judul, a.id_artikel, a.gambar, COUNT(l.id_like) as total_like
FROM artikel a
LEFT JOIN likes l ON a.id_artikel = l.id_artikel
GROUP BY a.id_artikel
ORDER BY total_like DESC
LIMIT 5
");
?>



<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f4f9;
}
h2 {
    margin-top: 0;
    margin-bottom: 20px;
}

/* NAVBAR */
.navbar {
    height: 60px;
    background: linear-gradient(135deg, #9370DB, #7B68EE);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    color: white;
}

/* TOGGLE BUTTON */
.toggle-btn {
    font-size: 20px;
    cursor: pointer;
}

/* USER */
.user {
    display: flex;
    align-items: center;
    gap: 10px;
}

.logout {
    background: #ff6b6b;
    padding: 6px 10px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    font-size: 12px;
}

/* SIDEBAR */
.sidebar {
    width: 230px;
    height: 100vh;
    background: white;
    position: absolute;
    top: 60px;
    left: 0;
    transition: 0.3s;
    box-shadow: 3px 0 10px rgba(0,0,0,0.05);
}

/* SIDEBAR HIDDEN */
.sidebar.hide {
    left: -230px;
}

/* MENU */
.sidebar a {
    display: block;
    padding: 12px 20px;
    color: #555;
    text-decoration: none;
    transition: 0.2s;
}

.sidebar a:hover {
    background: #f5f5ff;
    color: #6A5ACD;
}

.main {
    margin-left: 230px;
    padding: 15px 20px;
    position: relative;
    transition: 0.3s;
}

/* MAIN FULL */
.main.full {
    margin-left: 0;
}

/* CARDS */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px,1fr));
    gap: 20px;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.card h3 {
    margin: 0;
    color: #6A5ACD;
}

.card p {
    font-size: 22px;
    font-weight: bold;
}
</style>

<!-- NAVBAR -->
<div class="navbar">

    <div class="toggle-btn" onclick="toggleSidebar()">☰</div>

    <div class="users">
        👤 <?= $_SESSION['username'] ?>
        <a href="../auth/logout.php" class="logout">Logout</a>
    </div>

</div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="artikel/index.php">📝 Artikel</a>
    <a href="kategori/index.php">📂 Kategori</a>
    <a href="komentar/index.php">💬 Komentar</a>
    <a href="user/index.php">👤 Pengguna</a>
    <a href="tag/index.php">🏷️ Tag</a>
    <a href="statistik.php">📊 Statistik</a>
</div>

<!-- MAIN -->
<div class="main" id="main">

<h2>Dashboard Admin</h2>

<div class="cards">
    <div class="card">
        <h3>Total Artikel</h3>
        <p><?= $artikel ?></p>
    </div>

    <div class="card">
        <h3>Artikel Pending</h3>
        <p><?= $pending ?></p>
    </div>

    <div class="card">
        <h3>Total User</h3>
        <p><?= $user ?></p>
    </div>

    <div class="card">
        <h3>Komentar</h3>
        <p><?= $komentar ?></p>
    </div>
</div>

<h3 style="margin-top:30px;">🔥 Artikel Terpopuler</h3>

<div class="cards">
    <?php while($p = mysqli_fetch_assoc($q_populer)): ?>
    <div class="card">

        <!-- GAMBAR -->
        <?php
        $file = "../gambar/" . $p['gambar'];
        if (!empty($p['gambar']) && file_exists($file)): ?>
            <img src="../gambar/<?= htmlspecialchars($p['gambar']) ?>" 
                 style="width:100%; height:150px; object-fit:cover; border-radius:10px;">
        <?php else: ?>
            <img src="../gambar/default.png" 
                 style="width:100%; height:150px; object-fit:cover; border-radius:10px;">
        <?php endif; ?>

        <!-- JUDUL -->
        <h4 style="margin-top:10px;">
            <?= htmlspecialchars($p['judul']) ?>
        </h4>

        <!-- LIKE -->
        <p style="color:#888; font-size:14px;">
            ❤️ <?= $p['total_like'] ?> Like
        </p>

        <!-- LINK -->
        <a href="../detail.php?id=<?= $p['id_artikel'] ?>&from=admin">
            Lihat Artikel →
        </a>

    </div>
<?php endwhile; ?>
</div>
</div>

<!-- SCRIPT TOGGLE -->
<script>
function toggleSidebar() {
    let sidebar = document.getElementById("sidebar");
    let main = document.getElementById("main");

    sidebar.classList.toggle("hide");
    main.classList.toggle("full");
}
</script>