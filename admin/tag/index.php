<?php
include "../../config/koneksi.php";

// MODE EDIT
$edit = false;

if (isset($_GET['edit'])) {
    $edit = true;
    $id_edit = $_GET['edit'];

    $data_edit = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT * FROM tag WHERE id_tag='$id_edit'
    "));
}

// DATA TAG
$query = mysqli_query($koneksi, "SELECT * FROM tag");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manajemen Tag</title>

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

.header a {
    background: white;
    color: #9370DB;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
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

/* FORM */
.form-flex {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.form-flex input {
    flex: 1;
    min-width: 200px;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.form-flex button {
    padding: 10px 15px;
    background: #9370DB;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.form-flex button:hover {
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
    padding: 12px;
    text-align: center;
}

td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

td:nth-child(1) {
    text-align: center;
    width: 60px;
}

td:nth-child(3) {
    text-align: center;
}

/* AKSI */
.aksi {
    display: flex;
    justify-content: center;
    gap: 5px;
    flex-wrap: wrap;
}

.btn-edit {
    background: #3498db;
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
}

.btn-hapus {
    background: #ef4444;
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
}

.btn-hapus:hover {
    background: #dc2626;
}

/* RESPONSIVE */
@media (max-width: 600px) {
    .form-flex {
        flex-direction: column;
    }

    .form-flex button {
        width: 100%;
    }

    .aksi {
        flex-direction: column;
    }

    .aksi a {
        width: 100%;
        text-align: center;
    }
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h2>🏷️ Manajemen Tag</h2>
    <a href="../dashboard.php">⬅️ Dashboard</a>
</div>

<div class="container">

<!-- FORM -->
<div class="card">
    <form method="POST" action="proses.php" class="form-flex">

        <?php if ($edit) { ?>
            <input type="hidden" name="id" value="<?= $data_edit['id_tag'] ?>">
            <input type="text" name="tag" value="<?= $data_edit['nama_tag'] ?>" required>
            <button name="update">✏️ Update</button>
        <?php } else { ?>
            <input type="text" name="tag" placeholder="Nama tag..." required>
            <button name="simpan">+ Tambah</button>
        <?php } ?>

    </form>
</div>

<!-- TABLE -->
<div class="table-wrapper">
<table>
<tr>
    <th>No</th>
    <th>Nama Tag</th>
    <th>Aksi</th>
</tr>

<?php 
$no = 1;
if (mysqli_num_rows($query) > 0) {
    while ($d = mysqli_fetch_assoc($query)) { 
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $d['nama_tag'] ?></td>
    <td>
        <div class="aksi">
            <a href="?edit=<?= $d['id_tag'] ?>" class="btn-edit">Edit</a>

            <a href="proses.php?hapus=<?= $d['id_tag'] ?>" 
               class="btn-hapus"
               onclick="return confirm('Yakin hapus tag?')">
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
    <td colspan="3" style="text-align:center; padding:20px; color:#888;">
        Belum ada tag
    </td>
</tr>
<?php } ?>

</table>
</div>

</div>

</body>
</html>