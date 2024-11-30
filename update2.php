<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'cse');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session to get the logged-in student PRN
session_start();
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}
$prn = $_SESSION['user_id']; // Assuming 'user_id' holds the student's PRN from session

// Fetch existing student details to prefill the form
$sql = "SELECT * FROM students WHERE prn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $prn);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

// Handle form submission to update details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updates = [];
    $params = [];

    // Profile photo upload
    if (!empty($_FILES['profile_photo']['name'])) {
        $file_name = basename($_FILES['profile_photo']['name']);
        $target_path = 'uploads/' . $file_name;
        
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_path)) {
            $updates[] = "profile_photo = ?";
            $params[] = $file_name;
        }
    }

    // Collect other form inputs
    if (!empty($_POST['name'])) {
        $updates[] = "name = ?";
        $params[] = $_POST['name'];
    }
    if (!empty($_POST['email'])) {
        $updates[] = "email = ?";
        $params[] = $_POST['email'];
    }
    if (!empty($_POST['contact_no'])) {
        $updates[] = "contact_no = ?";
        $params[] = $_POST['contact_no'];
    }
    if (!empty($_POST['id_card_no'])) {
        $updates[] = "id_card_no = ?";
        $params[] = $_POST['id_card_no'];
    }
    if (!empty($_POST['year_of_study'])) {
        $updates[] = "year_of_study = ?";
        $params[] = $_POST['year_of_study'];
    }
    if (!empty($_POST['semester'])) {
        $updates[] = "semester = ?";
        $params[] = $_POST['semester'];
    }
    if (!empty($_POST['cgpa'])) {
        $updates[] = "cgpa = ?";
        $params[] = $_POST['cgpa'];
    }
    if (!empty($_POST['skills'])) {
        $updates[] = "skills = ?";
        $params[] = $_POST['skills'];
    }

    if (count($updates) > 0) {
        $sql = "UPDATE students SET " . implode(", ", $updates) . " WHERE prn = ?";
        $stmt = $conn->prepare($sql);
        $types = str_repeat("s", count($params)) . "s";
        $params[] = $prn;
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo "<p>Details updated successfully!</p>";
        } else {
            echo "<p>Error updating details: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>No details to update.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Personal Details</title>
    <style>
        /* General Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* Form Container */
form {
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    padding: 30px;
    width: 90%;
    max-width: 600px;
    animation: fadeIn 1s ease-in-out;
}

/* Form Title */
h2 {
    text-align: center;
    color: #4CAF50;
    font-size: 1.8em;
    margin-bottom: 20px;
}

/* Form Group */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
    transition: color 0.3s ease;
}

.form-group input,
.form-group select,
button {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border-radius: 4px;
    border: 1px solid #ddd;
    box-sizing: border-box;
    transition: box-shadow 0.3s ease, border 0.3s ease;
}

/* Placeholder and Focus Styles */
input::placeholder {
    color: #999;
    font-size: 0.9rem;
}

input:focus,
select:focus {
    border: 1px solid #4CAF50;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    outline: none;
}

/* Button Styling */
button {
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
    border: none;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
    background-color: #45a049;
    transform: scale(1.03);
}

button:active {
    transform: scale(0.98);
}

/* Profile Photo Styling */
.form-group img {
    margin-top: 10px;
    width: 80px;
    height: auto;
    border-radius: 50%;
    transition: transform 0.3s ease;
}

.form-group img:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

    </style>
</head>
<body>
    <h2>Update Personal Details</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <!-- Profile Photo -->
        <div class="form-group">
            <label for="profile_photo">Profile Photo (optional):</label>
            <input type="file" id="profile_photo" name="profile_photo">
            <?php if (!empty($student['profile_photo'])): ?>
                <div><img src="uploads/<?php echo htmlspecialchars($student['profile_photo']); ?>" width="80" alt="Current Profile Photo"></div>
            <?php endif; ?>
        </div>

        <!-- Name -->
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>">
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>">
        </div>

        <!-- Contact Number -->
        <div class="form-group">
            <label for="contact_no">Contact Number:</label>
            <input type="text" id="contact_no" name="contact_no" value="<?php echo htmlspecialchars($student['contact_no']); ?>">
        </div>

        <!-- PRN Number (readonly) -->
        <div class="form-group">
            <label for="prn">PRN Number:</label>
            <input type="text" id="prn" name="prn" value="<?php echo htmlspecialchars($student['prn']); ?>" readonly>
        </div>

        <!-- ID Card Number -->
        <div class="form-group">
            <label for="id_card_no">ID Card Number:</label>
            <input type="text" id="id_card_no" name="id_card_no" value="<?php echo htmlspecialchars($student['id_card_no']); ?>">
        </div>

        <!-- Year of Study -->
        <div class="form-group">
            <label for="year_of_study">Year of Study:</label>
            <select id="year_of_study" name="year_of_study">
                <option value="1st year" <?php if ($student['year_of_study'] == '1st year') echo 'selected'; ?>>1st Year</option>
                <option value="2nd year" <?php if ($student['year_of_study'] == '2nd year') echo 'selected'; ?>>2nd Year</option>
                <option value="3rd year" <?php if ($student['year_of_study'] == '3rd year') echo 'selected'; ?>>3rd Year</option>
                <option value="4th year" <?php if ($student['year_of_study'] == '4th year') echo 'selected'; ?>>4th Year</option>
            </select>
        </div>

        <!-- Semester -->
        <div class="form-group">
            <label for="semester">Semester:</label>
            <input type="number" id="semester" name="semester" min="1" max="8" value="<?php echo htmlspecialchars($student['semester']); ?>">
        </div>

        <!-- CGPA -->
        <div class="form-group">
            <label for="cgpa">CGPA:</label>
            <input type="text" id="cgpa" name="cgpa" value="<?php echo htmlspecialchars($student['cgpa']); ?>">
        </div>

        <!-- Skills -->
        <div class="form-group">
            <label for="skills">Skills:</label>
            <input type="text" id="skills" name="skills" value="<?php echo htmlspecialchars($student['skills']); ?>">
        </div>

        <button type="submit" name="update">Update Details</button>
    </form>
</body>
</html>
