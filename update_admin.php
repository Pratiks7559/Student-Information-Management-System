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

// Update admin details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];

    // Handle profile photo upload
    $profile_photo = $admin['profile_photo'];
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $photo_path = "uploads/" . basename($_FILES["profile_photo"]["name"]);
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $photo_path);
        $profile_photo = basename($_FILES["profile_photo"]["name"]);
    }

    // Update query
    $update_sql = "UPDATE admin SET name = ?, email = ?, contact_no = ?, profile_photo = ? WHERE admin_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssss", $name, $email, $contact_no, $profile_photo, $admin_id);

    if ($update_stmt->execute()) {
        echo "Profile updated successfully.";
        // Refresh data
        $admin['name'] = $name;
        $admin['email'] = $email;
        $admin['contact_no'] = $contact_no;
        $admin['profile_photo'] = $profile_photo;
    } else {
        echo "Error updating profile: " . $conn->error;
    }
    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Admin Profile</title>
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
            background: linear-gradient(135deg, #ff9a9e, #fad0c4, #fbc2eb);
            animation: gradient 6s ease infinite;
            background-size: 400% 400%;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .update-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: fadeIn 1.2s ease;
        }

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

        .update-container input, .update-container button {
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .update-container input {
            background-color: #f0f0f0;
        }

        .update-container input:focus {
            outline: none;
            background-color: #e8f0fe;
        }

        .update-container button {
            background: linear-gradient(45deg, #ff6f61, #de6161);
            color: #fff;
            cursor: pointer;
        }

        .update-container button:hover {
            background: linear-gradient(45deg, #de6161, #ff6f61);
        }

        .update-container img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #ff6f61;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
</head>
<body>

<div class="update-container">
    <h2>Update Profile</h2>
    <form method="POST" enctype="multipart/form-data">
        <div>
            <img src="uploads/<?php echo htmlspecialchars($admin['profile_photo']); ?>" alt="Profile Photo">
        </div>
        <input type="text" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>" required placeholder="Name">
        <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required placeholder="Email">
        <input type="text" name="contact_no" value="<?php echo htmlspecialchars($admin['contact_no']); ?>" required placeholder="Contact Number">
        <input type="file" name="profile_photo" accept="image/*">
        <button type="submit">Update Profile</button>
    </form>
</div>

</body>
</html>
