<?php
// config.php

// Database configuration
$host = "localhost";
$username = "root"; // Your MySQL username (default is root for XAMPP)
$password = ""; // Your MySQL password (default is empty for XAMPP)
$database = "venkatesh2"; // Your database name (the name you chose during setup)

// Create a PDO instance for database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    // Set the PDO error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
