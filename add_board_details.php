<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cse"; // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$prn = $_SESSION['user_id']; // PRN is stored in session after login

// Get 10th board details from form
$board_10th = $_POST['board_10th'];
$school_10th = $_POST['school_10th'];
$year_10th = $_POST['year_10th'];
$percentage_10th = $_POST['percentage_10th'];

// Get 12th board details from form
$board_12th = $_POST['board_12th'];
$school_12th = $_POST['school_12th'];
$year_12th = $_POST['year_12th'];
$percentage_12th = $_POST['percentage_12th'];

// Insert 10th board details into the database
$sql_10th = "INSERT INTO board_10th (prn, board_name, school_name, passing_year, percentage) 
             VALUES ('$prn', '$board_10th', '$school_10th', '$year_10th', '$percentage_10th')";

if ($conn->query($sql_10th) === TRUE) {
    echo "10th board details added successfully!<br>";
} else {
    echo "Error: " . $sql_10th . "<br>" . $conn->error;
}

// Insert 12th board details into the database
$sql_12th = "INSERT INTO board_12th (prn, board_name, school_name, passing_year, percentage) 
             VALUES ('$prn', '$board_12th', '$school_12th', '$year_12th', '$percentage_12th')";

if ($conn->query($sql_12th) === TRUE) {
    echo "12th board details added successfully!<br>";
} else {
    echo "Error: " . $sql_12th . "<br>" . $conn->error;
}

$conn->close();
?>
