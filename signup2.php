<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Default password for XAMPP MySQL
$dbname = "cse"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = $_POST['user_type'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    // Handle file upload for profile photo
    $profile_photo = $_FILES['profile_photo']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($profile_photo);

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
    }

    if (!move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
        die("Error uploading file.");
    }

    // Insert data based on user type
    $user_queries = [
        'student' => "INSERT INTO students (prn, name, email, contact_no, password, profile_photo) VALUES (?, ?, ?, ?, ?, ?)",
        'faculty' => "INSERT INTO faculty (emp_id, name, email, contact_no, password, profile_photo) VALUES (?, ?, ?, ?, ?, ?)",
        'admin'   => "INSERT INTO admin (admin_id, name, email, contact_no, password, profile_photo) VALUES (?, ?, ?, ?, ?, ?)"
    ];

    // Define the unique field based on user type
    if ($user_type == 'student') {
        $unique_field = $_POST['prn'];
        $table = 'students';
        $field = 'prn';
    } elseif ($user_type == 'faculty') {
        $unique_field = $_POST['emp_id'];
        $table = 'faculty';
        $field = 'emp_id';
    } else {
        $unique_field = $_POST['admin_id'];
        $table = 'admin';
        $field = 'admin_id';
    }

    // Check if the unique ID already exists
    $check_query = $conn->prepare("SELECT $field FROM $table WHERE $field = ?");
    $check_query->bind_param("s", $unique_field);
    $check_query->execute();
    $check_query->store_result();

    if ($check_query->num_rows > 0) {
        echo "<script>alert('". ucfirst($field) . " already exists. Please use a different one.');</script>";
    } else {
        // Prepare insertion query
        $stmt = $conn->prepare($user_queries[$user_type]);
        if ($stmt) {
            $stmt->bind_param("ssssss", $unique_field, $name, $email, $contact_no, $password, $profile_photo);

            if ($stmt->execute()) {
                // Display alert and redirect to login page
                echo "<script>
                        alert('". ucfirst($user_type) . " registered successfully!');
                        window.location.href = 'login.html';
                      </script>";
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing query: " . $conn->error;
        }
    }
    $check_query->close();
}

$conn->close();
?>
