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
    $user_type = $_POST['user_type'];
    $user_id = $_POST['user_id'];
    $token = bin2hex(random_bytes(50)); // Generate a random token for password reset

    // Determine table and identifier based on user type
    $table = $user_type === 'student' ? 'students' : 'faculty';
    $identifier = $user_type === 'student' ? 'prn' : 'emp_id';

    // Fetch user's email
    $stmt = $conn->prepare("SELECT email FROM $table WHERE $identifier = ?");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $user_id);

    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($email);
        $stmt->fetch();

        // Store the reset token in the database
        $token_stmt = $conn->prepare("UPDATE $table SET reset_token = ? WHERE $identifier = ?");
        if (!$token_stmt) {
            die("Error preparing token statement: " . $conn->error);
        }
        $token_stmt->bind_param("ss", $token, $user_id);

        if ($token_stmt->execute()) {
            // Send reset link via email
            $reset_link = "http://yourwebsite.com/reset_password.php?token=$token&user_type=$user_type";
            $subject = "Password Reset Request";
            $message = "Click the link below to reset your password:\n$reset_link";
            $headers = "From: engineeringcollage0@gmail.com";

            if (mail($email, $subject, $message, $headers)) {
                echo "Password reset link sent to your email!";
            } else {
                echo "Failed to send reset link. Please try again.";
            }
        } else {
            echo "Failed to store reset token.";
        }
        $token_stmt->close();
    } else {
        echo "No user found with that ID!";
    }

    $stmt->close();
}

$conn->close();
?>
