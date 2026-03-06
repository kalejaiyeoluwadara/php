<?php
/**
 * Database configuration for INSY 402/SENG 412 - Multi-User Login System
 * Uses MySQL with mysqli for secure prepared statements
 */

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kasie_project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
