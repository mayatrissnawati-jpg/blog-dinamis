<?php
$query = mysqli_query($koneksi, "
    SELECT artikel.*, kategori.nama_kategori 
    FROM artikel
    LEFT JOIN kategori ON artikel.id_kategori = kategori.id_kategori
    ORDER BY tanggal DESC
");
?>
<div class="text-center mt-5">
    <h1 class="fw-bold">Selamat Datang di Blog</h1>
    <p class="text-muted">Temukan berbagai artikel menarik</p>
</div>
<h2>Artikel Terbaru</h2>

<?php while ($data = mysqli_fetch_assoc($query)) { ?>
    <div>
        <h3><?= $data['judul'] ?></h3>
        <p>Kategori: <?= $data['nama_kategori'] ?></p>
        <p><?= substr($data['isi'], 0, 100) ?>...</p>
        <a href="?page=artikel&id=<?= $data['id_artikel'] ?>">Baca Selengkapnya</a>
    </div>
<?php } ?>