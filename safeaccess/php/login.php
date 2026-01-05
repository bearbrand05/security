<?php
require 'security.php';
start_secure_session();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

if (!isset($_POST['csrf']) || !verify_csrf_token($_POST['csrf'])) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid CSRF token'];
    header('Location: ../login.php');
    exit;
}

if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

$username = trim($_POST['username']);
$password = $_POST['password'];
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// Rate limiting per IP and per username
if (is_rate_limited('ip:' . $ip, 20, 300) || is_rate_limited('user:' . $username, 5, 300)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Too many login attempts; try again later'];
    header('Location: ../login.php');
    exit;
}

if ($_SESSION['attempts'] >= 3) {
    if (!isset($_POST['captcha']) || $_POST['captcha'] !== $_SESSION['captcha']) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'CAPTCHA incorrect'];
        header('Location: ../login.php');
        exit;
    }
}

$stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $stored = $row['password'];

    // New-style verification
    if (password_verify($password, $stored)) {
        session_regenerate_id(true);
        $_SESSION['user'] = $username;
        $_SESSION['attempts'] = 0;
        reset_rate_limit('ip:' . $ip);
        reset_rate_limit('user:' . $username);
        log_auth_event("Login success: $username");
        header("Location: dashboard.php");
        exit;
    }

    // Legacy SHA-256 Base64 match? If so, upgrade to password_hash
    elseif (hash_equals($stored, base64_encode(hash('sha256', $password, true)))) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password=? WHERE username=?");
        $update->bind_param("ss", $newHash, $username);
        $update->execute();

        session_regenerate_id(true);
        $_SESSION['user'] = $username;
        $_SESSION['attempts'] = 0;
        reset_rate_limit('ip:' . $ip);
        reset_rate_limit('user:' . $username);
        log_auth_event("Legacy migrated and login success: $username");
        header("Location: dashboard.php");
        exit;
    }
}

// failed login
$_SESSION['attempts']++;
increment_rate_limit('ip:' . $ip);
increment_rate_limit('user:' . $username);
log_auth_event("Login failed for $username");

if ($_SESSION['attempts'] >= 3) {
    $_SESSION['captcha'] = substr(str_shuffle("ABCDEFG123456"), 0, 5);
    // captcha will be shown on next load if frontend supports it
}

$_SESSION['flash'] = ['type' => 'error', 'message' => 'Login failed'];
header('Location: ../login.php');
exit;
