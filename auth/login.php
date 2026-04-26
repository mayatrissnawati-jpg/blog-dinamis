<?php 
session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<style>
body {
    background: linear-gradient(135deg, #E6E6FA, #D8BFD8);
    font-family: 'Segoe UI', sans-serif;
}

/* CONTAINER */
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* BOX LOGIN */
.box {
    width: 360px;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* TITLE */
h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #6A5ACD;
}

/* INPUT */
.form-group {
    margin-bottom: 12px;
}

input, select {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

input:focus, select:focus {
    border-color: #9370DB;
    outline: none;
    box-shadow: 0 0 5px rgba(147,112,219,0.5);
}

/* CAPTCHA */
.captcha-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    background: #f9f9ff;
    padding: 10px;
    border-radius: 10px;
}

.captcha-box img {
    border-radius: 10px;
    border: 2px solid #ddd;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* REFRESH */
.refresh {
    font-size: 13px;
    color: #6A5ACD;
    cursor: pointer;
}

.refresh:hover {
    text-decoration: underline;
}

/* BUTTON */
button {
    width: 100%;
    padding: 12px;
    background: #9370DB;
    border: none;
    color: white;
    margin-top: 15px;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #7B68EE;
}

/* ERROR */
.error {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}
</style>

<script>
function refreshCaptcha() {
    document.getElementById("captcha-img").src = "captcha.php?" + Date.now();
}
</script>

</head>

<body>

<div class="container">
<div class="box">

<h2>🔐 Login Blog</h2>

<?php if (isset($_GET['error'])) { ?>
    <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
<?php } ?>

<form method="POST" action="proses_login.php">

<div class="form-group">
    <input type="text" name="username" placeholder="Username" required>
</div>

<div class="form-group">
    <input type="password" name="password" placeholder="Password" required>
</div>

<div class="form-group">
    <select name="role" required>
        <option value="">-- Login sebagai --</option>
        <option value="admin">Admin</option>
        <option value="author">Author</option>
        <option value="user">User</option>
    </select>
</div>

<!-- CAPTCHA -->
<div class="form-group">
    <div class="captcha-box">
        <img src="captcha.php" id="captcha-img">
        <span class="refresh" onclick="refreshCaptcha()">🔄</span>
    </div>
</div>

<div class="form-group">
    <input type="text" name="captcha" placeholder="" required>
</div>

<button type="submit">Login</button>

</form>

<p style="text-align:center; margin-top:15px;">
Belum punya akun? <a href="register.php">Daftar</a>
</p>

</div>
</div>

</body>
</html>