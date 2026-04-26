<?php
include "../../config/koneksi.php";

// FUNCTION FORMAT TANGGAL
function tanggalIndo($datetime) {
    if (!$datetime) return '-';

    $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    $timestamp = strtotime($datetime);

    return $hari[date('w', $timestamp)] . ", " .
           date('d', $timestamp) . " " .
           $bulan[date('n', $timestamp)-1] . " " .
           date('Y H:i', $timestamp);
}

$query = mysqli_query($koneksi, "SELECT * FROM komentar ORDER BY id_komentar DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Komentar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f4f4f9;
    font-family: 'Segoe UI', sans-serif;
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

.btn-back {
    background: white;
    color: #9370DB;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
}

/* CONTAINER */
.container-custom {
    max-width: 900px;
    margin: 30px auto;
}

/* CARD */
.card {
    border-radius: 12px;
    border: none;
}

/* STATUS */
.badge.bg-warning {
    background-color: #facc15 !important;
}
.badge.bg-success {
    background-color: #22c55e !important;
}
</style>
</head>

<body>

<div class="header">
    <h2>💬 Manajemen Komentar</h2>
    <a href="../dashboard.php" class="btn-back">← Kembali</a>
</div>

<div class="container-custom">

<?php if (isset($_GET['msg'])) { ?>
    <div class="alert alert-success text-center">
        <?= htmlspecialchars($_GET['msg']) ?>
    </div>
<?php } ?>

<?php if (mysqli_num_rows($query) == 0) { ?>
    <div class="alert alert-info text-center">
        Belum ada komentar
    </div>
<?php } ?>

<?php while ($d = mysqli_fetch_assoc($query)) { ?>
    <div class="card mb-3 shadow-sm">
        <div class="card-body">

            <!-- NAMA + TANGGAL -->
            <div class="mb-2 text-muted" style="font-size: 13px;">
                <strong><?= htmlspecialchars($d['nama'] ?? 'Anonim') ?></strong> • 
                <?= tanggalIndo($d['tanggal'] ?? '') ?>
            </div>

            <!-- KOMENTAR -->
            <p class="card-text">
                <?= htmlspecialchars($d['komentar']) ?>
            </p>

            <!-- FOOTER -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <!-- STATUS -->
                <div>
                    <?php if ($d['status'] == 'pending') { ?>
                        <span class="badge bg-warning text-dark">Pending</span>
                    <?php } else { ?>
                        <span class="badge bg-success">Approved</span>
                    <?php } ?>
                </div>

                <!-- AKSI -->
                <div>
                    <?php if ($d['status'] == 'pending') { ?>
                        <a href="proses.php?approve=<?= $d['id_komentar'] ?>" 
                           class="btn btn-success btn-sm"
                           onclick="return confirm('Setujui komentar ini?')">
                           ✔ Approve
                        </a>
                    <?php } ?>

                    <a href="proses.php?hapus=<?= $d['id_komentar'] ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Yakin ingin menghapus komentar ini?')">
                       🗑 Hapus
                    </a>
                </div>

            </div>

        </div>
    </div>
<?php } ?>

</div>

</body>
</html>