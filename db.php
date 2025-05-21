<?php
// db.php - Koneksi database menggunakan PDO
$host = 'localhost';
$db   = 'phplogin';
$user = 'webuser';
$pass = 'Hafidz1103223052@123';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // aktifkan exception on error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // gunakan prepared statements asli
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Koneksi gagal
    die('Koneksi gagal: ' . $e->getMessage());
}
