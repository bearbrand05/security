<?php
require 'db.php';
require 'security.php';
start_secure_session();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../register.php");
    exit;
}

if (!isset($_POST['csrf']) || !verify_csrf_token($_POST['csrf'])) {
    die("Invalid CSRF token");
}

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (is_rate_limited('ip:' . $ip, 20, 300)) {
    die("Too many requests from your IP; try again later.");
}

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];

if (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $username)) {
    die("Invalid username (3-50 alphanumeric or underscore characters)");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address");
}
if (strlen($password) < 8) {
    die("Password too short (minimum 8 characters)");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $username, $email, $hash);

try {
    $stmt->execute();
    log_auth_event("Registration successful: $username");
    echo "Registration successful. <a href='../login.php'>Login</a>";
} catch (Exception $e) {
    increment_rate_limit('ip:' . $ip);
    log_auth_event("Registration failed for $username: " . $e->getMessage());
    echo "Registration failed: username may already exist.";
}
