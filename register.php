<?php
session_start();
require 'db.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input dari form
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = 'Semua kolom harus diisi.';
    } elseif ($password !== $confirm_password) {
        $error = 'Kata sandi dan konfirmasi harus sama.';
    } else {
        // Cek apakah username sudah ada
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Nama pengguna sudah terdaftar.';
        } else {
            // Insert user baru dengan password ter-hash
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
            if ($insert->execute([$username, $hashed_password])) {
                $success = 'Registrasi berhasil. <a href="index.php">Klik di sini</a> untuk login.';
                // Bersihkan variabel username agar field kosong kembali
                $username = '';
            } else {
                $error = 'Gagal mendaftar. Silakan coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Registrasi</title>
</head>
<body>
    <h2>Registrasi</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <form action="register.php" method="post">
        <label>Nama Pengguna:</label><br>
        <input type="text" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" required><br>
        <label>Kata Sandi:</label><br>
        <input type="password" name="password" required><br>
        <label>Konfirmasi Kata Sandi:</label><br>
        <input type="password" name="confirm_password" required><br>
        <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="index.php">Login di sini</a>.</p>
</body>
</html>
