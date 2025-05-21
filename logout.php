<?php
session_start();
// Hapus semua data sesi
$_SESSION = array();
// Hapus cookie sesi
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
// Akhiri sesi
session_destroy();
header('Location: index.php');
exit;
