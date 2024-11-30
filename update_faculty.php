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
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'faculty') {
    header("Location: login.php");
    exit();
}

// Get faculty ID from session
$faculty_id = $_SESSION['user_id'];

// Fetch faculty details from the database
$sql = "SELECT emp_id, name, email, contact_no, profile_photo FROM faculty WHERE emp_id = ?";
$stmt = $conn->prepare($sql);
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

// Update faculty details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];

    // Handle profile photo upload
    $profile_photo = $faculty['profile_photo'];
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $photo_path = "uploads/" . basename($_FILES["profile_photo"]["name"]);
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $photo_path);
        $profile_photo = basename($_FILES["profile_photo"]["name"]);
    }

    // Update query
    $update_sql = "UPDATE faculty SET name = ?, email = ?, contact_no = ?, profile_photo = ? WHERE emp_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssss", $name, $email, $contact_no, $profile_photo, $faculty_id);

    if ($update_stmt->execute()) {
        echo "Profile updated successfully.";
        // Refresh data
        $faculty['name'] = $name;
        $faculty['email'] = $email;
        $faculty['contact_no'] = $contact_no;
        $faculty['profile_photo'] = $profile_photo;
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
    <title>Update Faculty Profile</title>
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
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            overflow: hidden;
        }

        .update-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        .update-container h2 {
            color: #ff6f61;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .update-container input, .update-container button {
            width: 90%;
            margin-top: 15px;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .update-container input {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
        }

        .update-container button {
            background-color: #ff6f61;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        .update-container button:hover {
            transform: scale(1.05);
            background-color: #e65c54;
        }

        .update-container img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #ff6f61;
            animation: bounceIn 1.5s ease;
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

        @keyframes bounceIn {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }

    </style>
</head>
<body>

<div class="update-container">
    <h2>Update Profile</h2>
    <form method="POST" enctype="multipart/form-data">
        <div>
            <img src="uploads/<?php echo htmlspecialchars($faculty['profile_photo']); ?>" alt="Profile Photo">
        </div>
        <input type="text" name="name" value="<?php echo htmlspecialchars($faculty['name']); ?>" required placeholder="Name">
        <input type="email" name="email" value="<?php echo htmlspecialchars($faculty['email']); ?>" required placeholder="Email">
        <input type="text" name="contact_no" value="<?php echo htmlspecialchars($faculty['contact_no']); ?>" required placeholder="Contact Number">
        <input type="file" name="profile_photo" accept="image/*">
        <button type="submit">Update Profile</button>
    </form>
</div>

</body>
</html>