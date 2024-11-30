<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_type'])) {
    $user_type = $_POST['user_type'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'cse');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "";
    if ($user_type === 'student') {
        $query = "SELECT prn AS id, name FROM students";
    } elseif ($user_type === 'faculty') {
        $query = "SELECT emp_id AS id, name FROM faculty";
    } elseif ($user_type === 'admin') {
        $query = "SELECT admin_id AS id, name FROM admin";
    }

    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
        }
    }

    $conn->close();
}
?>
