<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cse";  // Change this to your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $user_type = $_POST['user_type'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    if ($user_type == 'student') {
        // Reset password for student
        $stmt = $conn->prepare("UPDATE students SET password = ?, reset_token = NULL WHERE reset_token = ?");
    } else {
        // Reset password for faculty
        $stmt = $conn->prepare("UPDATE faculty SET password = ?, reset_token = NULL WHERE reset_token = ?");
    }

    $stmt->bind_param("ss", $new_password, $token);

    if ($stmt->execute()) {
        echo "Password reset successful!";
        // Redirect to login page
        header("Location: login.html");
    } else {
        echo "Failed to reset password!";
    }

    $stmt->close();
}

$conn->close();
?>
