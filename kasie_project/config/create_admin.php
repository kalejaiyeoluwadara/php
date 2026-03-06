<?php
/**
 * Create admin user for testing - run once after init_db.php
 * Default: admin@example.com / Admin123!
 * DELETE THIS FILE IN PRODUCTION
 */

require_once __DIR__ . '/database.php';

$email = 'admin@example.com';
$password = 'Admin123!';
$name = 'Administrator';
$role = 'admin';

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE password = VALUES(password), role = VALUES(role)");
$stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

if ($stmt->execute()) {
    echo "Admin user created/updated. Email: admin@example.com, Password: Admin123!";
} else {
    echo "Error: " . $conn->error;
}
$stmt->close();
