<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cse"; // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$prn = $_SESSION['user_id']; // PRN is stored in session after login
$certificate_names = $_POST['certificate_name'];
$certificate_files = $_FILES['certificate_file'];

$upload_dir = "uploads/certificates/"; // Directory where files will be uploaded

// Ensure the upload directory exists
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true); // Create directory if not exists
}

for ($i = 0; $i < count($certificate_names); $i++) {
    $certificate_name = $certificate_names[$i];
    $certificate_file_name = basename($certificate_files['name'][$i]);
    $unique_file_name = uniqid() . "_" . $certificate_file_name; // Add unique ID to prevent overwrites
    $target_file = $upload_dir . $unique_file_name;

    // Upload file
    if (move_uploaded_file($certificate_files['tmp_name'][$i], $target_file)) {
        // Insert into database
        $sql = "INSERT INTO certificates (prn, certificate_name, certificate_file) VALUES ('$prn', '$certificate_name', '$unique_file_name')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Certificate added successfully!<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error uploading file $certificate_file_name<br>";
    }
}

$conn->close();
?>
