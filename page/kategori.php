<?php
// Ambil semua kategori
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori");

// Jika klik kategori
$id_kat = $_GET['id'] ?? null;
?>

<div class="row">

    <!-- SIDEBAR KATEGORI -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                📂 Kategori
            </div>
            <ul class="list-group list-group-flush">
                <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                    <li class="list-group-item">
                        <a href="?page=kategori&id=<?= $k['id_kategori'] ?>" 
                           class="text-decoration-none">
                           <?= htmlspecialchars($k['nama_kategori']) ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <!-- ARTIKEL -->
    <div class="col-md-8">

        <?php if ($id_kat) { ?>

            <h4 class="mb-3">📰 Artikel dalam Kategori</h4>

            <?php
            $artikel = mysqli_query($koneksi, "
                SELECT * FROM artikel 
                WHERE id_kategori='$id_kat' AND status='publish'
                ORDER BY tanggal DESC
            ");

            if (mysqli_num_rows($artikel) > 0) {
                while ($a = mysqli_fetch_assoc($artikel)) {
            ?>

            <div class="card mb-3 shadow-sm">
                <div class="row g-0">
                    
                    <!-- GAMBAR -->
                    <div class="col-md-4">
                        <?php 
                        $file = "gambar/" . $a['gambar'];
                        if (!empty($a['gambar']) && file_exists($file)) { ?>
                            <img src="/blog/gambar/<?= $a['gambar'] ?>" 
                                 class="img-fluid rounded-start" 
                                 style="height:100%; object-fit:cover;">
                        <?php } else { ?>
                            <img src="/blog/gambar/default.png" 
                                 class="img-fluid rounded-start">
                        <?php } ?>
                    </div>

                    <!-- KONTEN -->
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?= htmlspecialchars($a['judul']) ?>
                            </h5>

                            <p class="card-text text-muted small">
                                <?= date('d M Y', strtotime($a['tanggal'])) ?>
                            </p>

                            <p class="card-text">
                                <?= substr(strip_tags($a['isi']), 0, 100) ?>...
                            </p>

                            <a href="?page=artikel&id=<?= $a['id_artikel'] ?>" 
                               class="btn btn-sm btn-primary">
                               Baca Selengkapnya →
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <?php 
                }
            } else {
                echo "<div class='alert alert-warning'>Belum ada artikel di kategori ini</div>";
            }
            ?>

        <?php } else { ?>

            <div class="alert alert-info">
                👈 Silakan pilih kategori untuk melihat artikel
            </div>

        <?php } ?>

    </div>

</div>