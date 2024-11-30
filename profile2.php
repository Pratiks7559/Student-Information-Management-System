<?php
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student' ) {
    header("Location: login.html"); // Redirect to login if not logged in
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cse"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle certificate deletion
if (isset($_POST['delete_certificate'])) {
    $certificate_file = $_POST['delete_certificate'];
    $prn = $_SESSION['user_id'];

    // Delete from database
    $sql_delete = "DELETE FROM certificates WHERE prn = ? AND certificate_file = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ss", $prn, $certificate_file);
    $stmt_delete->execute();

    // Delete from directory if successful
    if ($stmt_delete->affected_rows > 0) {
        $file_path = "uploads/certificates/" . $certificate_file;
        if (file_exists($file_path)) {
            unlink($file_path); // Remove the file
        }
    }
    $stmt_delete->close();
    header("Location: " . $_SERVER['PHP_SELF']); // Refresh page to reflect changes
    exit();
}

// Fetch student details based on session user ID (PRN)
$prn = $_SESSION['user_id'];
$sql = "SELECT profile_photo, name, email, contact_no, prn, id_card_no, year_of_study, semester, cgpa, skills FROM students WHERE prn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $prn);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc(); // Fetch student data
} else {
    echo "No profile data found!";
    exit();
}

// Fetch 10th and 12th board details
$sql_board_10th = "SELECT board_name, school_name, passing_year, percentage FROM board_10th WHERE prn = ?";
$stmt_10th = $conn->prepare($sql_board_10th);
$stmt_10th->bind_param("s", $prn);
$stmt_10th->execute();
$result_10th = $stmt_10th->get_result();
$board_10th = ($result_10th->num_rows > 0) ? $result_10th->fetch_assoc() : null;

$sql_board_12th = "SELECT board_name, school_name, passing_year, percentage FROM board_12th WHERE prn = ?";
$stmt_12th = $conn->prepare($sql_board_12th);
$stmt_12th->bind_param("s", $prn);
$stmt_12th->execute();
$result_12th = $stmt_12th->get_result();
$board_12th = ($result_12th->num_rows > 0) ? $result_12th->fetch_assoc() : null;

// Fetch certificates
$sql_certificates = "SELECT certificate_file, certificate_name FROM certificates WHERE prn = ?";
$stmt_cert = $conn->prepare($sql_certificates);
$stmt_cert->bind_param("s", $prn);
$stmt_cert->execute();
$result_cert = $stmt_cert->get_result();
$certificates = [];
while ($row = $result_cert->fetch_assoc()) {
    $certificates[] = $row; // Store both certificate_file and certificate_name
}

$stmt->close();
$stmt_10th->close();
$stmt_12th->close();
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
       body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
    color: #333;
    margin: 0;
    padding: 0;
}

.profile-container {
    width: 60%;
    margin: 60px auto;
    padding: 25px;
    text-align: center;
    background: linear-gradient(135deg, #ffffff, #f9f9f9);
    border-radius: 15px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.profile-container:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
}

.profile-photo img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin-bottom: 20px;
    border: 5px solid #007bff;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.profile-photo img:hover {
    transform: scale(1.2);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
}

.profile-info, .board-details, .certificates {
    padding: 15px;
    background: linear-gradient(135deg, #e3f2fd, #ffffff);
    border-radius: 10px;
    margin-bottom: 25px;
    text-align: left;
    transition: transform 0.3s ease, background-color 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.profile-info:hover, .board-details:hover, .certificates:hover {
    transform: scale(1.02);
    background-color: #cde7ff;
}

h2, h3 {
    color: #ff6f61; /* Warm coral tone */
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
}

.certificates img {
    width: 120px;
    height: 120px;
    margin: 15px;
    border: 3px solid #007bff;
    border-radius: 15px;
    object-fit: cover;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.certificates img:hover {
    transform: rotate(3deg) scale(1.1);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
}

.certificates button {
    display: inline-block;
    margin: 10px;
    padding: 8px 15px;
    border: none;
    color: #fff;
    background: linear-gradient(135deg, #ff4e50, #f9d423);
    border-radius: 25px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
    font-weight: bold;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.certificates button:hover {
    transform: scale(1.1);
    background: linear-gradient(135deg, #e43a15, #f8c123);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.profile-container {
    animation: fadeIn 0.8s ease;
}

    </style>
</head>
<body>

<div class="profile-container">
    <h2>Your Profile</h2>
    <div class="profile-photo">
        <img src="uploads/<?php echo htmlspecialchars($student['profile_photo']); ?>" alt="Profile Photo">
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
        <?php if ($board_10th): ?>
            <p><strong>Board Name:</strong> <?php echo htmlspecialchars($board_10th['board_name']); ?></p>
            <p><strong>School Name:</strong> <?php echo htmlspecialchars($board_10th['school_name']); ?></p>
            <p><strong>Passing Year:</strong> <?php echo htmlspecialchars($board_10th['passing_year']); ?></p>
            <p><strong>Percentage:</strong> <?php echo htmlspecialchars($board_10th['percentage']); ?>%</p>
        <?php else: ?>
            <p>No 10th board details found.</p>
        <?php endif; ?>
    </div>

    <div class="board-details">
        <h3>12th Board Details</h3>
        <?php if ($board_12th): ?>
            <p><strong>Board Name:</strong> <?php echo htmlspecialchars($board_12th['board_name']); ?></p>
            <p><strong>School Name:</strong> <?php echo htmlspecialchars($board_12th['school_name']); ?></p>
            <p><strong>Passing Year:</strong> <?php echo htmlspecialchars($board_12th['passing_year']); ?></p>
            <p><strong>Percentage:</strong> <?php echo htmlspecialchars($board_12th['percentage']); ?>%</p>
        <?php else: ?>
            <p>No 12th board details found.</p>
        <?php endif; ?>
    </div>

    <div class="certificates">
        <h3>Your Certificates</h3>
        <?php if (!empty($certificates)): ?>
            <?php foreach ($certificates as $certificate): ?>
                <div>
                    <?php if (pathinfo($certificate['certificate_file'], PATHINFO_EXTENSION) == 'pdf'): ?>
                        <a href="uploads/certificates/<?php echo htmlspecialchars($certificate['certificate_file']); ?>" target="_blank">View PDF Certificate</a>
                    <?php else: ?>
                        <img src="uploads/certificates/<?php echo htmlspecialchars($certificate['certificate_file']); ?>" alt="Certificate">
                    <?php endif; ?>
                    <p><strong><?php echo htmlspecialchars($certificate['certificate_name']); ?></strong></p> <!-- Display the certificate name -->
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="delete_certificate" value="<?php echo htmlspecialchars($certificate['certificate_file']); ?>">
                        <button type="submit">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No certificates found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
