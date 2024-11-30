<?php
require_once 'tcpdf/TCPDF-main/tcpdf.php';; // Include TCPDF library

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'cse';

// Get the semester from the request
$semester = isset($_GET['semester']) ? $_GET['semester'] : '';
if (!$semester) {
    die('Semester not selected');
}

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch student attendance data
$sql = "SELECT s.prn, s.name, 
        SUM(a.status = 'Present') AS present_days,
        SUM(a.status = 'Absent') AS absent_days,
        COUNT(a.status) AS total_days
        FROM students s
        LEFT JOIN attendance a ON s.prn = a.prn
        WHERE s.semester = ?
        GROUP BY s.prn";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $semester);
$stmt->execute();
$result = $stmt->get_result();

// Prepare data for the report
$students = [];
while ($row = $result->fetch_assoc()) {
    $attendance_percentage = $row['total_days'] > 0 
        ? round(($row['present_days'] / $row['total_days']) * 100, 2)
        : 0;
    $students[] = [
        'prn' => $row['prn'],
        'name' => $row['name'],
        'present' => $row['present_days'],
        'absent' => $row['absent_days'],
        'attendance' => $attendance_percentage
    ];
}

$stmt->close();
$conn->close();

// Create the PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Attendance Report');
$pdf->SetHeaderData('', 0, 'Attendance Report', "Semester: $semester");

// Set margins
$pdf->SetMargins(10, 10, 10);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);

// Add a page
$pdf->AddPage();

// Add content to the PDF
$pdf->SetFont('helvetica', '', 12);
$html = '<h2 style="text-align: center;">Attendance Report - Semester ' . $semester . '</h2>';
$html .= '<table border="1" cellspacing="3" cellpadding="4">
            <thead>
                <tr>
                    <th>PRN</th>
                    <th>Name</th>
                    <th>Present Days</th>
                    <th>Absent Days</th>
                    <th>Attendance (%)</th>
                </tr>
            </thead>
            <tbody>';

foreach ($students as $student) {
    $html .= '<tr>
                <td>' . htmlspecialchars($student['prn']) . '</td>
                <td>' . htmlspecialchars($student['name']) . '</td>
                <td>' . htmlspecialchars($student['present']) . '</td>
                <td>' . htmlspecialchars($student['absent']) . '</td>
                <td>' . htmlspecialchars($student['attendance']) . '%</td>
              </tr>';
}

$html .= '</tbody></table>';
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF
$pdf->Output('Attendance_Report_Semester_' . $semester . '.pdf', 'D');
?>
