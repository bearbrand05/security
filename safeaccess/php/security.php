<?php
// security.php

function start_secure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        // Try to set secure cookie flags where possible
        @ini_set('session.cookie_httponly', 1);
        if (!empty($_SERVER['HTTPS'])) {
            @ini_set('session.cookie_secure', 1);
        }
        session_start();
    }
}

function generate_csrf_token() {
    start_secure_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    start_secure_session();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function log_auth_event($msg) {
    $dir = __DIR__ . '/logs';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $file = $dir . '/auth.log';
    $time = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    $line = "[$time] [$ip] $msg\n";
    @file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
}

function is_rate_limited($key, $limit = 5, $window = 300) {
    start_secure_session();
    $now = time();
    if (!isset($_SESSION['rate_limits'])) {
        $_SESSION['rate_limits'] = [];
    }
    if (!isset($_SESSION['rate_limits'][$key])) {
        $_SESSION['rate_limits'][$key] = ['count' => 0, 'start' => $now];
    }
    $data = &$_SESSION['rate_limits'][$key];
    if ($now - $data['start'] > $window) {
        $data['start'] = $now;
        $data['count'] = 0;
    }
    return ($data['count'] >= $limit);
}

function increment_rate_limit($key) {
    start_secure_session();
    $now = time();
    if (!isset($_SESSION['rate_limits'])) {
        $_SESSION['rate_limits'] = [];
    }
    if (!isset($_SESSION['rate_limits'][$key])) {
        $_SESSION['rate_limits'][$key] = ['count' => 0, 'start' => $now];
    }
    $_SESSION['rate_limits'][$key]['count']++;
}

function reset_rate_limit($key) {
    start_secure_session();
    if (isset($_SESSION['rate_limits'][$key])) {
        unset($_SESSION['rate_limits'][$key]);
    }
}

?>