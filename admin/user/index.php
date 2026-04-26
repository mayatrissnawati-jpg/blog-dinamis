<?php
include "../../config/koneksi.php";
include "../../config/auth.php";


$query = mysqli_query($koneksi, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manajemen User</title>

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
    text-align: center;
}

td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    text-align: center;
}

tr:hover {
    background: #f5f5ff;
}

/* ROLE BADGE */
.role {
    padding: 5px 10px;
    border-radius: 8px;
    color: white;
    font-size: 12px;
}

.admin { background: #e74c3c; }
.author { background: #3498db; }
.user { background: #2ecc71; }

/* BUTTON */
.btn-hapus {
    background: #e74c3c;
    padding: 6px 10px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    font-size: 12px;
}

/* EMPTY */
.empty {
    padding: 20px;
    color: #888;
}
</style>

</head>
<body>

<!-- HEADER -->
<div class="header">
    <h2>👤 Manajemen User</h2>
    <a href="../dashboard.php">⬅️ Dashboard</a>
</div>

<div class="container">

<div class="table-wrapper">
<table>

<tr>
    <th>No</th>
    <th>Username</th>
    <th>Nama</th>
    <th>Email</th>
    <th>Role</th>
    <th>Aksi</th>
</tr>

<?php 
$no = 1;
if (mysqli_num_rows($query) > 0) {
    while ($d = mysqli_fetch_assoc($query)) { 
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $d['username'] ?></td>

    <td><?= $d['nama'] ?></td>
    <td><?= $d['email'] ?></td>
    <td>
        <span class="role <?= $d['role'] ?>">
            <?= $d['role'] ?>
        </span>
    </td>

    <td>
        <a href="proses.php?hapus=<?= $d['id_user'] ?>" 
           class="btn-hapus"
           onclick="return confirm('Yakin hapus user?')">
           Hapus
        </a>
    </td>
</tr>
<?php 
    }
} else {
?>
<tr>
    <td colspan="4" class="empty">Belum ada user</td>
</tr>
<?php } ?>

</table>
</div>

</div>

</body>
</html>