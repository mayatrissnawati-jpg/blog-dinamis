<?php
include "../../config/koneksi.php";
include "../../config/auth.php";
cek_login();

if ($_SESSION['role'] !== 'author') {
    header("Location: ../../index.php");
    exit;
}

$id = intval($_GET['id']);

// ambil data artikel
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT * FROM artikel WHERE id_artikel='$id'
"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Artikel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">
<div class="card p-4">

<h4>✏️ Edit Artikel</h4>

<?php if (isset($_GET['msg'])): ?>
<div class="alert alert-warning"><?= htmlspecialchars($_GET['msg']) ?></div>
<?php endif; ?>

<form method="POST" action="proses.php" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $data['id_artikel'] ?>">

<label>Judul</label>
<input type="text" name="judul" class="form-control"
       value="<?= htmlspecialchars($data['judul']) ?>" required>

<label class="mt-2">Isi</label>
<textarea name="isi" class="form-control" rows="5" required>
<?= htmlspecialchars($data['isi']) ?>
</textarea>

<label class="mt-2">Kategori</label>
<select name="id_kategori" class="form-control" required>

<?php
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
while ($k = mysqli_fetch_assoc($kategori)) {
?>
<option value="<?= $k['id_kategori'] ?>"
    <?= ($k['id_kategori'] == $data['id_kategori']) ? 'selected' : '' ?>>
    <?= htmlspecialchars($k['nama_kategori']) ?>
</option>
<?php } ?>

</select>

<label class="mt-2">Tag</label>
<select name="id_tag" class="form-control">

<option value="">-- Pilih Tag --</option>

<?php
$tag = mysqli_query($koneksi, "SELECT * FROM tag");
while ($t = mysqli_fetch_assoc($tag)) {
?>
<option value="<?= $t['id_tag'] ?>"
    <?= ($t['id_tag'] == $data['id_tag']) ? 'selected' : '' ?>>
    <?= htmlspecialchars($t['nama_tag']) ?>
</option>
<?php } ?>

</select>

<label class="mt-2">Gambar</label><br>

<?php if (!empty($data['gambar'])): ?>
    <img src="/blog/gambar/<?= $data['gambar'] ?>" width="120"><br>
<?php endif; ?>

<input type="file" name="gambar" class="form-control mt-2">

<div class="mt-3">
    <button type="submit" name="update" class="btn btn-primary">Update</button>
    <a href="index.php" class="btn btn-secondary">Kembali</a>
</div>

</form>

</div>
</div>

</body>
</html>