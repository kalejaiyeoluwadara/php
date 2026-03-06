<?php
/**
 * Database initialization script - run once to create database and tables
 * Run via: http://localhost/php/kasie_project/config/init_db.php
 */

$servername = "localhost";
$username = "root";
$password = "";

// Connect without database first (in case it doesn't exist)
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS kasie_project CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db("kasie_project");
$conn->set_charset("utf8mb4");

// Users table with roles
$conn->query("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'editor', 'user') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_email (email),
        INDEX idx_role (role)
    ) ENGINE=InnoDB
");

// Content table for role-restricted content
$conn->query("
    CREATE TABLE IF NOT EXISTS content (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        body TEXT NOT NULL,
        content_type ENUM('article', 'image', 'video') NOT NULL DEFAULT 'article',
        min_role ENUM('admin', 'editor', 'user') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB
");

// Insert sample content for demonstration (only if empty)
$check = $conn->query("SELECT COUNT(*) FROM content");
if ($check && $check->fetch_row()[0] == 0) {
    $conn->query("
        INSERT INTO content (title, body, content_type, min_role) VALUES
        ('Welcome Article', 'This article is visible to all logged-in users.', 'article', 'user'),
        ('Editor Only Content', 'This content is only visible to editors and admins.', 'article', 'editor'),
        ('Admin Dashboard Info', 'Sensitive admin-only information goes here.', 'article', 'admin')
    ");
}

echo "Database and tables created successfully. <a href='../register.php'>Register</a> | <a href='../login.php'>Login</a>";
