<?php
require 'db.php';

function register_user($conn, $username, $email, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hash);
    $stmt->execute();
    echo "Registered $username\n";
    return $hash;
}

function create_legacy_user($conn, $username, $email, $password) {
    $legacy = base64_encode(hash('sha256', $password, true));
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $legacy);
    $stmt->execute();
    echo "Created legacy user $username\n";
    return $legacy;
}

function verify_login_flow($conn, $username, $password) {
    $stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $stored = $row['password'];
        if (password_verify($password, $stored)) {
            echo "Login OK for $username (new-style)\n";
            return true;
        } elseif (hash_equals($stored, base64_encode(hash('sha256', $password, true)))) {
            echo "Legacy match for $username — migrating\n";
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password=? WHERE username=?");
            $update->bind_param("ss", $newHash, $username);
            $update->execute();
            echo "Migrated $username\n";
            return true;
        } else {
            echo "Login failed for $username\n";
            return false;
        }
    } else {
        echo "No user $username\n";
        return false;
    }
}

echo "Cleaning up test users...\n";
$conn->query("DELETE FROM users WHERE username IN ('testuser','legacyuser')");

// Test new registration and login
echo "\n== New user test ==\n";
register_user($conn, 'testuser', 'test@example.com', 'testpass');
verify_login_flow($conn, 'testuser', 'testpass');
verify_login_flow($conn, 'testuser', 'wrongpass');

// Test legacy migration
echo "\n== Legacy migration test ==\n";
create_legacy_user($conn, 'legacyuser', 'legacy@example.com', 'secret');
verify_login_flow($conn, 'legacyuser', 'secret');
verify_login_flow($conn, 'legacyuser', 'secret');

echo "\nAll tests completed.\n";