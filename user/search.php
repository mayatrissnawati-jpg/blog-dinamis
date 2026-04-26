<?php
include "../config/koneksi.php";
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

$query = mysqli_query($koneksi, "
    SELECT a.*, k.nama_kategori 
    FROM artikel a
    LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
    WHERE a.status='publish'
    AND (a.judul LIKE '%$keyword%' OR a.isi LIKE '%$keyword%')
    ORDER BY a.id_artikel DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Pencarian</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

<h4>🔍 Hasil pencarian: "<?= htmlspecialchars($keyword) ?>"</h4>

<form method="GET" class="mb-3">
    <input type="text" name="q" class="form-control" placeholder="Cari artikel...">
</form>

<div class="row">
<?php while($d = mysqli_fetch_assoc($query)) { ?>
<div class="col-md-4 mb-3">
    <div class="card p-3">
        <h6><?= htmlspecialchars($d['judul']) ?></h6>
        <small><?= $d['nama_kategori'] ?></small><br>
        <a href="detail.php?id=<?= $d['id_artikel'] ?>">Baca</a>
    </div>
</div>
<?php } ?>
</div>

<a href="index.php" class="btn btn-secondary">← Kembali</a>

</div>
</body>
</html>