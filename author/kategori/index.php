<?php
include "../../config/koneksi.php";
include "../../config/auth.php";
cek_login('author');
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Kategori</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #f4f4f9, #e9e6ff);
    font-family: 'Segoe UI', sans-serif;
}

/* CARD */
.form-box {
    max-width: 600px;
    margin: 80px auto;
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 25px rgba(147,112,219,0.15);
}

/* TITLE */
h4 {
    color: #6A5ACD;
}

/* BUTTON */
.btn-primary {
    background: #9370DB;
    border: none;
}
.btn-primary:hover {
    background: #7B68EE;
}
</style>
</head>

<body>

<div class="form-box">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>📂 Tambah Kategori</h4>

        <!-- FIX BACK BUTTON -->
        <a href="../dashboard.php" class="btn btn-light btn-sm">← Kembali</a>
    </div>

    <?php if (isset($_GET['msg'])) { ?>
        <div class="alert alert-light border">
            <?= htmlspecialchars($_GET['msg']) ?>
        </div>
    <?php } ?>

    <form method="POST" action="proses.php">

        <div class="mb-3">
            <label>Nama Kategori</label>
            <input type="text" name="kategori" class="form-control" required>
        </div>

        <button type="submit" name="simpan" class="btn btn-primary">
            💾 Simpan
        </button>

    </form>

</div>

</body>
</html>