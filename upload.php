<?php
// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
include 'db.php';

// Check if $pdo is defined
if (!isset($pdo)) {
    die("Database connection could not be established.");
}

// Check if form was submitted with files
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['files'])) {
    // Sanitize and retrieve form inputs
    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
    $semester = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);
    // Updated sanitization method
    $subjectName = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validate year, semester, and subject
    if (!$year || !$semester || !$subjectName) {
        die("Invalid input. Please check your selections and try again.");
    }

    // Fetch subject_id from subjects table
    // Update the column name here if it's different
    $stmt = $pdo->prepare("SELECT id FROM subjects WHERE subject_name = ?");
    $stmt->execute([$subjectName]);
    $subject = $stmt->fetch();

    // Check if subject exists
    if (!$subject) {
        die("Subject '{$subjectName}' does not exist in the database.");
    }
    
    $subjectId = $subject['id']; // Get the subject ID

    // Directory to store uploads
    $uploadDir = "uploads/questions/{$year}/{$semester}/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Process each uploaded file
    foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
        if ($_FILES['files']['error'][$index] === UPLOAD_ERR_OK) {
            $originalName = $_FILES['files']['name'][$index];
            $filePath = $uploadDir . basename($originalName);

            // Move file to upload directory
            if (move_uploaded_file($tmpName, $filePath)) {
                // Prepare to insert file data into the database
                $stmt = $pdo->prepare("INSERT INTO questions (year, semester, subject_id, file_path) VALUES (?, ?, ?, ?)");

                // Execute the insert statement
                try {
                    if ($stmt->execute([$year, $semester, $subjectId, $filePath])) {
                        echo "File '{$originalName}' uploaded and stored successfully.<br>";
                    } else {
                        echo "Failed to store '{$originalName}' in database.<br>";
                    }
                } catch (PDOException $e) {
                    echo "Database error: " . $e->getMessage() . "<br>";
                }
            } else {
                echo "Failed to upload file: {$originalName}<br>";
            }
        } else {
            echo "Error uploading file {$originalName}. Please try again.<br>";
        }
    }
} else {
    echo "No files were uploaded.";
}
?>
