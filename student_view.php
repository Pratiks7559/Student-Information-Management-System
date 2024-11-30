<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check if the user is logged in as a student and has a PRN
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'student' || !isset($_SESSION['prn'])) {
    echo "<p>Error: You must be logged in as a student to view attendance records.</p>";
    exit;
}

// Function to display attendance records based on PRN
function displayAttendance($prn) {
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cse"; // Update this with your actual database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch attendance records for the student
    $sql = "SELECT attendance_date, status FROM attendance WHERE prn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $prn);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h3>Attendance Records for PRN: $prn</h3>";

    if ($result->num_rows > 0) {
        $total_classes = 0;
        $present_count = 0;

        echo "<table>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['attendance_date']}</td>
                    <td>{$row['status']}</td>
                  </tr>";

            // Count attendance records
            $total_classes++;
            if ($row['status'] == 'Present') {
                $present_count++;
            }
        }
        echo "</table>";

        // Calculate attendance percentage
        if ($total_classes > 0) {
            $attendance_percentage = ($present_count / $total_classes) * 100;
            echo "<p>Attendance Percentage: " . round($attendance_percentage, 2) . "%</p>";
        }

    } else {
        echo "<p>No attendance records found for PRN: $prn</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Display attendance for the logged-in student's PRN
$prn = $_SESSION['prn'];
displayAttendance($prn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Attendance Records</h2>
</body>
</html>
