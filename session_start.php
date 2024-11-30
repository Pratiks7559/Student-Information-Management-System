<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: login.html");
    exit();
}

// Access session variables dynamically
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
?>
