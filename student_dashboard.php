<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #f0f4f8, #d9e4f5);
        }

        /* Header */
        .header {
            background: linear-gradient(to right, #4CAF50, #388E3C);
            color: white;
            padding: 20px;
            text-align: center;
            animation: slideInDown 1s ease;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h2 {
            margin: 0;
            font-size: 2rem;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Sidebar */
        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(to bottom, #333, #444);
            overflow-x: hidden;
            transition: width 0.3s ease;
            padding-top: 60px;
            box-shadow: 4px 0px 6px rgba(0, 0, 0, 0.2);
        }

        .sidebar a {
            padding: 12px 20px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #575757;
            transform: translateX(10px);
        }

        .sidebar .closebtn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 36px;
            cursor: pointer;
            color: white;
        }

        .sidebar.open {
            width: 300px;
        }

        /* Submenu Styles */
        .submenu {
            display: none;
            background-color: #555;
        }

        .submenu a {
            font-size: 16px;
            padding-left: 30px;
        }

        .submenu a:hover {
            background-color: #666;
        }

        /* Main Content */
        .main-content {
            padding: 40px;
            margin-left: 10px;
            text-align: center;
            animation: fadeIn 1.2s ease;
        }

        .main-content h3 {
            font-size: 2rem;
            color: #333;
        }

        .main-content p {
            font-size: 1.2rem;
            color: #555;
        }

        .info-section {
            background: #ffffff;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 900px;
            text-align: justify;
            line-height: 1.6;
        }

        .info-section h4 {
            font-size: 1.5rem;
            color: #4CAF50;
            margin-bottom: 10px;
        }

        /* Footer */
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

        /* Responsive Footer */
        @media (max-width: 768px) {
            .footer {
                font-size: 12px;
                padding: 8px;
            }
        }

        /* Animations */
        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Call-to-action Buttons */
        .cta-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin: 20px;
        }

        .cta-button:hover {
            background-color: #45a049;
            transform: scale(1.1);
        }

        /* 3 Dots for Sidebar Toggle */
        .three-dots {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 30px;
            cursor: pointer;
            color: #333;
            animation: bounce 1.5s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
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
        <a href="profile2.php">Profile</a>
        <a href="javascript:void(0)" onclick="toggleSubmenu('updateSubmenu')">Update Details &#x25BC;</a>
        <div id="updateSubmenu" class="submenu">
            <a href="update2.php">Personal Details</a>
            <a href="board.html">Add Board Exam Details</a>
            <a href="cetificate.html">Add Certificates</a>
        </div>
        <a href="javascript:void(0)" onclick="toggleSubmenu('studyMaterialsSubmenu')">Study Materials &#x25BC;</a>
        <div id="studyMaterialsSubmenu" class="submenu">
            <a href="pyq/view_pyq.php">View Past Year Questions</a>
        </div>
        <a href="attendance/student_attendence.php">My Attendance</a>
        <a href="me1/index.php">Notification</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h3>Welcome to the Student Dashboard</h3>
        <p>Select options from the sidebar to manage your account and access resources.</p>
        <div class="info-section">
            <h4>Welcome to CSE</h4>
            <p>
                Established in the year 2003, Department of Computer Science and Engineering at SSGBCOET, Bhusawal has both
                Under Graduate and Post Graduate Program with affiliation to Kaviyatri Bahinabai Chaudhari North Maharashtra
                University, Jalgaon and now from year 2017 affiliated to Dr. Babasaheb Ambedkar Technological University, Lonere,
                Maharashtra - A State Technological University. The Department has state of the art infrastructure and laboratories
                having computing equipment supported by high speed Ethernet and wireless networks. Department having qualified and
                well-experienced faculty members. Department also attracts a regular stream of visiting faculty members from industry 
                and research professionals. Faculties are diligently involved in training students and preparing them for the future ahead. 
                Department offers an excellent academic environment to students.Department admits 60 students every year in the B.Tech. 
                Program (UG) and 18 students in the M.Tech. Program (PG). Department has extra ordinary facilities for carrying out projects 
                in fields such as computer vision, information and network security, image processing, soft computing, parallel computing, 
                distributed systems, mobile computing, cloud computing, and many more.
            </p>
        </div>
    </div>
    <div class="footer">
        © SIMS copyright. All Rights Reserved.
    </div>

    <!-- JavaScript to Handle Sidebar and Submenus -->
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
