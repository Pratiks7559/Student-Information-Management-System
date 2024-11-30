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
    $emp_id = $conn->real_escape_string(trim($_POST['emp_id']));
    $name = $conn->real_escape_string(trim($_POST['name']));
    $password = $conn->real_escape_string(trim($_POST['password']));
    
    // Check if the PRN already exists
    $checkQuery = "SELECT * FROM faculty WHERE emp_id='$emp_id'";
    $result = $conn->query($checkQuery);
    
    if ($result->num_rows > 0) {
        $error = "A student with this PRN already exists.";
    } else {
        // Insert new student into the students table
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security
        $sql = "INSERT INTO faculty (emp_id, name, password) VALUES ('$emp_id', '$name', '$hashedPassword')";
        
        if ($conn->query($sql) === TRUE) {
            $success = "faculty added successfully!";
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
    <title>Add Faculty</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; font-size: 16px; }
        .btn { padding: 10px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer; font-size: 16px; }
        .message { margin-top: 15px; font-weight: bold; color: green; }
        .error { margin-top: 15px; font-weight: bold; color: red; }
    </style>
</head>
<body>

<div class="container">
    <h2>Add faculty</h2>
    
    <!-- Success or Error Message -->
    <?php if ($success) echo "<p class='message'>$success</p>"; ?>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    
    <!-- Form to Add Student -->
    <form action="add_faculty.php" method="POST">
        <div class="form-group">
            <label for="emp_id">emp_id (Primary Key):</label>
            <input type="text" id="emp_id" name="emp_id" required>
        </div>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Add faculty</button>
    </form>
</div>

</body>
</html>
