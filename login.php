<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan trim input
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi input tidak kosong
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Nama pengguna dan kata sandi harus diisi.';
        $_SESSION['old_username'] = $username;
        header('Location: index.php');
        exit;
    }

    // Cari pengguna berdasarkan nama pengguna
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Verifikasi password
    if ($user && password_verify($password, $user['password'])) {
        // Login berhasil
        session_regenerate_id(true); // mencegah session fixation
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        // Login gagal
        $_SESSION['error'] = 'Nama pengguna atau kata sandi salah.';
        $_SESSION['old_username'] = $username;
        header('Location: index.php');
        exit;
    }
}
