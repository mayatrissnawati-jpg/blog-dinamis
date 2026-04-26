<?php
include "../config/auth.php";
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_SESSION['id_user'];

// Ambil data user
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id'");
$user = mysqli_fetch_assoc($query);

// PROSES UPDATE
if (isset($_POST['update'])) {

    $nama  = $_POST['nama'];
    $email = $_POST['email'];

    // =====================
    // UPLOAD FOTO
    // =====================
    $foto = $user['foto'];

    if (!empty($_FILES['foto']['name'])) {

        $namaFile = time() . "_" . $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];

        move_uploaded_file($tmp, "../gambar/" . $namaFile);

        $foto = $namaFile;
    }

    // UPDATE DATA
    mysqli_query($koneksi, "
        UPDATE users 
        SET nama='$nama', email='$email', foto='$foto'
        WHERE id_user='$id'
    ");

    echo "<script>alert('Profil berhasil diupdate!');window.location='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Profil</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #eef2ff, #dbeafe, #f8fafc);
}

.box {
    max-width: 500px;
    margin: 80px auto;
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
}

h3 {
    text-align: center;
}

img {
    display: block;
    margin: 0 auto 15px;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #6366f1;
}

input {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    border-radius: 10px;
    border: 1px solid #ddd;
}

.btn {
    width: 100%;
    margin-top: 15px;
    padding: 10px;
    border-radius: 10px;
    border: none;
    color: white;
    cursor: pointer;
}

.save {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
}

.back {
    background: #6b7280;
    text-align: center;
    display: block;
    text-decoration: none;
    line-height: 35px;
}
</style>
</head>

<body>

<div class="box">

<h3>Edit Profil</h3>

<?php
$foto = (!empty($user['foto']) && file_exists("../gambar/" . $user['foto']))
    ? $user['foto']
    : 'default.png';
?>

<img src="../gambar/<?= htmlspecialchars($foto) ?>">

<form method="POST" enctype="multipart/form-data">

<input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>

<input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

<input type="file" name="foto">

<button type="submit" name="update" class="btn save">Simpan Perubahan</button>

</form>

<a href="index.php" class="btn back">Kembali</a>

</div>

</body>
</html>