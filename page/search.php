<?php
$cari = $_GET['cari'];

$query = mysqli_query($koneksi, "
    SELECT * FROM artikel
    WHERE judul LIKE '%$cari%' OR isi LIKE '%$cari%'
");
?>

<h2>Hasil Pencarian</h2>

<?php while ($data = mysqli_fetch_assoc($query)) { ?>
    <div>
        <h3><?= $data['judul'] ?></h3>
        <p><?= substr($data['isi'], 0, 100) ?>...</p>
    </div>
<?php } ?>