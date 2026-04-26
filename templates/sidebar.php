<div class="sidebar">
    <h2>My Blog</h2>

    <!-- DASHBOARD -->
    <a href="../<?= $_SESSION['role'] ?>/dashboard.php">🏠 Dashboard</a>

    <!-- ADMIN MENU -->
    <?php if ($_SESSION['role'] == 'admin') { ?>
        <a href="../admin/artikel/index.php">📝 Artikel</a>
        <a href="../admin/kategori/index.php">📂 Kategori</a>
        <a href="../admin/user/index.php">👤 User</a>
    <?php } ?>

    <!-- AUTHOR MENU -->
    <?php if ($_SESSION['role'] == 'author') { ?>
        <a href="../author/artikel/index.php">📝 Artikel Saya</a>
    <?php } ?>

    <!-- USER MENU -->
    <?php if ($_SESSION['role'] == 'user') { ?>
        <a href="../user/index.php">🏠 Home</a>
        <a href="../user/artikel.php">📰 Artikel</a>
        <a href="../user/kategori.php">📂 Kategori</a>
    <?php } ?>

    <a href="../auth/logout.php" class="logout">🚪 Logout</a>
</div>