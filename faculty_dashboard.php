
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            transition: all 0.3s ease;
        }

        /* Header */
        .header {
            background-color: #4CAF50; /* Green background for header */
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 20px;
            letter-spacing: 2px;
            text-transform: uppercase;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-out;
        }

        /* Sidebar */
        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #333;
            color: white;
            overflow-x: hidden;
            transition: 0.4s;
            padding-top: 60px;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.2);
        }

        .sidebar a {
            padding: 12px 20px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s;
            font-weight: bold;
            text-transform: capitalize;
            border-bottom: 1px solid #444;
        }

        .sidebar a:hover {
            background-color: #555;
            color: #f4f4f4;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .sidebar .closebtn {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 36px;
            color: white;
            cursor: pointer;
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
            padding: 10px 15px;
        }

        /* Main Content */
        .main-content {
            padding: 30px;
            margin-left: 20px;
            transition: margin-left 0.3s ease;
            animation: fadeIn 1.5s ease-out;
        }

        .main-content h3 {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #3e5e56;
            text-transform: uppercase;
            animation: fadeIn 2s ease-out;
        }

        .main-content p {
            font-size: 18px;
            color: #666;
            text-align: justify;
        }

        /* Info Paragraph Styles */
        .info {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            margin-top: 20px;
            line-height: 1.6;
            font-size: 18px;
            color: #444;
            animation: fadeIn 2s ease-out;
        }

        .info h4 {
            font-size: 24px;
            color: #3e5e56;
            margin-bottom: 15px;
        }

        /* 3 Dots for Sidebar Toggle */
        .three-dots {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 32px;
            cursor: pointer;
            color: red; /* Red color for the 3 dots */
            z-index: 1000;
            transition: color 0.3s ease;
        }

        .three-dots:hover {
            color: darkred;
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            background-color: #3e5e56;
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background-color: #2e4d44;
            transform: scale(1.05);
        }

        /* Animations */
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
        <marquee direction="right"><h2>Welcome to Your personal Dashboard</h2></marquee>
    </div>

    <!-- 3 Dots to Toggle Sidebar -->
    <div class="three-dots" onclick="openSidebar()">&#x22EE;</div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
        
        <a href="faculty_profile.php">Profile</a>
        <a href="update_faculty.php">Update Details</a>
        <a href="student_info.php">Student Information</a>

        <!-- Study Materials Submenu -->
        <a href="javascript:void(0)" onclick="toggleSubmenu('studyMaterialsSubmenu')">Study Materials &#x25BC;</a>
        <div id="studyMaterialsSubmenu" class="submenu">
            <a href="pyq/view_pyq.php">View Past Year Questions</a>
        </div>

        <a href="attendance/attendence_entry2.php">Student Attendance</a>
        <a href="attendance/report.html">Attendance Report</a>
        <a href="me1/index.php">Notifications</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h3>Welcome to the Faculty Dashboard</h3>
        <p>Select options from the sidebar to manage students or your account details.</p>

        <!-- Added Info Section -->
        <div class="info">
            <h4>Welcome to CSE</h4>
            <p>
            Established in the year 2003, Department of Computer Science and Engineering at SSGBCOET, 
            Bhusawal has both Under Graduate and Post Graduate Program with affiliation to Kaviyatri 
            Bahinabai Chaudhari North Maharashtra University, Jalgaon and now from year 2017 affiliated to 
            Dr. Babasaheb Ambedkar Technological University, Lonere, Maharashtra - A State Technological 
            University. The Department has state of the art infrastructure and laboratories having computing 
            equipment supported by high speed Ethernet and wireless networks. Department having qualified and 
            well-experienced faculty members. Department also attracts a regular stream of visiting faculty 
            members from industry and research professionals. Faculties are diligently involved in training 
            students and preparing them for the future ahead. Department offers an excellent academic environment 
            to students.Department admits 60 students every year in the B.Tech. Program (UG) and 18 students in the 
            M.Tech. Program (PG). Department has extra ordinary facilities for carrying out projects in fields such 
            as computer vision, information and network security, image processing, soft computing, parallel computing, 
            distributed systems, mobile computing, cloud computing, and many more.
            </p>
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

