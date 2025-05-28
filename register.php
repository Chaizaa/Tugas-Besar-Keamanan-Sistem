<?php
require_once 'db.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format";
    }
    // Validate fullname length (assuming max 100 chars in DB)
    elseif (strlen($fullname) > 100) {
        $error_message = "Fullname must be 100 characters or less";
    } else {
    // Check if username or already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetchColumn() > 0) {
        $error_message = "Username or email already exists";
    } else {
        // Generate a random salt
        $salt = bin2hex(random_bytes(32));

        // Hash the password with the salt
        $password = $_POST['password'];
        $password_hash = hash('sha256', $password . $salt);

        // Insert new user into database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, fullname, password_hash, salt) VALUES (?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $fullname, $password_hash, $salt]);
            $success_message = "Registration successful! You can now login.";
        } catch (PDOException $e) {
            $error_message = "Registration failed: " . $e->getMessage();
        }
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
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        .error { color: red; margin-bottom: 15px; }
        .success { color: green; margin-bottom: 15px;}
        button { padding: 10px 15px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        a { display: inline-block; margin-top: 15px; }
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
            <input type="text" name="username" required maxlength= "50">
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required maxlength="100">
        </div>
        <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="fullname" required maxlength="100">
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
