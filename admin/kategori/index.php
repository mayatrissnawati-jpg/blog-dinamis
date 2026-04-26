<?php
include "../../config/koneksi.php";
include "../../config/auth.php";
cek_login('admin');

// MODE EDIT
$edit = false;
if (isset($_GET['edit'])) {
    $edit = true;
    $id_edit = $_GET['edit'];
    $data_edit = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT * FROM kategori WHERE id_kategori='$id_edit'
    "));
}

// DATA KATEGORI
$query = mysqli_query($koneksi, "SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html>
<head>
<title>Kategori</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f4f9;
    margin: 0;
}

/* HEADER */
.header {
    background: #9370DB;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header a {
    background: white;
    color: #9370DB;
    padding: 8px 12px;
    border-radius: 8px;
    text-decoration: none;
}

/* CONTAINER */
.container {
    padding: 20px;
}

/* CARD */
.card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

/* INPUT */
input {
    padding: 10px;
    width: 250px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

button {
    padding: 10px 15px;
    background: #9370DB;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

button:hover {
    background: #7B68EE;
}

/* TABLE */
.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

th {
    background: #6A5ACD;
    color: white;
    padding: 14px;
    font-size: 14px;
    text-align: center;
}

td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

td:nth-child(1) {
    text-align: center;
    width: 60px;
}

td:nth-child(3) {
    text-align: center;
    width: 180px;
}

tr:hover {
    background: #f5f5ff;
}

/* BUTTON */
.btn {
    padding: 6px 10px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    font-size: 12px;
    margin: 2px;
    display: inline-block;
}

.btn-edit { background: #3498db; }
.btn-hapus { background: #e74c3c; }

/* EMPTY */
.empty {
    text-align: center;
    padding: 20px;
    color: #888;
}

/* FORM */
.form-flex {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.form-flex input {
    flex: 1;
    min-width: 200px;
}

.form-flex button {
    white-space: nowrap;
}

/* BUTTON GROUP AKSI */
.aksi {
    display: flex;
    justify-content: center;
    gap: 5px;
    flex-wrap: wrap;
}

/* RESPONSIVE */
@media (max-width: 600px) {

    .header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .form-flex {
        flex-direction: column;
    }

    .form-flex button {
        width: 100%;
    }

    .aksi {
        flex-direction: column;
    }

    .aksi .btn {
        width: 100%;
        text-align: center;
    }

    table {
        font-size: 13px;
    }
}
</style>

</head>
<body>

<!-- HEADER -->
<div class="header">
    <h2>📂 Manajemen Kategori</h2>
    <a href="../dashboard.php">⬅️ Dashboard</a>
</div>

<div class="container">

<!-- FORM -->
<div class="card">
    <form method="POST" action="proses.php" class="form-flex">

        <?php if ($edit) { ?>
            <input type="hidden" name="id" value="<?= $data_edit['id_kategori'] ?>">
            <input type="text" name="kategori" value="<?= $data_edit['nama_kategori'] ?>" required>
            <button name="update">✏️ Update</button>
            <a href="index.php">Batal</a>
        <?php } else { ?>
            <input type="text" name="kategori" placeholder="Nama kategori..." required>
            <button name="simpan">+ Tambah</button>
        <?php } ?>

    </form>
</div>

<!-- TABEL -->
<div class="table-wrapper">
<table>

<tr>
    <th>No</th>
    <th>Nama Kategori</th>
    <th>Aksi</th>
</tr>

<?php 
$no = 1;
if (mysqli_num_rows($query) > 0) {
    while ($d = mysqli_fetch_assoc($query)) { 
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $d['nama_kategori'] ?></td>
    <td>
    <div class="aksi">
        <a href="?edit=<?= $d['id_kategori'] ?>" class="btn btn-edit">Edit</a>

        <a href="proses.php?hapus=<?= $d['id_kategori'] ?>" 
           class="btn btn-hapus"
           onclick="return confirm('Yakin hapus kategori?')">
           Hapus
        </a>
    </div>
</td>
</tr>
<?php 
    }
} else {
?>
<tr>
    <td colspan="3" class="empty">Belum ada kategori</td>
</tr>
<?php } ?>

</table>
</div>

</div>

</body>
</html>