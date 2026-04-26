<?php
session_start();
include '../config/koneksi.php';
include '../config/auth.php';

// 🔥 WAJIB: hanya admin
cek_role('admin');

// =======================
// FUNGSI HITUNG
// =======================
function hitung($koneksi, $tabel){
    $q = mysqli_query($koneksi,"SELECT COUNT(*) as total FROM $tabel");
    $d = mysqli_fetch_assoc($q);
    return $d['total'];
}

// =======================
// DATA DASAR
// =======================
$jml_artikel  = hitung($koneksi,'artikel');
$jml_kategori = hitung($koneksi,'kategori');
$jml_komentar = hitung($koneksi,'komentar');
$jml_user     = hitung($koneksi,'users');

// =======================
// PENGUNJUNG
// =======================
$today = mysqli_fetch_assoc(mysqli_query($koneksi,"
    SELECT COUNT(*) as total FROM pengunjung WHERE tanggal = CURDATE()
"));
$pengunjung_hari_ini = $today['total'];

$total = mysqli_fetch_assoc(mysqli_query($koneksi,"
    SELECT COUNT(*) as total FROM pengunjung
"));
$total_pengunjung = $total['total'];

// =======================
// KATEGORI POPULER
// =======================
$data_kategori = mysqli_query($koneksi,"
    SELECT k.nama_kategori, COUNT(a.id_artikel) as total
    FROM kategori k
    LEFT JOIN artikel a ON a.id_kategori = k.id_kategori
    GROUP BY k.id_kategori
");

$label_kategori = [];
$value_kategori = [];

while($d = mysqli_fetch_assoc($data_kategori)){
    $label_kategori[] = $d['nama_kategori'];
    $value_kategori[] = $d['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Statistik Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

.container-custom {
    max-width: 1100px;
    margin: 25px auto;
}

/* CARD */
.card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

/* CHART */
.chart-box {
    background: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 20px;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h3>📊 Statistik Website</h3>
    <a href="/blog/admin/dashboard.php" class="btn btn-light btn-sm">← Dashboard</a>
</div>

<div class="container-custom">

<!-- ======================= -->
<!-- CARD SUMMARY -->
<!-- ======================= -->
<div class="row text-center mb-4">

<div class="col-md-3">
<div class="card p-3">
<h4><?= $jml_artikel ?></h4>
<p>Artikel</p>
</div>
</div>

<div class="col-md-3">
<div class="card p-3">
<h4><?= $jml_user ?></h4>
<p>User</p>
</div>
</div>

<div class="col-md-3">
<div class="card p-3">
<h4><?= $pengunjung_hari_ini ?></h4>
<p>Pengunjung Hari Ini</p>
</div>
</div>

<div class="col-md-3">
<div class="card p-3">
<h4><?= $total_pengunjung ?></h4>
<p>Total Pengunjung</p>
</div>
</div>

</div>

<!-- ======================= -->
<!-- BAR CHART -->
<!-- ======================= -->
<div class="chart-box">
    <canvas id="barChart"></canvas>
</div>

<!-- ======================= -->
<!-- PIE CHART KATEGORI -->
<!-- ======================= -->
<div class="chart-box">
    <canvas id="pieChart"></canvas>
</div>

</div>

<script>
// =======================
// BAR DATA
// =======================
const barData = [
    <?= $jml_artikel ?>,
    <?= $jml_kategori ?>,
    <?= $jml_komentar ?>,
    <?= $jml_user ?>,
    <?= $total_pengunjung ?>
];

const barLabels = ['Artikel','Kategori','Komentar','User','Pengunjung'];
// BAR CHART
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: barLabels,
        datasets: [{
            label: 'Data Website',
            data: barData,
            backgroundColor: [
                '#9370DB',
                '#6A5ACD',
                '#BA55D3',
                '#FF8C94'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// =======================
// PIE KATEGORI
// =======================
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($label_kategori) ?>,
        datasets: [{
            data: <?= json_encode($value_kategori) ?>,
            backgroundColor: [
                '#9370DB',
                '#6A5ACD',
                '#BA55D3',
                '#FF8C94',
                '#4CAF50',
                '#FF9800'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

</body>
</html>