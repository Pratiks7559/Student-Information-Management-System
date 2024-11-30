<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
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
    <h2>View Attendance</h2>
    
    <?php
    session_start();
    
    // Check if the user is logged in
    if (!isset($_SESSION['user_type'])) {
        echo "<p>Error: User type not set. Please log in.</p>";
        exit; // Exit if user type is not set
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

        if ($result->num_rows > 0) {
            $total_classes = 0;
            $present_count = 0;

            echo "<h3>Attendance Records for PRN: $prn</h3>";
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

    // Check user type and handle attendance display
    if ($_SESSION['user_type'] == 'student') {
        // Retrieve PRN from session for students
        if (isset($_SESSION['prn'])) {
            $prn = $_SESSION['prn'];
            echo "<p>Viewing attendance for PRN: $prn</p>";
            displayAttendance($prn);
        } else {
            echo "<p>Error: Student PRN not set. Please log in again.</p>";
        }
        
    } elseif ($_SESSION['user_type'] == 'faculty' || $_SESSION['user_type'] == 'admin') {
        // Faculty or Admin can input a student's PRN
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['prn'])) {
            $prn = trim($_POST['prn']);
            displayAttendance($prn);
        } else {
            echo '
            <form method="post" action="">
                <label>Enter Student PRN:</label>
                <input type="text" name="prn" required><br><br>
                <button type="submit" name="view_attendance">View Attendance</button>
            </form>';
        }
    } else {
        echo "<p>You must be logged in to view attendance records.</p>";
    }
    ?>
</body>
</html>
