<?php
require 'db.php';
require 'security.php';
start_secure_session();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../register.php");
    exit;
}

if (!isset($_POST['csrf']) || !verify_csrf_token($_POST['csrf'])) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid CSRF token'];
    header('Location: ../register.php');
    exit;
}

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (is_rate_limited('ip:' . $ip, 20, 300)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Too many requests from your IP; try again later.'];
    header('Location: ../register.php');
    exit;
}

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];

if (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $username)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid username (3-50 alphanumeric or underscore characters)'];
    header('Location: ../register.php');
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid email address'];
    header('Location: ../register.php');
    exit;
}
if (strlen($password) < 8) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Password too short (minimum 8 characters)'];
    header('Location: ../register.php');
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $username, $email, $hash);

try {
    $stmt->execute();
    log_auth_event("Registration successful: $username");
    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Registration successful. You can now sign in.'];
    header('Location: ../login.php');
    exit;
} catch (Exception $e) {
    increment_rate_limit('ip:' . $ip);
    log_auth_event("Registration failed for $username: " . $e->getMessage());
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Registration failed: username may already exist.'];
    header('Location: ../register.php');
    exit;
}
