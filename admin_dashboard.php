<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            transition: all 0.3s ease;
        }

        /* Header */
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            letter-spacing: 1px;
            animation: fadeIn 1.5s ease-out;
        }

        /* Sidebar */
        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #333;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar a {
            padding: 12px 16px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s;
            font-weight: bold;
        }

        .sidebar a:hover {
            background-color: #575757;
            color: #f0f0f0;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            color: white;
            cursor: pointer;
            padding: 10px;
        }

        /* Sidebar width when open */
        .sidebar.open {
            width: 250px;
        }

        /* Submenu */
        .submenu {
            display: none; /* Hidden by default */
            padding-left: 20px;
            animation: slideIn 0.5s ease-out;
        }

        .submenu a {
            font-size: 16px;
        }

        /* Main Content */
        .main-content {
            padding: 20px;
            margin-left: 20px;
            transition: margin-left 0.3s ease;
            animation: fadeIn 1s ease-out;
        }

        /* Welcome Section */
        .welcome-section {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            line-height: 1.6;
            text-align: justify;
        }

        .welcome-section h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .welcome-section p {
            color: #555;
            font-size: 16px;
        }

        /* 3 Dots for Sidebar Toggle */
        .three-dots {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 36px;
            cursor: pointer;
            color: darkred; /* Dark Red color */
            z-index: 1000;
        }

        /* Animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(0);
            }
        }

        /* Hover Effects */
        a {
            transition: all 0.3s ease;
        }

        a:hover {
            transform: scale(1.05);
        }

        /* Button Styles for Actions */
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .footer {
            background: linear-gradient(to right, #333, #444);
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 14px;
            box-shadow: 0px -4px 6px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <marquee direction="right"><h2>Welcome to Your Personal Dashboard</h2></marquee>
    </div>

    <!-- 3 Dots to Toggle Sidebar -->
    <div class="three-dots" onclick="openSidebar()">&#x22EE;</div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
        <a href="admin_profile.php">Profile</a>
        <a href="update_admin.php">Update Details</a>
        <a href="faculty_info.php">Faculty Information</a>
        <a href="student_info.php">Student Information</a>

        <!-- Study Materials Submenu -->
        <a href="javascript:void(0)" onclick="toggleSubmenu('studyMaterialsSubmenu')">Study Materials &#x25BC;</a>
        <div id="studyMaterialsSubmenu" class="submenu">
            <a href="pyq/index.php">Add Past Year Questions</a>
            <a href="pyq/view_pyq.php">View Past Year Questions</a>
        </div>

        <a href="attendance/attendence_entry2.php">Student Attendance</a>
        <a href="me1/index.php">Manage Notifications</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h3>Welcome to the Admin Dashboard</h3>
        <p>Select options from the sidebar to manage students or your account details.</p>
        <a href="student_info.php" class="btn">View Student Information</a>

        <!-- Welcome Section -->
        <div class="welcome-section">
            <h3>Welcome to CSE</h3>
            <p>Established in the year 2003, Department of Computer Science and Engineering at SSGBCOET, Bhusawal has both Under Graduate and Post Graduate Program with affiliation to Kaviyatri Bahinabai Chaudhari North Maharashtra University, Jalgaon and now from year 2017 affiliated to Dr. Babasaheb Ambedkar Technological University, Lonere, Maharashtra - A State Technological University. The Department has state-of-the-art infrastructure and laboratories having computing equipment supported by high-speed Ethernet and wireless networks. Department having qualified and well-experienced faculty members. Department also attracts a regular stream of visiting faculty members from industry and research professionals. Faculties are diligently involved in training students and preparing them for the future ahead. Department offers an excellent academic environment to students.</p>

            <p>Department admits 60 students every year in the B.Tech. Program (UG) and 18 students in the M.Tech. Program (PG). Department has extraordinary facilities for carrying out projects in fields such as computer vision, information and network security, image processing, soft computing, parallel computing, distributed systems, mobile computing, cloud computing, and many more.</p>
        </div>
    </div>

    <div class="footer">
        © SIMS copyright. All Rights Reserved.
    </div>

    <!-- JavaScript to Handle Sidebar and Submenu Toggle -->
    <script>
        function openSidebar() {
            document.getElementById("sidebar").classList.add("open");
        }

        function closeSidebar() {
            document.getElementById("sidebar").classList.remove("open");
        }

        function toggleSubmenu(submenuId) {
            var submenu = document.getElementById(submenuId);
            submenu.style.display = submenu.style.display === "block" ? "none" : "block";
        }
    </script>

</body>
</html>
