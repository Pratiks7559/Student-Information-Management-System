<?php
// Start the session
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php"); // Redirect to login page if not logged in as student
    exit();
}

// Get the PRN from the session
$prn = $_SESSION['user_id'];

// Database connection
$servername = "localhost"; // replace with your database server name
$username = "root";        // replace with your database username
$password = "";            // replace with your database password
$dbname = "cse"; // replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch attendance records for the student
$sql = "SELECT attendance_date, status FROM attendance WHERE prn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $prn);
$stmt->execute();
$result = $stmt->get_result();

$attendance_records = [];
while ($row = $result->fetch_assoc()) {
    $attendance_records[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Attendance</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        h2 {
            text-align: center;
            color: #444;
            font-size: 2em;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        
        /* Table Styles */
        .attendance-table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-in-out;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-size: 1.1em;
        }
        td {
            background-color: #ffffff;
            color: #555;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }
        tr:hover td {
            background-color: #f2f2f2;
        }

        /* Responsive Table */
        @media (max-width: 600px) {
            .attendance-table {
                width: 100%;
                font-size: 0.9em;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Button Styles */
        /*.btn-back {
            display: inline-block;
            margin: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            font-size: 1em;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            text-align: center;
        }
        .btn-back:hover {
            background-color: #45a049;
        }*/
    </style>
</head>
<body>
    <h2>Your Attendance Records</h2>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($attendance_records)): ?>
                <?php foreach ($attendance_records as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['attendance_date']); ?></td>
                        <td><?php echo htmlspecialchars($record['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No attendance records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    
</body>
</html>
