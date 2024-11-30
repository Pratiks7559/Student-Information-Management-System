<?php
session_start();
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
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.html");
    exit();
}

// Delete faculty profile if delete request is sent
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM faculty WHERE emp_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Faculty profile deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting faculty profile');</script>";
    }
    $stmt->close();
}

// Fetch faculty members from the database
$sql = "SELECT emp_id, name, email, contact_no, profile_photo FROM faculty";
$result = $conn->query($sql);
if ($result === false) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Member List</title>
    <style>
        /* CSS styles here */
        /* Reset some basic styles */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
}

/* Faculty container */
.faculty-container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Title styling */
h2 {
    text-align: center;
    color: #4CAF50;
}

/* Faculty list */
.faculty-list {
    list-style: none;
    padding: 0;
}

/* Individual faculty item */
.faculty-item {
    display: flex;
    align-items: center;
    padding: 15px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    background: #fafafa;
    transition: background 0.3s, transform 0.3s;
}

/* Hover effect on faculty item */
.faculty-item:hover {
    background: #e8f5e9;
    transform: translateY(-2px);
}

/* Faculty photo */
.faculty-photo img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    border: 2px solid #4CAF50;
    margin-right: 15px;
}

/* Faculty info */
.faculty-info {
    flex-grow: 1;
}

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 10px;
}

/* Button styles */
.view-button, .delete-button {
    padding: 8px 15px;
    border-radius: 5px;
    text-decoration: none;
    color: #fff;
    transition: background 0.3s, transform 0.3s;
}

/* View button styles */
.view-button {
    background: #2196F3;
}

/* Delete button styles */
.delete-button {
    background: #F44336;
}

/* Button hover effects */
.view-button:hover {
    background: #1976D2;
    transform: scale(1.05);
}

.delete-button:hover {
    background: #D32F2F;
    transform: scale(1.05);
}

/* Responsive styling */
@media (max-width: 768px) {
    .faculty-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .faculty-photo {
        margin-bottom: 10px;
    }
}

    </style>
</head>
<body>

<div class="faculty-container">
    <h2>Faculty Member List</h2>
    <ul class="faculty-list">
        <?php while ($faculty = $result->fetch_assoc()) { ?>
            <li class="faculty-item">
                <div class="faculty-photo">
                    <img src="uploads/<?php echo htmlspecialchars($faculty['profile_photo']); ?>" alt="Profile Photo">
                </div>
                <div class="faculty-info">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($faculty['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($faculty['email']); ?></p>
                    <p><strong>Contact No:</strong> <?php echo htmlspecialchars($faculty['contact_no']); ?></p>
                </div>
                <div class="action-buttons">
                    
                    <a href="?delete_id=<?php echo $faculty['emp_id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this profile?');">Delete</a>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>

</body>
</html>

<?php $conn->close(); ?>
