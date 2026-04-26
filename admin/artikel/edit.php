<?php
include "../../config/koneksi.php";

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "
SELECT * FROM artikel WHERE id_artikel='$id'
"));
?>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f4f9;
    margin: 0;
}

/* HEADER */
.header {
    background: linear-gradient(135deg, #9370DB, #7B68EE);
    color: white;
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header h2 {
    margin: 0;
}

.header a {
    background: white;
    color: #9370DB;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    font-size: 13px;
}

/* CONTAINER */
.container {
    display: flex;
    justify-content: center;
    padding: 40px 20px;
}

/* CARD */
.card {
    background: white;
    padding: 25px;
    width: 100%;
    max-width: 520px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

/* FORM GROUP */
.form-group {
    margin-bottom: 15px;
}

/* INPUT */
input, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border-radius: 8px;
    border: 1px solid #ddd;
    outline: none;
    font-size: 14px;
}

input:focus, textarea:focus {
    border-color: #9370DB;
}

/* TEXTAREA */
textarea {
    height: 150px;
    resize: none;
}

/* IMAGE */
.preview-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
    margin-top: 8px;
}

/* BUTTON */
.btn {
    margin-top: 10px;
    padding: 10px;
    width: 100%;
    border: none;
    border-radius: 10px;
    background: #9370DB;
    color: white;
    font-size: 15px;
    cursor: pointer;
    transition: 0.2s;
}

.btn:hover {
    background: #7B68EE;
}

/* LABEL */
label {
    font-weight: 600;
    font-size: 14px;
}
</style>

<!-- HEADER -->
<div class="header">
    <h2>✏️ Edit Artikel</h2>
    <a href="index.php">⬅️ Kembali</a>
</div>

<div class="container">
<div class="card">

<form method="POST" action="proses.php" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?= $data['id_artikel'] ?>">

    <div class="form-group">
        <label>Judul Artikel</label>
        <input type="text" name="judul" value="<?= $data['judul'] ?>" required>
    </div>

    <div class="form-group">
        <label>Isi Artikel</label>
        <textarea name="isi" required><?= $data['isi'] ?></textarea>
    </div>

    <div class="form-group">
        <label>Gambar Saat Ini</label>
        <?php if ($data['gambar']) { ?>
            <img src="/blog/gambar/<?= $data['gambar'] ?>" class="preview-img">
        <?php } else { ?>
            <img src="/blog/gambar/default.png" class="preview-img">
        <?php } ?>
    </div>

    <div class="form-group">
        <label>Ganti Gambar</label>
        <input type="file" name="gambar">
    </div>

    <button name="update" class="btn">💾 Update Artikel</button>

</form>

</div>
</div>