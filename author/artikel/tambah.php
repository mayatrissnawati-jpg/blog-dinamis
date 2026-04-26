<?php
include "../../config/koneksi.php";
include "../../config/auth.php";
cek_login();

if ($_SESSION['role'] !== 'author') {
    header("Location: ../../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Artikel</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #eef2ff, #f8fafc);
    font-family: 'Segoe UI', sans-serif;
}

.card-custom {
    border-radius: 18px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    border: none;
}

.header-title {
    color: #6A5ACD;
    font-weight: bold;
}

.form-control {
    border-radius: 10px;
}

.form-control:focus {
    border-color: #9370DB;
    box-shadow: 0 0 6px rgba(147,112,219,0.4);
}

.btn-primary {
    background: #9370DB;
    border: none;
}

.btn-primary:hover {
    background: #7B68EE;
}

label {
    margin-top: 10px;
    font-weight: 500;
}
</style>

<script>
// tombol kembali dinamis
function goBack() {
    if (document.referrer) {
        window.history.back();
    } else {
        window.location.href = 'index.php';
    }
}
</script>

</head>

<body>

<div class="container mt-5">
<div class="col-md-8 mx-auto">

<div class="card card-custom p-4">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="header-title">➕ Tambah Artikel</h4>
    <a href="index.php" class="btn btn-secondary btn-sm">← Kembali</a>
</div>

<?php if (isset($_GET['msg'])): ?>
<div class="alert alert-warning text-center">
    <?= htmlspecialchars($_GET['msg']) ?>
</div>
<?php endif; ?>

<form method="POST" action="proses.php" enctype="multipart/form-data">

<label>Judul Artikel</label>
<input type="text" name="judul" class="form-control" placeholder="Masukkan judul..." required>

<label>Isi Artikel</label>
<textarea name="isi" class="form-control" rows="5" placeholder="Tulis isi artikel..." required></textarea>

<label>Kategori</label>
<select name="id_kategori" class="form-control" required>
    <option value="">-- Pilih Kategori --</option>
    <?php
    $kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
    while ($k = mysqli_fetch_assoc($kategori)) {
        echo "<option value='{$k['id_kategori']}'>{$k['nama_kategori']}</option>";
    }
    ?>
</select>

<label>Tag</label>
<select name="id_tag" class="form-control">
    <option value="">-- Pilih Tag --</option>
    <?php
    $tag = mysqli_query($koneksi, "SELECT * FROM tag");
    while ($t = mysqli_fetch_assoc($tag)) {
        echo "<option value='{$t['id_tag']}'>{$t['nama_tag']}</option>";
    }
    ?>
</select>

<label>Upload Gambar</label>
<input type="file" name="gambar" class="form-control">

<!-- BUTTON -->
<div class="mt-4 d-flex justify-content-between">

    <button type="submit" name="simpan" class="btn btn-primary">
        💾 Simpan Artikel
    </button>

</div>

</form>

</div>
</div>
</div>

</body>
</html>