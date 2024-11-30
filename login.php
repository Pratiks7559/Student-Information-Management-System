<?php
session_start(); // Start the session

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
    $password = $_POST['password'];

    if ($user_type == 'student') {
        // Check student login
        $stmt = $conn->prepare("SELECT prn, password FROM students WHERE prn = ?");
        $stmt->bind_param("s", $user_id);
    } elseif ($user_type == 'faculty') {
        // Check faculty login
        $stmt = $conn->prepare("SELECT emp_id, password FROM faculty WHERE emp_id = ?");
        $stmt->bind_param("s", $user_id);
    } elseif ($user_type == 'admin') {
        // Check admin login
        $stmt = $conn->prepare("SELECT admin_id, password FROM admin WHERE admin_id = ?");
        $stmt->bind_param("s", $user_id);
    } else {
        echo "Invalid user type!";
        exit();
    }

    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_user_id, $db_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $db_password)) {
            // Successful login
            $_SESSION['user_id'] = $db_user_id;
            $_SESSION['user_type'] = $user_type;

            echo "Login successful!";

            // Redirect to the respective dashboard based on user type
            if ($user_type == 'student') {
                header("Location: student_dashboard.php"); // Redirect to student dashboard
            } elseif ($user_type == 'faculty') {
                header("Location: faculty_dashboard.php"); // Redirect to faculty dashboard
            } elseif ($user_type == 'admin') {
                header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            }

            exit(); // Make sure the script ends after redirection
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Invalid PRN/Employee ID/Admin ID!";
    }

    $stmt->close();
}

$conn->close();
?>
