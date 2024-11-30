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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Uploaded Past Year Questions</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <h1>Uploaded Past Year Questions</h1>

    <!-- Display Uploaded Questions -->
    <h2>Available Past Year Questions</h2>
    <table>
        <thead>
            <tr>
                <th>Year</th>
                <th>Semester</th>
                <th>Subject</th>
                <th>File</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                // Query to fetch questions with subjects
                $stmt = $pdo->query("
                    SELECT questions.id, questions.year, questions.semester, subjects.subject_name, questions.file_path 
                    FROM questions
                    JOIN subjects ON questions.subject_id = subjects.id
                    ORDER BY questions.year, questions.semester
                ");
                
                $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Check if any questions were retrieved
                if ($questions && count($questions) > 0) {
                    foreach ($questions as $q) {
                        echo "<tr>
                                <td>{$q['year']}</td>
                                <td>{$q['semester']}</td>
                                <td>{$q['subject_name']}</td>
                                <td>
                                    <a href='{$q['file_path']}' target='_blank'>" . basename($q['file_path']) . "</a>
                                </td>
                                <td>
                                    <a href='{$q['file_path']}' download>Download</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No question papers available.</td></tr>";
                }
            } catch (PDOException $e) {
                // Error handling for database query
                echo "<tr><td colspan='5'>Failed to retrieve questions: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
