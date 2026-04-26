<?php
include "../../config/auth.php";
include "../../config/koneksi.php";
cek_login('author');

$id_user = $_SESSION['id_user'] ?? 0;

// ================= KOMENTAR UTAMA =================
$stmt = mysqli_prepare($koneksi, "
    SELECT k.*, a.judul
    FROM komentar k
    JOIN artikel a ON k.id_artikel = a.id_artikel
    WHERE a.id_user=? AND k.parent_id IS NULL
    ORDER BY k.id_komentar DESC
");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Komentar Author</title>

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

.btn-back {
    background: white;
    color: #9370DB;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
}

/* CONTAINER */
.container {
    max-width: 800px;
    margin: 30px auto;
    padding: 0 15px;
}

/* CARD */
.card {
    background: white;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

/* BALASAN */
.reply {
    margin-left: 30px;
    margin-top: 10px;
    background: #f9f9f9;
    padding: 10px;
    border-radius: 8px;
}

/* FORM */
textarea {
    width: 100%;
    padding: 8px;
    margin-top: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

button {
    margin-top: 8px;
    padding: 6px 12px;
    background: #6A5ACD;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #5848c2;
}

/* TEXT */
small {
    color: #888;
}
</style>

</head>
<body>

<!-- HEADER -->
<div class="header">
    <h2>💬 Komentar Masuk</h2>
    <a href="../dashboard.php" class="btn-back">← Kembali</a>
</div>

<div class="container">

<?php if (mysqli_num_rows($result) == 0) { ?>
    <p>Belum ada komentar.</p>
<?php } ?>

<?php while($k = mysqli_fetch_assoc($result)) { ?>

<div class="card">

    <!-- INFO USER -->
    <b><?= htmlspecialchars($k['nama'] ?? 'User') ?></b><br>
    <small>Artikel: <?= htmlspecialchars($k['judul']) ?></small>

    <!-- ISI -->
    <p><?= htmlspecialchars($k['komentar']) ?></p>

    <!-- TANGGAL -->
    <?php if (!empty($k['created_at'])) { ?>
        <small>
            <?= date('d M Y H:i', strtotime($k['created_at'])) ?>
        </small>
    <?php } ?>

    <!-- ================= BALASAN ================= -->
    <?php
    $stmtBalas = mysqli_prepare($koneksi, "
        SELECT * FROM komentar WHERE parent_id=?
    ");
    mysqli_stmt_bind_param($stmtBalas, "i", $k['id_komentar']);
    mysqli_stmt_execute($stmtBalas);
    $resultBalas = mysqli_stmt_get_result($stmtBalas);

    while($r = mysqli_fetch_assoc($resultBalas)) {
    ?>
        <div class="reply">
            <b style="color:#6A5ACD;">Author</b>
            <p><?= htmlspecialchars($r['komentar']) ?></p>

            <?php if (!empty($r['created_at'])) { ?>
                <small>
                    <?= date('d M Y H:i', strtotime($r['created_at'])) ?>
                </small>
            <?php } ?>
        </div>
    <?php } ?>

    <!-- ================= FORM BALAS ================= -->
    <form action="proses.php" method="POST">
        <input type="hidden" name="id_artikel" value="<?= $k['id_artikel'] ?>">
        <input type="hidden" name="parent_id" value="<?= $k['id_komentar'] ?>">
        
        <textarea name="komentar" placeholder="Balas komentar..." required></textarea>
        <button type="submit">Balas</button>
    </form>

</div>

<?php } ?>

</div>

</body>
</html>