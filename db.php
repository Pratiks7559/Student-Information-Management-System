<?php
// Database configuration
$host = 'localhost';
$db = 'pyq_website';  // Ensure this matches your actual database name
$user = 'root';       // Replace with your actual database user
$pass = '';           // Replace with your actual password

// Establish PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
