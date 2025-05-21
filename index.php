<?php
session_start();
// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Ambil pesan error dari sesi (jika ada)
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

// Ambil username lama dari sesi (jika ada) agar tetap terisi pada form
$username_value = '';
if (isset($_SESSION['old_username'])) {
    $username_value = htmlspecialchars($_SESSION['old_username'], ENT_QUOTES, 'UTF-8');
    unset($_SESSION['old_username']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
        <label>Nama Pengguna:</label><br>
        <input type="text" name="username" value="<?php echo $username_value; ?>" required><br>
        <label>Kata Sandi:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Belum punya akun? <a href="register.php">Daftar di sini</a>.</p>
</body>
</html>
