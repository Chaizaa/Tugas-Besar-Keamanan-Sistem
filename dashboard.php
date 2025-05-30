<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

// Ambil informasi lengkap user dari database
$stmt = $pdo->prepare("SELECT username, email, fullname FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$username = htmlspecialchars($user['username']);
$email = htmlspecialchars($user['email']);
$fullname = htmlspecialchars($user['fullname']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logout {
            background-color: #f44336;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
        }

        .logout:hover {
            background-color: #d32f2f;
        }
        .user-info {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .user-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Welcome, <?php echo $fullname; ?></h2>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    <div class="user-info">
        <h3>Your Information</h3>
        <p><strong>Username:</strong> <?php echo $username; ?></p>
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <p><strong>Full Name:</strong> <?php echo $fullname; ?></p>
    </div>
    <div>
        <h3>Secure Dashboard</h3>
        <p>This is a secure area that is only accessible after successful login.</p>
        <p>Your login is protected with:</p>
        <ul>
            <li>HTTPS encryption</li>
            <li>Password salting</li>
            <li>Password hashing (SHA-256)</li>
            <li>Protection against SQL injection</li>
            <li>Protection against XSS attacks</li>
            <li>Protection against buffer overflow</li>
        </ul>
    </div>

</body>
</html>

