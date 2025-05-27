<?php
// db.php - Koneksi database menggunakan PDO
$host = 'localhost';
$dbname   = 'phplogin';
$username = 'webuser';
$password = 'Aisyah1103223148#148';

// Create a database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
// Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
