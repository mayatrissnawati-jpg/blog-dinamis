<?php
include "../config/koneksi.php";
session_start();

$id_user = $_SESSION['id_user'];

$query = mysqli_query($koneksi, "
    SELECT a.* 
    FROM bookmark b
    JOIN artikel a ON b.id_artikel = a.id_artikel
    WHERE b.id_user='$id_user'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Bookmark</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

<h4>🔖 Artikel Favorit</h4>

<div class="row">
<?php while($d = mysqli_fetch_assoc($query)) { ?>
<div class="col-md-4 mb-3">
    <div class="card p-3">
        <h6><?= $d['judul'] ?></h6>
        <a href="detail.php?id=<?= $d['id_artikel'] ?>">Baca</a>
    </div>
</div>
<?php } ?>
</div>

<a href="index.php" class="btn btn-secondary">← Kembali</a>

</div>
</body>
</html>