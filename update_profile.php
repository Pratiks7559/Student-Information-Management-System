<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'cse');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session to get the logged-in student PRN
session_start();
$prn = $_SESSION['user_id']; // Assuming 'user_id' holds the student's PRN from session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an empty array for update queries
    $updates = array();

    // Check if profile photo is uploaded
    if (!empty($_FILES['profile_photo']['name'])) {
        $file_name = basename($_FILES['profile_photo']['name']); // Get only the file name
        $target_path = 'uploads/' . $file_name;
        
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_path)) {
            $updates[] = "profile_photo='$file_name'"; // Save only the file name in the database
        }
    }

    // Sanitize and update other fields if they are set
    if (!empty($_POST['name'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $updates[] = "name='$name'";
    }
    if (!empty($_POST['email'])) {
        $email = $conn->real_escape_string($_POST['email']);
        $updates[] = "email='$email'";
    }
    if (!empty($_POST['contact_no'])) {
        $contact_no = $conn->real_escape_string($_POST['contact_no']);
        $updates[] = "contact_no='$contact_no'";
    }
    if (!empty($_POST['id_card_no'])) {
        $id_card_no = $conn->real_escape_string($_POST['id_card_no']);
        $updates[] = "id_card_no='$id_card_no'";
    }
    if (!empty($_POST['year_of_study'])) {
        $year_of_study = $conn->real_escape_string($_POST['year_of_study']);
        $updates[] = "year_of_study='$year_of_study'";
    }
    if (!empty($_POST['semester'])) {
        $semester = intval($_POST['semester']);
        $updates[] = "semester=$semester";
    }
    if (!empty($_POST['cgpa'])) {
        $cgpa = $conn->real_escape_string($_POST['cgpa']);
        $updates[] = "cgpa='$cgpa'";
    }
    if (!empty($_POST['skills'])) {
        $skills = $conn->real_escape_string($_POST['skills']);
        $updates[] = "skills='$skills'";
    }

    // Build the SQL update query
    if (count($updates) > 0) {
        $sql = "UPDATE students SET " . implode(", ", $updates) . " WHERE prn='$prn'";
        
        if ($conn->query($sql) === TRUE) {
            echo "Details updated successfully!";
        } else {
            echo "Error updating details: " . $conn->error;
        }
    } else {
        echo "No details to update.";
    }
}

$conn->close();
?>
