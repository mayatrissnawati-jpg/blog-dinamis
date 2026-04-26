<?php
include "../config/koneksi.php";
session_start();

$error = "";

if (isset($_POST['register'])) {

    $nama     = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if ($nama == '' || $username == '' || $email == '' || $password == '') {
        $error = "Semua field wajib diisi!";
    } elseif ($password !== $confirm) {
        $error = "Konfirmasi password tidak cocok!";
    } else {

        $cek = mysqli_query($koneksi, "
            SELECT * FROM users 
            WHERE username='$username' OR email='$email'
        ");

        if (mysqli_num_rows($cek) > 0) {
            $error = "Username atau email sudah digunakan!";
        } else {

            mysqli_query($koneksi, "
                INSERT INTO users (nama, username, email, password, role)
                VALUES ('$nama', '$username', '$email', '$password', 'user')
            ");

            header("Location: login.php?msg=Berhasil daftar, silakan login");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#9370DB">

<div class="container mt-5">
<div class="col-md-4 mx-auto bg-white p-4 rounded">

<h4 class="text-center">Daftar</h4>

<?php if ($error) { ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<form method="POST">

<input type="text" name="nama" class="form-control mb-2" placeholder="Nama Lengkap" required>
<input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
<input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
<input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
<input type="password" name="confirm" class="form-control mb-3" placeholder="Konfirmasi Password" required>

<button type="submit" name="register" class="btn btn-primary w-100">Daftar</button>

</form>

<p class="text-center mt-3">
Sudah punya akun? <a href="login.php">Login</a>
</p>

</div>
</div>

</body>
</html>