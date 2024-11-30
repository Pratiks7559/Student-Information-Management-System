
<?php
session_start();

// Check if user is logged in and is either a student, admin, or faculty
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['admin', 'faculty'])) {
    header("Location: login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cse"; // Update with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get PRN from GET parameter if available, else use session user_id
$prn = isset($_GET['prn']) ? $_GET['prn'] : $_SESSION['user_id'];

// Handle certificate deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_certificate'])) {
    $certificate_file = $_POST['delete_certificate'];

    // Delete certificate from database
    $sql_delete = "DELETE FROM certificates WHERE prn = ? AND certificate_file = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ss", $prn, $certificate_file);
    $stmt_delete->execute();

    // Check if deletion was successful
    if ($stmt_delete->affected_rows > 0) {
        $file_path = "uploads/certificates/" . $certificate_file;

        // Remove file from the directory
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    $stmt_delete->close();

    // Redirect to the same page to reflect changes
    header("Location: " . $_SERVER['PHP_SELF'] . "?prn=" . $prn);
    exit();
}

// Fetch student details
$sql = "SELECT profile_photo, name, email, contact_no, prn, id_card_no, year_of_study, semester, cgpa, skills FROM students WHERE prn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $prn);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "No profile data found!";
    exit();
}

// Fetch 10th and 12th board details
function fetchBoardDetails($conn, $prn, $table) {
    $sql_board = "SELECT board_name, school_name, passing_year, percentage FROM $table WHERE prn = ?";
    $stmt = $conn->prepare($sql_board);
    $stmt->bind_param("s", $prn);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

$board_10th = fetchBoardDetails($conn, $prn, 'board_10th') ?: [];
$board_12th = fetchBoardDetails($conn, $prn, 'board_12th') ?: [];

// Fetch certificates
$sql_certificates = "SELECT certificate_file, certificate_name FROM certificates WHERE prn = ?";
$stmt_cert = $conn->prepare($sql_certificates);
$stmt_cert->bind_param("s", $prn);
$stmt_cert->execute();
$result_cert = $stmt_cert->get_result();
$certificates = [];
while ($row = $result_cert->fetch_assoc()) {
    $certificates[] = $row;
}

$stmt->close();
$stmt_cert->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <style>
        /* CSS styles omitted for brevity; reuse previous styles */
        body {
    font-family: Arial, sans-serif;
    background-color: #f0f2f5;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.profile-container {
    background-color: #fff;
    width: 80%;
    max-width: 800px;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    animation: fadeIn 1s ease-in-out;
}

/* Header styling */
.profile-container h2 {
    color: #333;
    text-align: center;
    font-size: 1.8rem;
    margin-bottom: 20px;
    animation: fadeIn 0.5s ease-in-out;
}

/* Profile photo styling */
.profile-photo {
    text-align: center;
    margin-bottom: 20px;
}

.profile-photo img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 2px solid #4CAF50;
    animation: popIn 1.2s ease;
}

/* Profile info styling */
.profile-info {
    margin: 10px 0;
    color: #555;
    font-size: 1rem;
}

.profile-info p {
    margin: 8px 0;
    font-weight: 500;
}

.board-details h3,
.certificates h3 {
    margin-top: 20px;
    color: #333;
    font-size: 1.5rem;
    border-bottom: 2px solid #4CAF50;
    display: inline-block;
    padding-bottom: 5px;
}

/* Button Styling */
button {
    padding: 8px 12px;
    margin: 10px 0;
    border: none;
    color: #fff;
    background-color: #f44336;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
    background-color: #d32f2f;
    transform: scale(1.05);
}

/* Certificates Styling */
.certificates div {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    margin-top: 15px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
}

.certificates img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
    transition: transform 0.3s ease;
}

.certificates img:hover {
    transform: scale(1.1);
}

.certificates a {
    color: #2196F3;
    font-weight: bold;
    text-decoration: none;
    transition: color 0.3s ease;
}

.certificates a:hover {
    color: #1976D2;
}

/* Go-back button styling */
.go-back-button {
    position: absolute;
    top: 20px;
    left: 20px;
    display: inline-block;
    color: #4CAF50;
    font-weight: bold;
    padding: 8px 12px;
    text-decoration: none;
    background-color: #e8f5e9;
    border-radius: 5px;
    transition: color 0.3s ease, background-color 0.3s ease, transform 0.3s ease;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
}

.go-back-button:hover {
    color: #ffffff;
    background-color: #4CAF50;
    transform: translateY(-2px) scale(1.05);
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes popIn {
    0% {
        opacity: 0;
        transform: scale(0.8);
    }
    60% {
        opacity: 1;
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}


    </style>
</head>
<body>

<a href="student_info.php?year_of_study=<?php echo urlencode($student['year_of_study']); ?>" class="go-back-button">Go Back</a>

<div class="profile-container">
    <h2>Student Profile</h2>

    <div class="profile-photo">
        <?php if ($student['profile_photo']): ?>
            <img src="uploads/<?php echo htmlspecialchars($student['profile_photo']); ?>" alt="Profile Photo">
        <?php else: ?>
            <img src="default-profile.png" alt="Profile Photo">
        <?php endif; ?>
    </div>

    <div class="profile-info">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
        <p><strong>Contact No:</strong> <?php echo htmlspecialchars($student['contact_no']); ?></p>
        <p><strong>PRN:</strong> <?php echo htmlspecialchars($student['prn']); ?></p>
        <p><strong>ID Card No:</strong> <?php echo htmlspecialchars($student['id_card_no']); ?></p>
        <p><strong>Year of Study:</strong> <?php echo htmlspecialchars($student['year_of_study']); ?></p>
        <p><strong>Semester:</strong> <?php echo htmlspecialchars($student['semester']); ?></p>
        <p><strong>CGPA:</strong> <?php echo htmlspecialchars($student['cgpa']); ?></p>
        <p><strong>Skills:</strong> <?php echo htmlspecialchars($student['skills']); ?></p>
    </div>

    <div class="board-details">
        <h3>10th Board Details</h3>
        <p><strong>Board Name:</strong> <?php echo htmlspecialchars($board_10th['board_name'] ?? 'N/A'); ?></p>
        <p><strong>School Name:</strong> <?php echo htmlspecialchars($board_10th['school_name'] ?? 'N/A'); ?></p>
        <p><strong>Passing Year:</strong> <?php echo htmlspecialchars($board_10th['passing_year'] ?? 'N/A'); ?></p>
        <p><strong>Percentage:</strong> <?php echo htmlspecialchars($board_10th['percentage'] ?? 'N/A'); ?>%</p>

        <h3>12th Board Details</h3>
        <p><strong>Board Name:</strong> <?php echo htmlspecialchars($board_12th['board_name'] ?? 'N/A'); ?></p>
        <p><strong>School Name:</strong> <?php echo htmlspecialchars($board_12th['school_name'] ?? 'N/A'); ?></p>
        <p><strong>Passing Year:</strong> <?php echo htmlspecialchars($board_12th['passing_year'] ?? 'N/A'); ?></p>
        <p><strong>Percentage:</strong> <?php echo htmlspecialchars($board_12th['percentage'] ?? 'N/A'); ?>%</p>
    </div>

    <div class="certificates">
        <h3>Certificates</h3>
        <?php if (empty($certificates)): ?>
            <p>No certificates found!</p>
        <?php else: ?>
            <?php foreach ($certificates as $cert): ?>
                <div>
                    <?php
                    $file_path = "uploads/certificates/" . htmlspecialchars($cert['certificate_file']);
                    $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

                    if (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
                        echo "<img src='$file_path' alt='{$cert['certificate_name']}' width='80' height='80'>";
                    } else {
                        echo "<p>{$cert['certificate_name']} (Preview not available)</p>";
                    }
                    ?>
                    <div><?php echo htmlspecialchars($cert['certificate_name']); ?></div>
                    <a href="<?php echo $file_path; ?>" target="_blank" download="<?php echo htmlspecialchars($cert['certificate_name']); ?>">Download</a>
                    <a href="<?php echo $file_path; ?>" target="_blank">View</a>
                    <form method="post" action="">
                        <input type="hidden" name="delete_certificate" value="<?php echo htmlspecialchars($cert['certificate_file']); ?>">
                        <button type="submit">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
