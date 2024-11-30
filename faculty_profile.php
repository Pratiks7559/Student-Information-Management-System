<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cse"; // Change this to your actual database name

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the faculty is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'faculty' && $_SESSION['user_type'] != 'admin') {
    header("Location: login.html");
    exit();
}

// Get faculty ID from session
$faculty_id = $_SESSION['user_id'];

// Fetch faculty details from the database
$sql = "SELECT emp_id, name, email, contact_no, profile_photo FROM faculty WHERE emp_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $faculty = $result->fetch_assoc();
    } else {
        echo "No details found for this faculty.";
        exit();
    }
    $stmt->close();
} else {
    die("Query preparation failed: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Profile</title>
    <style>
        /* Basic Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #667eea, #764ba2);
            animation: backgroundColorShift 10s infinite alternate;
        }

        @keyframes backgroundColorShift {
            0% {
                background: linear-gradient(135deg, #667eea, #764ba2);
            }
            50% {
                background: linear-gradient(135deg, #43cea2, #185a9d);
            }
            100% {
                background: linear-gradient(135deg, #ff6a00, #ee0979);
            }
        }

        .profile-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
            transform: scale(0.9);
            animation: bounceIn 1s ease forwards;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }
            80% {
                transform: scale(1.05);
                opacity: 1;
            }
            100% {
                transform: scale(1);
            }
        }

        .profile-photo img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: 15px;
            border: 3px solid #764ba2;
            animation: photoGlow 2s infinite alternate;
        }

        @keyframes photoGlow {
            0% {
                box-shadow: 0 0 10px #764ba2;
            }
            100% {
                box-shadow: 0 0 20px #667eea;
            }
        }

        .profile-info {
            margin-top: 20px;
            font-size: 16px;
        }

        .profile-info p {
            margin: 10px 0;
            font-size: 16px;
            color: #333;
            transition: color 0.5s;
        }

        .profile-info p:hover {
            color: #764ba2;
        }

        .profile-info p strong {
            color: #555;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <div class="profile-photo">
        <img src="uploads/<?php echo htmlspecialchars($faculty['profile_photo']); ?>" alt="Profile Photo">
    </div>
    <div class="profile-info">
        <p><strong>Employee ID:</strong> <?php echo htmlspecialchars($faculty['emp_id']); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($faculty['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($faculty['email']); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($faculty['contact_no']); ?></p>
    </div>
</div>

</body>
</html>