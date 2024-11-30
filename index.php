<?php
// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
include 'db.php';

// Define subjects by year and semester
$subjectsByYear = [
    1 => [
        1 => ["Engineering Math-1", "Engineering Chemistry", "Engineering Mechanics", "Computer Programming in C"],
        2 => ["Engineering Math-2", "Engineering Physics", "Communication Skills", "Engineering Graphics", "Energy and Environmental Engineering"]
    ],
    2 => [
        3 => ["Engineering Math-3", "Discrete Math", "Data Structure", "OOPS in Java", "Computer Architecture and Organisation"],
        4 => ["Design and Analysis of Algorithm", "Operating System", "Probability and Statistics", "Digital Logic Design and Microprocessor", "Basic Human Rights", "Universal Human Values-II"]
    ],
    3 => [
        5 => ["Database System", "Theory of Computation", "Human-Computer Interaction", "Software Engineering", "Business Communication"],
        6 => ["Computer Networks", "Compiler Design", "Machine Learning", "Internet of Things", "Development Engineering"]
    ],
    4 => [
        7 => ["Artificial Intelligence", "Cloud Computing", "Big Data Analysis", "Blockchain Technology", "Virtual Reality"],
        8 => [] // No subjects for 8th semester
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Past Year Questions</title>
    <link rel="stylesheet" href="styless.css">
</head>
<body>
    <h1>Upload CSE Past Year Questions</h1>

    <!-- Upload Form -->
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="year">Year:</label>
        <select name="year" id="year" required onchange="updateSubjects()">
            <?php for ($i = 1; $i <= 4; $i++) echo "<option value=\"$i\">$i Year</option>"; ?>
        </select>

        <label for="semester">Semester:</label>
        <select name="semester" id="semester" required onchange="updateSubjects()">
            <?php for ($i = 1; $i <= 8; $i++) echo "<option value=\"$i\">Semester $i</option>"; ?>
        </select>

        <label for="subject">Subject:</label>
        <select name="subject" id="subject" required>
            <!-- Subject options will be populated based on year and semester selection -->
        </select>

        <div id="fileUploads">
            <label>Upload Files:</label>
            <input type="file" name="files[]" required>
        </div>
        
        <button type="button" onclick="addFileInput()">Add Another File</button>
        <button type="submit">Upload</button>
    </form>

    <script>
        const subjectsByYear = <?php echo json_encode($subjectsByYear); ?>;

        function updateSubjects() {
            const year = document.getElementById("year").value;
            const semester = document.getElementById("semester").value;                                 
            const subjectSelect = document.getElementById("subject");
            subjectSelect.innerHTML = "";

            if (subjectsByYear[year] && subjectsByYear[year][semester]) {
                subjectsByYear[year][semester].forEach(subject => {
                    const option = document.createElement("option");
                    option.value = subject;
                    option.textContent = subject;
                    subjectSelect.appendChild(option);
                });
            } else {
                const option = document.createElement("option");
                option.value = "";
                option.textContent = "No subjects available";
                subjectSelect.appendChild(option);
            }
        }

        function addFileInput() {
            const fileUploads = document.getElementById("fileUploads");
            const newFileInput = document.createElement("input");
            newFileInput.setAttribute("type", "file");
            newFileInput.setAttribute("name", "files[]");
            fileUploads.appendChild(newFileInput);
        }

        window.onload = updateSubjects;
    </script>
</body>
</html>
