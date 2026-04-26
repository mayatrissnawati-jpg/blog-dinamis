<?php
include "../../config/koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Tag</title>

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
    background: #fff;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 25px rgba(147,112,219,0.15);
}

/* HEADER */
h4 {
    color: #6A5ACD;
    margin: 0;
}

/* INPUT */
.form-control {
    border-radius: 8px;
}

/* LABEL */
label {
    font-weight: 600;
}

/* BUTTON PRIMARY */
.btn-primary {
    background: #9370DB;
    border: none;
    border-radius: 8px;
}

.btn-primary:hover {
    background: #7B68EE;
}

/* BUTTON SECONDARY */
.btn-secondary {
    border-radius: 8px;
    background: #e5e7eb;
    color: #333;
    border: none;
}
</style>
</head>

<body>

<div class="form-box">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>🏷️ Tambah Tag</h4>
        <a href="../dashboard.php" class="btn btn-light btn-sm">← Kembali</a>
    </div>

    <!-- NOTIF -->
    <?php if (isset($_GET['msg'])) { ?>
        <div class="alert alert-light border">
            <?= htmlspecialchars($_GET['msg']) ?>
        </div>
    <?php } ?>

    <!-- FORM -->
    <form method="POST" action="proses.php">

        <div class="mb-3">
            <label class="form-label">Nama Tag</label>
            <input type="text" name="tag" class="form-control"
                   placeholder="Masukkan tag..." required>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" name="simpan" class="btn btn-primary">
                💾 Simpan
            </button>

            <a href="../dashboard.php" class="btn btn-secondary">
                Batal
            </a>
        </div>

    </form>

</div>

</body>
</html>