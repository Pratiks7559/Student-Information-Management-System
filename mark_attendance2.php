<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "cse"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendance_date = $_POST['attendance_date'];
    $semester = $_POST['semester'];
    $attendance_data = $_POST['attendance'] ?? [];

    // Check if attendance data is provided
    if (empty($attendance_data)) {
        echo "No attendance data provided!";
        exit;
    }

    // Insert attendance into the database
    foreach ($attendance_data as $prn => $status) {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("
            INSERT INTO attendance (prn, attendance_date, semester, status, timestamp) 
            VALUES (?, ?, ?, ?, NOW())
        ");

        $stmt->bind_param("ssis", $prn, $attendance_date, $semester, $status);

        if (!$stmt->execute()) {
            echo "Error inserting data for PRN $prn: " . $stmt->error . "<br>";
        }
    }

    echo "Attendance marked successfully!";
} else {
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>
