<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cse');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for error and success messages
$error = $success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize inputs
    $prn = $conn->real_escape_string(trim($_POST['prn']));
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $conn->real_escape_string(trim($_POST['password']));
    
    // Check if the PRN or email already exists
    $checkQuery = "SELECT * FROM students WHERE prn='$prn' OR email='$email'";
    $result = $conn->query($checkQuery);
    
    if ($result->num_rows > 0) {
        $error = "A student with this PRN or email already exists.";
    } else {
        // Insert new student into the students table
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security
        $sql = "INSERT INTO students (prn, name, email, password) VALUES ('$prn', '$name', '$email', '$hashedPassword')";
        
        if ($conn->query($sql) === TRUE) {
            $success = "Student added successfully!";
        } else {
            $error = "Error adding student: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; }
        input[type="text"], input[type="password"], input[type="email"] { width: 100%; padding: 8px; font-size: 16px; }
        .btn { padding: 10px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer; font-size: 16px; }
        .message { margin-top: 15px; font-weight: bold; color: green; }
        .error { margin-top: 15px; font-weight: bold; color: red; }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Student</h2>
    
    <!-- Success or Error Message -->
    <?php if ($success) echo "<p class='message'>$success</p>"; ?>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    
    <!-- Form to Add Student -->
    <form action="add_student.php" method="POST">
        <div class="form-group">
            <label for="prn">PRN (Primary Key):</label>
            <input type="text" id="prn" name="prn" required>
        </div>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Add Student</button>
    </form>
</div>

</body>
</html>
