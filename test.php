<?php
include "./php/dbcon.php"; // Include your database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hostel Warden Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e3f2fd;
            margin: 0;
            padding: 0;
            color: #333;
        }
        header {
            background-color: #1565c0;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            background-color: #1976d2;
            margin: 0;
        }
        nav ul li {
            margin: 10px;
        }
        nav ul li a {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            display: inline-block;
            background-color: #42a5f5;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        nav ul li a:hover {
            background-color: #64b5f6;
        }
        section {
            margin: 20px auto;
            padding: 20px;
            width: 80%;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: none; /* Hide all sections by default */
        }
        button {
            padding: 10px 20px;
            margin: 5px;
            background-color: #1e88e5;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #1565c0;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #64b5f6;
            color: white;
        }
        td input[type="text"] {
            width: 80%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }
        .notice {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .delete-notice {
            background-color: #e53935;
        }
    </style>
</head>
<script>
    // Show or hide sections when navigation is clicked
    function showSection(sectionId) {
        // Hide all sections
        const sections = document.querySelectorAll('section');
        sections.forEach(section => section.style.display = 'none');

        // Show the selected section
        const selectedSection = document.getElementById(sectionId);
        selectedSection.style.display = 'block';
    }

    // Toggle visibility of room lists
    function toggleVisibility(hostelId) {
        const hostelRooms = document.getElementById(hostelId);
        hostelRooms.style.display = (hostelRooms.style.display === 'none') ? 'block' : 'none';
    }

    // Display room details based on selected hostel
    function showRooms(hostelName) {
        // Hide all room tables
        const tables = document.querySelectorAll('table');
        tables.forEach(table => table.style.display = 'none');

        // Show the table for the selected hostel
        const tableIds = {
            'Elite': 'BHEliteTable',
            'Maharaj': 'BHMahaTable',
            'Nalanda': 'BHNalaTable',
            'Saraswathi Niwas': 'BHSaraTable',
            'Bagiradha Bavan': 'BHBagiTable'
        };

        const tableId = tableIds[hostelName];
        const table = document.getElementById(tableId);
        if (table) {
            table.style.display = 'block';
        }
    }

    // Update attendance and fee for the student
    function updateStudent() {
        const rollNumber = document.getElementById('rollNumber').value;
        const attendancePercentage = document.getElementById('attendancePercentage').value;
        const feeDue = document.getElementById('feeDue').value;

        if (rollNumber && attendancePercentage && feeDue) {
            // Send the data to the server to update
            fetch('updateStudent.php', {
                method: 'POST',
                body: JSON.stringify({ rollNumber, attendancePercentage, feeDue }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Student attendance and fee details updated successfully!');
                } else {
                    alert('Error updating data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        } else {
            alert("Please fill in all fields.");
        }
    }

    // Search attendance by roll number
    function searchAttendance() {
        const searchRollNumber = document.getElementById('searchRollNumber').value;
        console.log(`Searching attendance for Roll Number: ${searchRollNumber}`);
    }
</script>
<body>
    <header>Hostel Warden Dashboard</header>

    <nav>
        <ul>
            <li><a href="javascript:void(0);" onclick="showSection('roomManagement')">Room Management</a></li>
            <li><a href="javascript:void(0);" onclick="showSection('hostelRoomDetails')">Room Details</a></li>
            <li><a href="javascript:void(0);" onclick="showSection('studentAttendance')">Attendance</a></li>
            <li><a href="javascript:void(0);" onclick="showSection('notices')">Upload Notices</a></li>
        </ul>
    </nav>

    <!-- Room Management -->
    <section id="roomManagement">
        <h2>Room Management</h2>
        <button onclick="toggleVisibility('boysHostelRooms')">Boys Hostel</button>
        <button onclick="toggleVisibility('girlsHostelRooms')">Girls Hostel</button>
        
        <div id="boysHostelRooms" style="display: none;">
            <h3>Boys Hostel Rooms</h3>
            <button onclick="showRooms('Elite')">Elite Hostel</button>
            <button onclick="showRooms('Maharaj')">Maharaj Hostel</button>
            <button onclick="showRooms('Nalanda')">Nalanda Hostel</button>
        </div>
        
        <div id="girlsHostelRooms" style="display: none;">
            <h3>Girls Hostel Rooms</h3>
            <button onclick="showRooms('Saraswathi Niwas')">Saraswathi Niwas</button>
            <button onclick="showRooms('Bagiradha Bavan')">Bagiradha Bavan</button>
        </div>
        
        <div id="hostelRooms">
            <h3>Room Details</h3>
            <table id="BHEliteTable" style="display: none;">
                <tr><th>admissionNO</th><th>Studentsname</th><th>room no</th><th>attendance</th><th>fee due</th><th>Actions</th></tr>
                <?php 
                    $sql = "SELECT * FROM boyshostel WHERE hostelname='elite'";
                    $res = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<tr><td>{$row['rollno']}</td><td>{$row['fullname']}</td><td>{$row['roomno']}</td><td></td><td></td></tr>";   
                    }
                ?>
            </table>
            <table id="BHMahaTable" style="display: none;">
                <tr><th>admissionNO</th><th>Studentsname</th><th>room no</th><th>attendance</th><th>fee due</th><th>Actions</th></tr>
                <?php 
                    $sql = "SELECT * FROM boyshostel WHERE hostelname='maharaj'";
                    $res = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<tr><td>{$row['rollno']}</td><td>{$row['fullname']}</td><td>{$row['roomno']}</td><td></td><td></td></tr>";  
                    }
                ?>
            </table>
            <table id="BHNalaTable" style="display: none;">
                <tr><th>admissionNO</th><th>Studentsname</th><th>room no</th><th>attendance</th><th>fee due</th><th>Actions</th></tr>
                <?php 
                    $sql = "SELECT * FROM boyshostel WHERE hostelname='Nalandha'";
                    $res = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<tr><td>{$row['rollno']}</td><td>{$row['fullname']}</td><td>{$row['roomno']}</td><td></td><td></td></tr>";     
                        echo(mysqli_error($conn));
                    }
                ?>
            </table>
            <table id="BHSaraTable" style="display: none;">
                <tr><th>admissionNO</th><th>Studentsname</th><th>room no</th><th>attendance</th><th>fee due</th><th>Actions</th></tr>
                <?php 
                    $sql = "SELECT * FROM  girlshostel WHERE hostelname='saraswati nivas'";
                    $res = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<tr><td>{$row['rollno']}</td><td>{$row['fullname']}</td><td>{$row['roomno']}</td><td></td><td></td></tr>";   
                    }
                ?>
            </table>
            <table id="BHBagiTable" style="display: none;">
                <tr><th>admissionNO</th><th>Studentsname</th><th>room no</th><th>attendance</th><th>fee due</th><th>Actions</th></tr>
                <?php 
                    $sql = "SELECT * FROM  girlshostel WHERE hostelname='bhagirathi bhavan'";
                    $res = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<tr><td>{$row['rollno']}</td><td>{$row['fullname']}</td><td>{$row['roomno']}</td><td></td><td></td></tr>";   
                    }
                ?>
            </table>
        </div>
    </section>

    <!-- Attendance and Fee Due -->
    <section id="studentAttendance">
        <h2>Update Attendance and Fee Due</h2>
        <input type="text" id="searchRollNumber" placeholder="Search by Roll Number" oninput="searchAttendance()">
        <table id="attendanceTable">
            <tr><th>Name</th><th>Roll Number</th><th>Hostel</th><th>Attendance (%)</th><th>Fee Due</th><th>Update</th></tr>
            <!-- Populate this table with PHP or JavaScript -->
        </table>

        <h3>Update Student Attendance and Fee</h3>
        <input type="text" id="rollNumber" placeholder="Enter Roll Number">
        <input type="text" id="attendancePercentage" placeholder="Enter Attendance Percentage">
        <input type="text" id="feeDue" placeholder="Enter Fee Due Amount">
        <button onclick="updateStudent()">Update</button>
    </section>

    <!-- Notices -->
    <section id="notices">
        <h2>Upload Notices</h2>
        <textarea id="noticeContent" placeholder="Enter notice..."></textarea>
        <button onclick="publishNotice()">Publish Notice</button>
        <h3>Published Notices</h3>
        <div id="noticeList"></div>
    </section>
</body>
</html>
