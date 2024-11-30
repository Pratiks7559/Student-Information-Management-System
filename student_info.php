<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cse";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin' && $_SESSION['user_type'] != 'faculty') {
    header("Location: login.php");
    exit();
}

// Delete student profile if delete request is sent
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Fetch profile photo to delete from the filesystem
    $photo_sql = "SELECT profile_photo FROM students WHERE prn = ?";
    $photo_stmt = $conn->prepare($photo_sql);
    $photo_stmt->bind_param("s", $delete_id);
    $photo_stmt->execute();
    $photo_result = $photo_stmt->get_result();
    
    if ($photo_result->num_rows > 0) {
        $student_data = $photo_result->fetch_assoc();
        $photo_path = 'uploads/' . $student_data['profile_photo'];

        // Delete the student profile from the database
        $delete_sql = "DELETE FROM students WHERE prn = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("s", $delete_id);
        
        if ($stmt->execute()) {
            // Delete profile photo from the filesystem
            if (file_exists($photo_path)) {
                unlink($photo_path); // Delete the file
            }
            echo "<script>alert('Student profile deleted successfully');</script>";
            // Redirect to avoid resubmission of delete request on refresh
            echo "<script>window.location.href = 'student_info.php?year_of_study=" . urlencode($selected_year) . "';</script>";
            exit();
        } else {
            echo "<script>alert('Error deleting student profile: " . $conn->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Student profile not found.');</script>";
    }
    $photo_stmt->close();
}

// Fetch students based on the selected year of study
$selected_year = isset($_GET['year_of_study']) ? $_GET['year_of_study'] : '';
$sql = "SELECT prn, name, email, contact_no, profile_photo FROM students WHERE year_of_study = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $selected_year);
$stmt->execute();
$result = $stmt->get_result();
if ($result === false) {
    die("Error fetching students: " . $conn->error);
}

// Fetch distinct years of study for the dropdown
$years = ["1st Year", "2nd Year", "3rd Year", "4th Year"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Member List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .student-container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            animation: fadeIn 0.5s ease-in;
        }
        h2 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }
        .filter-form {
            margin-bottom: 20px;
            text-align: center;
        }
        .student-list {
            list-style-type: none;
            padding: 0;
        }
        .student-item {
            border: 1px solid #ddd;
            margin-bottom: 15px;
            padding: 10px;
            display: flex;
            align-items: center;
            transition: transform 0.3s;
        }
        .student-item:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .student-photo img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .student-info p {
            margin: 5px 0;
        }
        .action-buttons {
            margin-left: auto;
        }
        .action-buttons a {
            margin-right: 10px;
            color: #007BFF;
            text-decoration: none;
            transition: color 0.3s;
        }
        .action-buttons a:hover {
            color: #0056b3;
        }
        .delete-button {
            color: red;
        }
        .delete-button:hover {
            text-decoration: underline;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
    <script>
        // Go back to the previous page
        function goBack() {
            window.history.back();
        }
    </script>
</head>
<body>

<div class="student-container">
    <h2>Student Member List</h2>
    <form method="GET" class="filter-form">
        <label for="year_of_study">Select Year of Study:</label>
        <select name="year_of_study" id="year_of_study">
            <option value="">--Select Year--</option>
            <?php foreach ($years as $year) { ?>
                <option value="<?php echo $year; ?>" <?php echo ($year == $selected_year) ? 'selected' : ''; ?>>
                    <?php echo $year; ?>
                </option>
            <?php } ?>
        </select>
        <input type="submit" value="Filter">
    </form>
    <ul class="student-list">
        <?php while ($student = $result->fetch_assoc()) { ?>
            <li class="student-item">
                <div class="student-photo">
                    <img src="uploads/<?php echo htmlspecialchars($student['profile_photo']); ?>" alt="Profile Photo">
                </div>
                <div class="student-info">
                    <p><strong>PRN:</strong> <?php echo htmlspecialchars($student['prn']); ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                    <p><strong>Contact No:</strong> <?php echo htmlspecialchars($student['contact_no']); ?></p>
                </div>
                <div class="action-buttons">
                    <a href="student_profile.php?prn=<?php echo htmlspecialchars($student['prn']); ?>&year_of_study=<?php echo urlencode($selected_year); ?>" class="view-button">View Profile</a>
                    <a href="view.php?prn=<?php echo htmlspecialchars($student['prn']); ?>&year_of_study=<?php echo urlencode($selected_year); ?>" class="view-attendance-button">View Attendance Record</a>
                    <a href="?delete_id=<?php echo htmlspecialchars($student['prn']); ?>&year_of_study=<?php echo urlencode($selected_year); ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this profile?');">Delete</a>
                </div>
            </li>
        <?php } ?>
    </ul>
    <!-- Go Back Button -->
    <button onclick="goBack()" style="display: block; margin: 20px auto; padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">Go Back</button>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
