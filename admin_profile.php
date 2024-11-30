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

// Check if the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.html");
    exit();
}

// Get admin ID from session
$admin_id = $_SESSION['user_id'];

// Fetch admin details from the database
$sql = "SELECT admin_id, name, email, contact_no, profile_photo FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
    } else {
        echo "No details found for this admin.";
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
    <title>Admin Profile</title>
    <style>
        /* Basic Styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            overflow: hidden;
        }

        .profile-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 1.5s ease-in-out;
            position: relative;
            width: 350px;
        }

        .profile-photo img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #2575fc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: bounce 2s infinite;
        }

        .profile-info {
            margin-top: 20px;
            font-size: 16px;
        }

        .profile-info p {
            margin: 10px 0;
            font-size: 15px;
            color: #444;
        }

        .profile-info p strong {
            color: #6a11cb;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        /* Background Animation */
        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        body {
            background-size: 200% 200%;
            animation: gradientBG 6s ease infinite;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <div class="profile-photo">
        <img src="uploads/<?php echo htmlspecialchars($admin['profile_photo']); ?>" alt="Profile Photo">
    </div>
    <div class="profile-info">
        <p><strong>Admin ID:</strong> <?php echo htmlspecialchars($admin['admin_id']); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($admin['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($admin['contact_no']); ?></p>
    </div>
</div>

</body>
</html>
