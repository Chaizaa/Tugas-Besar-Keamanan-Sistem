<?php
require_once 'db.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        $error_message = "Username already exists";
    } else {
        // Generate a random salt
        $salt = bin2hex(random_bytes(32));

        // Hash the password with the salt
        $password = $_POST['password'];
        $password_hash = hash('sha256', $password . $salt);

        // Insert new user into database
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, salt) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $password_hash, $salt]);
            $success_message = "Registration successful! You can now login.";
        } catch (PDOException $e) {
            $error_message = "Registration failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial; max-width: 600px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; }
        input { width: 100%; padding: 8px; }
        .error { color: red; }
        .success { color: green; }
        button { padding: 10px 15px; background: green; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Register</h2>

    <?php if ($error_message): ?>
        <div class="error"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Register</button>
    </form>

    <a href="login.php">Already have an account? Login</a>
</body>
</html>
