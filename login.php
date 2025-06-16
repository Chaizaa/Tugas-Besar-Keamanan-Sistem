<?php
require_once 'db.php';
session_start();

$error_message = '';

// Jika user sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// Jika form dikirim (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    // Ambil data user dari database
    $stmt = $pdo->prepare("SELECT id, username, password_hash, salt FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$login, $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verifikasi password
        $password_hash = hash('sha256', $password . $user['salt']);
        if ($password_hash === $user['password_hash']) {
            // Password benar, buat session
            session_regenerate_id();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Invalid username or password";
        }
    } else {
        $error_message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
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

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
    }

    input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccd6dd;
        border-radius: 8px;
        box-sizing: border-box;
        transition: border 0.3s ease;
    }

    input:focus {
        border-color: #3498db;
        outline: none;
    }

    .error {
        color: #e74c3c;
        background: #fdecea;
        border: 1px solid #f5c6cb;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 6px;
    }

    button {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    button:hover {
        background: linear-gradient(135deg, #2980b9, #2471a3);
    }

    a {
        display: block;
        margin-top: 20px;
        text-align: center;
        text-decoration: none;
        color: #3498db;
    }

    a:hover {
        text-decoration: underline;
    }
    </style>
</head>
<body>

<h2>Login</h2>

<?php if ($error_message): ?>
    <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="saltPassword()">
    <div class="form-group">
        <label for="login">Usernameor Email:</label>
        <input type="text" id="login" name="login" required>
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>

    <button type="submit">Login</button>
</form>

<a href="register.php">Don't have an account? Register here</a>

<script>
// Client-side salting (tambahan keamanan, tidak wajib)
function saltPassword() {
    const clientSalt = "NIM1103223052ClientSalt";
    const passwordField = document.getElementById('password');
    const password = passwordField.value;

    // Hanya contoh - tidak mengubah nilai password
    console.log("Password has been salted on client side");
}
</script>

</body>
</html>

