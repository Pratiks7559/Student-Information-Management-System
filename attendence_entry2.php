<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: #333;
}

h2 {
    margin-bottom: 20px;
    color: #fff;
    font-size: 2em;
    font-weight: bold;
    animation: fadeIn 1s ease-in-out;
}

/* Form Styling */
form {
    background: #ffffff;
    padding: 30px;
    width: 400px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    transition: transform 0.3s;
}

form:hover {
    transform: scale(1.02);
}

label {
    font-weight: bold;
    color: #333;
}

input[type="date"],
select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 20px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
    transition: border 0.3s, box-shadow 0.3s;
}

input[type="date"]:focus,
select:focus {
    border-color: #6a11cb;
    box-shadow: 0 0 8px rgba(106, 17, 203, 0.3);
}

/* Button Styling */
button[type="submit"] {
    width: 100%;
    padding: 12px;
    font-size: 1em;
    font-weight: bold;
    color: #fff;
    background-color: #6a11cb;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
}

button[type="submit"]:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

button[type="submit"]:hover:enabled {
    background-color: #2575fc;
    transform: translateY(-3px);
}

/* Student List Styling */
#student-list {
    margin-top: 15px;
    display: flex;
    flex-direction: column;
}

#student-list tr {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    background: #f9f9f9;
    border-radius: 5px;
    margin-bottom: 8px;
    animation: fadeInUp 0.5s ease;
}

#student-list tr:nth-child(odd) {
    background: #e3e3e3;
}

.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.3s;
}

.btn:hover {
    transform: scale(1.05);
}

.btn-present {
    background-color: transparent;
    color: green;
}

.btn-absent {
    background-color: transparent;
    color: red;
}

.btn-present.active {
    background-color: green;
    color: #fff;
}

.btn-absent.active {
    background-color: red;
    color: #fff;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

    </style>
</head>
<body>
    <h2>Mark Attendance</h2>
    <form method="post" action="mark_attendance2.php">
        <label>Select Date:</label>
        <input type="date" name="attendance_date" required><br><br>

        <label>Select Semester:</label>
        <select name="semester" id="semester" required>
            <option value="">Select Semester</option>
            <option value="1">1st Semester</option>
            <option value="2">2nd Semester</option>
            <option value="3">3rd Semester</option>
            <option value="4">4th Semester</option>
            <option value="5">5th Semester</option>
            <option value="6">6th Semester</option>
            <option value="7">7th Semester</option>
            <option value="8">8th Semester</option>
        </select><br><br>

        <label>Select Attendance Status (For Selected Students):</label><br>
        <div id="student-list">
            <!-- Student list will be dynamically loaded here -->
        </div><br>

        <button type="submit" name="submit" disabled>Mark Attendance</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#semester').change(function() {
                var semester = $(this).val();
                if (semester != "") {
                    $.ajax({
                        url: 'fetch_student2.php',
                        method: 'POST',
                        data: { semester: semester },
                        success: function(response) {
                            $('#student-list').html(response);
                            attendanceData = {}; // Reset attendance data
                            document.querySelector('button[name="submit"]').disabled = true; // Disable the submit button initially
                        }
                    });
                } else {
                    $('#student-list').html("");
                }
            });
        });

        let attendanceData = {}; // Store attendance status for each student

        function markAttendance(prn, status, button) {
            // Store attendance status in attendanceData
            attendanceData[prn] = status;

            // Reset styles for both buttons in the row
            const buttons = button.parentElement.querySelectorAll('.btn');
            buttons.forEach(btn => {
                btn.style.backgroundColor = 'transparent';
                btn.style.color = btn.innerText === 'Present' ? 'green' : 'red';
            });

            // Style the selected button
            button.style.backgroundColor = status === 'Present' ? 'green' : 'red';
            button.style.color = 'white';

            // Check if all students have been marked
            checkAllMarked();
        }

        function checkAllMarked() {
            // Enable the submit button only if every student has been marked "Present" or "Absent"
            const studentCount = document.querySelectorAll('#student-list tr').length;
            const allMarked = Object.keys(attendanceData).length === studentCount;

            document.querySelector('button[name="submit"]').disabled = !allMarked;
        }

        // Attach attendance data to the form on submission
        document.querySelector('form').onsubmit = function(e) {
            const form = e.target;
            for (const [prn, status] of Object.entries(attendanceData)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `attendance[${prn}]`;
                input.value = status;
                form.appendChild(input);
            }
        };
    </script>
</body>
</html>
