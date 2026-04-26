<?php
include "../../config/koneksi.php";

/* ================= FILTER ================= */
$where = "";
$params = [];
$types = "";

if (isset($_GET['kategori']) && $_GET['kategori'] != '') {
    $where = "WHERE a.id_kategori = ?";
    $params[] = intval($_GET['kategori']);
    $types .= "i";
}

/* ================= QUERY ================= */
$sql = "
    SELECT a.*, u.nama AS penulis, k.nama_kategori
    FROM artikel a
    JOIN users u ON a.id_user = u.id_user
    LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
    $where
    ORDER BY a.id_artikel DESC
";

$stmt = mysqli_prepare($koneksi, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen Artikel</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f1f5f9;
    margin: 0;
}

/* HEADER */
.header {
    background: linear-gradient(90deg, #667eea, #764ba2);
    padding: 18px 25px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* BUTTON */
.btn {
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    font-size: 13px;
}

.edit { background: #3b82f6; }
.hapus { background: #ef4444; }
.approve { background: #22c55e; }
.filter { background: #6366f1; }

/* CONTAINER */
.table-container {
    width: 95%;
    margin: 20px auto;
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    overflow-x: auto;
}

/* FILTER */
.filter-box {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

/* HEADER TABLE */
th {
    background: #6366f1;
    color: white;
    padding: 12px;
    font-size: 14px;
}

/* ISI TABLE */
td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

/* ALIGN */
td:nth-child(3) {
    text-align: left;
}

td:not(:nth-child(3)) {
    text-align: center;
}

/* HOVER */
tr:hover {
    background: #f9f9ff;
}

/* GAMBAR */
.img-thumb {
    width: 80px;
    height: 55px;
    object-fit: cover;
    border-radius: 8px;
}

/* STATUS */
.status-pending {
    color: orange;
    font-weight: 600;
}

.status-publish {
    color: green;
    font-weight: 600;
}

/* AKSI */
.aksi {
    display: flex;
    gap: 6px;
    justify-content: center;
    flex-wrap: wrap;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h2>📊 Manajemen Artikel</h2>
    <a href="../dashboard.php" class="btn edit">← Dashboard</a>
</div>

<div class="table-container">

<!-- FILTER -->
<form method="GET" class="filter-box">
    <select name="kategori">
        <option value="">Semua Kategori</option>
        <?php
        $kat = mysqli_query($koneksi, "SELECT * FROM kategori");
        while ($k = mysqli_fetch_assoc($kat)) {
        ?>
        <option value="<?= $k['id_kategori'] ?>"
            <?= (isset($_GET['kategori']) && $_GET['kategori']==$k['id_kategori']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($k['nama_kategori']) ?>
        </option>
        <?php } ?>
    </select>

    <button type="submit" class="btn filter">Filter</button>
    <a href="index.php" class="btn hapus">Reset</a>
</form>

<!-- TABLE -->
<table>
<thead>
<tr>
    <th>No</th>
    <th>Gambar</th>
    <th>Judul</th>
    <th>Penulis</th>
    <th>Kategori</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>

<?php 
$no = 1;
while ($d = mysqli_fetch_assoc($query)) { 
?>
<tr>

<td><?= $no++ ?></td>

<td>
<?php 
$file = "../../gambar/" . $d['gambar'];
if (!empty($d['gambar']) && file_exists($file)) { ?>
    <img src="/blog/gambar/<?= htmlspecialchars($d['gambar']) ?>" class="img-thumb">
<?php } else { ?>
    <img src="/blog/gambar/default.png" class="img-thumb">
<?php } ?>
</td>

<td><?= htmlspecialchars($d['judul']) ?></td>
<td><?= htmlspecialchars($d['penulis']) ?></td>
<td><?= htmlspecialchars($d['nama_kategori'] ?? '-') ?></td>

<td>
<?php if ($d['status']=='pending') { ?>
    <span class="status-pending">Pending</span>
<?php } else { ?>
    <span class="status-publish">Publish</span>
<?php } ?>
</td>

<td>
<div class="aksi">

<?php if ($d['status']=='pending') { ?>
    <a href="proses.php?approve=<?= $d['id_artikel'] ?>" class="btn approve">✔ Approve</a>
<?php } ?>

<a href="edit.php?id=<?= $d['id_artikel'] ?>" class="btn edit">✏️ Edit</a>

<a href="proses.php?hapus=<?= $d['id_artikel'] ?>" 
   class="btn hapus"
   onclick="return confirm('Yakin hapus artikel ini?')">
   🗑 Hapus
</a>

</div>
</td>

</tr>
<?php } ?>

</tbody>
</table>

</div>

</body>
</html>