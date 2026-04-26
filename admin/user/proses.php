<?php
session_start();
include "../../config/koneksi.php";
include "../../config/auth.php";

cek_login('admin');

// cek parameter
if (isset($_GET['hapus'])) {

    $id = intval($_GET['hapus']); // amankan input

    // ❌ tidak boleh hapus diri sendiri
    if ($id == $_SESSION['id_user']) {
        echo "
        <script>
            alert('❌ Tidak bisa menghapus akun sendiri!');
            window.location='index.php';
        </script>";
        exit;
    }

    // cek user ada atau tidak
    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user='$id'");

    if (mysqli_num_rows($cek) > 0) {

        mysqli_query($koneksi, "DELETE FROM user WHERE id_user='$id'");

        echo "
        <script>
            alert('✅ User berhasil dihapus!');
            window.location='index.php';
        </script>";

    } else {
        echo "
        <script>
            alert('⚠️ User tidak ditemukan!');
            window.location='index.php';
        </script>";
    }

} else {
    header("Location: index.php");
}
?>