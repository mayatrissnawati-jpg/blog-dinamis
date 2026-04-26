<?php
include "../../config/koneksi.php";
include "../../config/auth.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔥 WAJIB: hanya author
cek_role('author');

// =======================
// AMBIL USER
// =======================
$id_user = intval($_SESSION['id_user']);

// =======================
// FILTER
// =======================
$where = "WHERE a.id_user = $id_user";

// 🔍 SEARCH
$cari = trim($_GET['cari'] ?? '');

if ($cari !== '') {
    $cari = mysqli_real_escape_string($koneksi, $cari);
    $where .= " AND LOWER(a.judul) LIKE LOWER('%$cari%')";
}

// 📂 FILTER KATEGORI
if (!empty($_GET['kategori'])) {
    $id_kategori = intval($_GET['kategori']);
    $where .= " AND a.id_kategori = $id_kategori";
}

// =======================
// QUERY
// =======================
$query = mysqli_query($koneksi, "
    SELECT a.*, k.nama_kategori 
    FROM artikel a
    LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
    $where
    ORDER BY a.id_artikel DESC
");

// =======================
// PESAN
// =======================
$msg = $_GET['msg'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
<title>Artikel Saya</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background: #f4f4f9; }

.card {
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

img { border-radius: 6px; }
</style>
</head>

<body>

<div class="container mt-4">
<div class="card p-4">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>📝 Artikel Saya</h4>

    <div>
        <a href="../dashboard.php" class="btn btn-secondary btn-sm">← Dashboard</a>
        <a href="tambah.php" class="btn btn-success btn-sm">+ Tambah Artikel</a>
    </div>
</div>

<!-- NOTIFIKASI -->
<?php if ($msg): ?>
<div class="alert alert-info">
    <?= htmlspecialchars($msg) ?>
</div>
<?php endif; ?>

<!-- FILTER -->
<form method="GET" class="mb-3 d-flex gap-2">

    <!-- 🔍 SEARCH -->
    <input type="text" name="cari" class="form-control"
        placeholder="Cari artikel..."
        value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>">

    <!-- FILTER KATEGORI -->
    <select name="kategori" class="form-control">
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

    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    <a href="index.php" class="btn btn-secondary btn-sm">Reset</a>

</form>

<!-- TABLE -->
<div class="table-responsive">
<table class="table table-bordered table-hover text-center align-middle">

<thead class="table-light">
<tr>
    <th>No</th>
    <th>Gambar</th>
    <th>Judul</th>
    <th>Kategori</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php 
$no = 1;

if (mysqli_num_rows($query) > 0):
while ($d = mysqli_fetch_assoc($query)): 
?>
<tr>

<td><?= $no++ ?></td>

<td>
<?php 
$file = "../../gambar/" . $d['gambar'];

if (!empty($d['gambar']) && file_exists($file)) { ?>
    <img src="/blog/gambar/<?= htmlspecialchars($d['gambar']) ?>" width="70">
<?php } else { ?>
    <img src="/blog/gambar/default.png" width="70">
<?php } ?>
</td>

<td><?= htmlspecialchars($d['judul']) ?></td>

<td>
<?= !empty($d['nama_kategori']) 
    ? htmlspecialchars($d['nama_kategori']) 
    : '<span class="text-muted">-</span>' ?>
</td>

<td>
<?= ($d['status']=='pending') 
    ? '<span class="badge bg-warning">Pending</span>' 
    : '<span class="badge bg-success">Publish</span>' ?>
</td>

<td>
    <a href="edit.php?id=<?= $d['id_artikel'] ?>" class="btn btn-primary btn-sm">Edit</a>
    <a href="hapus.php?id=<?= $d['id_artikel'] ?>" 
       class="btn btn-danger btn-sm"
       onclick="return confirm('Yakin hapus?')">Hapus</a>
</td>

</tr>
<?php 
endwhile;
else:
?>
<tr>
<td colspan="6" class="text-center text-muted">
    Belum ada artikel
</td>
</tr>
<?php endif; ?>
</tbody>

</table>
</div>

</div>
</div>

</body>
</html>