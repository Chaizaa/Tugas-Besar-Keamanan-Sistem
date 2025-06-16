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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            color: #333;
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        .user-info {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 1px solid #e0e0e0;
        }

        .user-info p {
            margin: 10px 0;
        }

        .logout {
            display: block;
            text-align: center;
            padding: 12px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 15px;
            transition: background 0.3s ease;
        }

        .logout:hover {
            background: linear-gradient(135deg, #c0392b, #a93226);
        }

        .info-box {
            text-align: center;
            font-weight: 500;
            color: #2c3e50;
            background: #ecf0f1;
            padding: 15px;
            border-radius: 10px;
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
        <h3>Ini adalah website yang sudah dijamin keamanannya</h3>
    </div>

</body>
</html>

