<?php
require 'security.php';
start_secure_session();

// Only allow POST requests to refresh the CAPTCHA
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// generate new CAPTCHA and return as JSON
$_SESSION['captcha'] = substr(str_shuffle("ABCDEFG123456"), 0, 5);
header('Content-Type: application/json');
echo json_encode(['captcha' => $_SESSION['captcha']]);
exit;
?>