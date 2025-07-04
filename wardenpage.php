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
            display: none;
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
<body>
    <header>Hostel Warden Dashboard</header>

    <nav>
        <ul>
            <li><a onclick="showSection('roomManagement')">Room Management</a></li>
            <li><a onclick="showSection('hostelRoomDetails')">Room Details</a></li>
            <li><a onclick="showSection('studentAttendance')">Attendance</a></li>
            <li><a onclick="showSection('notices')">Upload Notices</a></li>
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
            <table id="roomTable">
                <tr><th>Room Number</th><th>Students</th><th>Actions</th></tr>
            </table>
            <br>
            <input type="text" id="studentName" placeholder="Enter student name">
            <input type="text" id="rollNumber" placeholder="Enter roll number">
            <input type="text" id="roomNumber" placeholder="Enter room number">
            <button onclick="addStudent()">Add Student</button>
        </div>
    </section>

    <!-- Room Details -->
    <section id="hostelRoomDetails">
        <h2>Select Hostel to View Room Details</h2>
        <div id="hostelList"></div>
        <table id="roomDetailsTable">
            <tr><th>Room Number</th><th>Student Name</th><th>Roll Number</th><th>Actions</th></tr>
        </table>
    </section>

    <!-- Student Attendance -->
    <section id="studentAttendance">
        <h2>Attendance</h2>
        <input type="text" id="searchRollNumber" placeholder="Search by Roll Number" oninput="searchAttendance()">
        <table id="attendanceTable">
            <tr><th>Name</th><th>Roll Number</th><th>Hostel</th><th>No. of Days Stay</th><th>Attendance (%)</th><th>Modify</th><th>DELETE</th></tr>
        </table>
    </section>

    <!-- Notices -->
    <section id="notices">
        <h2>Upload Notices</h2>
        <textarea id="noticeContent" placeholder="Enter notice..."></textarea>
        <button onclick="publishNotice()">Publish Notice</button>
        <h3>Published Notices</h3>
        <div id="noticeList"></div>
    </section>

    <script>
        let hostelData = {};  
        let attendanceData = {};
        let notices = [];

        function showSection(sectionId) {
            document.querySelectorAll('section').forEach(sec => sec.style.display = 'none');
            document.getElementById(sectionId).style.display = 'block';

            if (sectionId === "hostelRoomDetails") updateHostelRoomDetails();
            if (sectionId === "studentAttendance") updateAttendanceTable();
        }

        function toggleVisibility(id) {
            let element = document.getElementById(id);
            element.style.display = element.style.display === 'none' ? 'block' : 'none';
        }

        function showRooms(hostel) {
            let table = document.getElementById('roomTable');
            table.dataset.hostel = hostel;
            table.innerHTML = `<tr><th>Room Number</th><th>Students</th><th>Actions</th></tr>`;

            if (!hostelData[hostel]) hostelData[hostel] = {};

            for (let i = 1; i <= 10; i++) {
                let students = hostelData[hostel][i] || [];
                let row = document.createElement('tr');
                row.innerHTML = `<td>${i}</td><td>${students.length}/3</td><td><button onclick="removeStudent('${hostel}', ${i})">Remove Student</button></td>`;
                table.appendChild(row);
            }
        }

        function addStudent() {
            let name = document.getElementById("studentName").value;
            let roll = document.getElementById("rollNumber").value;
            let room = document.getElementById("roomNumber").value;
            let hostel = document.querySelector("#roomTable").dataset.hostel;

            if (!name || !roll || !room || !hostel) { alert("Fill all fields and select a hostel!"); return; }

            if (!hostelData[hostel][room]) hostelData[hostel][room] = [];
            if (hostelData[hostel][room].length >= 3) return alert("Room is full");

            hostelData[hostel][room].push({ name, roll });
            attendanceData[roll] = { name, hostel, daysStay: 0, attendancePercentage: "0%" };

            showRooms(hostel);
            updateHostelRoomDetails();
            updateAttendanceTable();
            clearInputFields();
        }

        function clearInputFields() {
            document.getElementById("studentName").value = '';
            document.getElementById("rollNumber").value = '';
            document.getElementById("roomNumber").value = '';
        }

        function removeStudent(hostel, room) {
            let roll = prompt("Enter roll number of the student to remove:");
            if (!roll || !attendanceData[roll]) {
                alert("Student not found!");
                return;
            }

            // Remove from hostel data
            let students = hostelData[hostel][room];
            hostelData[hostel][room] = students.filter(student => student.roll !== roll);

            // Remove from attendance data
            delete attendanceData[roll];

            // Update tables
            showRooms(hostel);
            updateHostelRoomDetails();
            updateAttendanceTable();
        }

        function updateHostelRoomDetails() {
            let hostelList = document.getElementById("hostelList");
            hostelList.innerHTML = '';

            for (let hostel in hostelData) {
                let button = document.createElement('button');
                button.innerText = hostel;
                button.onclick = () => showHostelDetails(hostel);
                hostelList.appendChild(button);
            }
        }

        function showHostelDetails(hostel) {
            let table = document.getElementById("roomDetailsTable");
            table.innerHTML = `<tr><th>Room Number</th><th>Student Name</th><th>Roll Number</th><th>Actions</th></tr>`;
            for (let room in hostelData[hostel]) {
                hostelData[hostel][room].forEach(student => {
                    let row = table.insertRow();
                    row.insertCell(0).innerText = room;
                    row.insertCell(1).innerText = student.name;
                    row.insertCell(2).innerText = student.roll;
                    let deleteCell = row.insertCell(3);
                    let deleteButton = document.createElement('button');
                    deleteButton.innerText = "Delete";
                    deleteButton.onclick = () => removeStudentFromRoom(hostel, room, student.roll);
                    deleteCell.appendChild(deleteButton);
                });
            }
        }

        function removeStudentFromRoom(hostel, room, roll) {
            hostelData[hostel][room] = hostelData[hostel][room].filter(student => student.roll !== roll);
            delete attendanceData[roll];
            updateAttendanceTable();
            showRooms(hostel);
            updateHostelRoomDetails();
        }

        function updateAttendanceTable() {
            let table = document.getElementById("attendanceTable");
            table.innerHTML = `<tr><th>Name</th><th>Roll Number</th><th>Hostel</th><th>No. of Days Stay</th><th>Attendance (%)</th><th>Modify</th><th>Delete</th></tr>`;

            for (let roll in attendanceData) {
                let student = attendanceData[roll];
                let row = table.insertRow();
                row.insertCell(0).innerText = student.name;
                row.insertCell(1).innerText = roll;
                row.insertCell(2).innerText = student.hostel;
                row.insertCell(3).innerText = student.daysStay;
                row.insertCell(4).innerText = student.attendancePercentage;

                let modifyCell = row.insertCell(5);
                let modifyButton = document.createElement('button');
                modifyButton.innerText = "Modify";
                modifyButton.onclick = () => modifyAttendance(roll);
                modifyCell.appendChild(modifyButton);

                let deleteCell = row.insertCell(6);
                let deleteButton = document.createElement('button');
                deleteButton.innerText = "Delete";
                deleteButton.onclick = () => removeStudentFromAttendance(roll);
                deleteCell.appendChild(deleteButton);
            }
        }

        function removeStudentFromAttendance(roll) {
            delete attendanceData[roll];
            updateAttendanceTable();
        }

        function searchAttendance() {
            let searchValue = document.getElementById("searchRollNumber").value.toLowerCase();
            let table = document.getElementById("attendanceTable");
            let rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
                let cells = rows[i].getElementsByTagName("td");
                let rollNumber = cells[1].innerText.toLowerCase();
                if (rollNumber.includes(searchValue)) {
                    rows[i].style.display = ""; // Show row
                } else {
                    rows[i].style.display = "none"; // Hide row
                }
            }
        }

        function modifyAttendance(roll) {
            let newDaysStay = prompt("Enter new number of days stay:");
            let newAttendancePercentage = prompt("Enter new attendance percentage:");

            if (newDaysStay !== null && newAttendancePercentage !== null) {
                attendanceData[roll].daysStay = parseInt(newDaysStay);
                attendanceData[roll].attendancePercentage = newAttendancePercentage + "%";
                updateAttendanceTable();
            }
        }

        function publishNotice() {
            let content = document.getElementById("noticeContent").value;
            if (!content) {
                alert("Please enter notice content!");
                return;
            }

            notices.push(content);
            displayNotices();
            document.getElementById("noticeContent").value = ''; // Clear input
        }

        function displayNotices() {
            let noticeList = document.getElementById("noticeList");
            noticeList.innerHTML = '';

            notices.forEach((notice, index) => {
                let noticeDiv = document.createElement('div');
                noticeDiv.className = 'notice';
                noticeDiv.innerText = notice;

                let deleteButton = document.createElement('button');
                deleteButton.innerText = "Delete";
                deleteButton.className = 'delete-notice';
                deleteButton.onclick = () => deleteNotice(index);
                noticeDiv.appendChild(deleteButton);

                noticeList.appendChild(noticeDiv);
            });
        }

        function deleteNotice(index) {
            notices.splice(index, 1);
            displayNotices();
        }
    </script>
</body>
</html>