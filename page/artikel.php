<?php
$id = $_GET['id'];

$query = mysqli_query($koneksi, "
    SELECT artikel.*, kategori.nama_kategori 
    FROM artikel
    LEFT JOIN kategori ON artikel.id_kategori = kategori.id_kategori
    WHERE id_artikel = '$id'
");

$data = mysqli_fetch_assoc($query);
?>

<h2><?= $data['judul'] ?></h2>
<p>Kategori: <?= $data['nama_kategori'] ?></p>
<p><?= $data['isi'] ?></p>