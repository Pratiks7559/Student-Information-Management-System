<?php
$servername = "localhost";
$username = "root";
$password = ""; // Your database password
$dbname = "cse"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['semester'])) {
    $semester = $_POST['semester'];
    $sql = "SELECT prn, name FROM students WHERE semester = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $semester);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table>";
        while ($row = $result->fetch_assoc()) {
            $prn = $row['prn'];
            $name = $row['name'];
            echo "<tr>
                    <td>{$name} (PRN: {$prn})</td>
                    <td>
                        <button type='button' onclick='markAttendance(\"$prn\", \"Present\", this)' class='btn' data-status='' style='background-color: transparent; color: green; border: 1px solid green; padding: 10px; cursor: pointer;'>Present</button>
                        <button type='button' onclick='markAttendance(\"$prn\", \"Absent\", this)' class='btn' data-status='' style='background-color: transparent; color: red; border: 1px solid red; padding: 10px; cursor: pointer;'>Absent</button>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No students found for this semester.";
    }
    $stmt->close();
}

$conn->close();
?>
