<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: lightslategray;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        .sidebar p {
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .sidebar a {
            color: white;
            font-size: 1.2em;
            display: block;
            padding: 15px;
            text-decoration: none;
            border-bottom: 1px solid #333;
        }

        .sidebar a:hover {
            background-color: #444;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        /* Library System Button */
        .library-btn {
            background-color: #f1c40f;
            border: none;
            color: black;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            position: absolute;
            left: 270px;
            top: 20px;
            cursor: pointer;
            z-index: 999;
            font-family: Arial, sans-serif;
        }

        .library-btn:hover {
            background-color: #d4ac0d;
        }

        .card {
            border-radius: 10px;
            color: white;
            text-align: center;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5em;
            margin-bottom: 20px;
            height: 250px;
            width: 250px;
        }

        .card .icon {
            font-size: 3em;
            margin-right: 10px;
        }

        .card-blue {
            background-color: #3498db;
        }

        .card-green {
            background-color: #2ecc71;
        }

        .card-orange {
            background-color: #e67e22;
        }

        .card-yellow {
            background-color: #f1c40f;
        }

        .card-red {
            background-color: #e74c3c;
        }

        /* Styling for Logout Button and Header Divider */
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            z-index: 1000;
        }

        .logout-btn:hover {
            background-color: #0056b3;
        }

        .header-divider {
            position: absolute;
            top: 70px;
            right: 0;
            left: 0;
            height: 2px;
            background-color: #ddd;
            z-index: 999;
        }

        .main-content .row {
            margin-top: 100px;
        }

        .chart-container {
            margin-top: 40px;
        }

        canvas {
            max-width: 100%;
            height: 400px;
        }

        /* Styling for Recent Activities */
        .recent-activities {
            margin-top: 40px;
        }

        .recent-activities h4 {
            font-size: 1.8em;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .recent-activities ul {
            list-style: none;
            padding-left: 0;
        }

        .recent-activities li {
            background-color: #f8f9fa;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
            font-size: 1.2em;
        }

        /* Styling for Filter Section */
        .filter-section {
            margin-top: 20px;
            padding: 20px;
        }

        .filter-section label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-control {
            width: 100%;
        }

        button.btn {
            margin-top: 10px;
        }

        /* Notification Bell for Book Requests */
        .notification-bell {
            position: absolute;
            top: 20px;
            right: 150px;
            font-size: 1.5em;
            cursor: pointer;
            color: black;
        }

        .notification-bell .badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: white;
            font-size: 0.8em;
            padding: 5px 8px;
            border-radius: 50%;
        }

        /* Dropdown for Notifications */
        .notification-dropdown {
            position: absolute;
            right: 100px;
            top: 50px;
            background-color: white;
            border: 1px solid #ccc;
            width: 300px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
        }

        .notification-dropdown ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .notification-dropdown ul li {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .notification-dropdown ul li:last-child {
            border-bottom: none;
        }

        .notification-dropdown ul li a {
            text-decoration: none;
            color: #333;
        }

        .notification-dropdown ul li a:hover {
            text-decoration: underline;
        }

    </style>
</head>

<body>

    <div class="sidebar">
        <p>Admin Panel</p>
        <a href="#">Homepage</a>
        <a href="#">Manage Books</a>
        <a href="#">Manage Users</a>
        <a href="#">Reports</a>
    </div>

    <!-- Library System Button next to the sidebar -->
    <button class="library-btn">Library System</button>

    <!-- Notification Bell -->
    <div class="notification-bell">
        <i class="fas fa-bell"></i>
        <span class="badge">3</span> <!-- Replace "3" with dynamic count of new requests -->
    </div>

    <!-- Notification Dropdown -->
    <div class="notification-dropdown">
        <ul>
            <li><a href="#">John requested "Book 1"</a></li>
            <li><a href="#">Emily requested "Book 2"</a></li>
            <li><a href="#">Adam requested "Book 3"</a></li>
        </ul>
    </div>

    <button class="logout-btn">Logout</button>

    <div class="header-divider"></div>

    <div class="main-content">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-blue">
                    <i class="fas fa-book icon"></i>
                    <div>Total Books</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-green">
                    <i class="fas fa-shopping-cart icon"></i>
                    <div>Borrowed Books</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-orange">
                    <i class="fas fa-users icon"></i>
                    <div>Users</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card card-red">
                    <i class="fas fa-undo icon"></i>
                    <div>Returned Books</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-yellow">
                    <i class="fas fa-exclamation-triangle icon"></i>
                    <div>Overdue Books</div>
                </div>
            </div>
        </div>

        <!-- Search Filter Section -->
        <div class="filter-section">
            <h5>Filters</h5>
            <form id="filterForm">
                <div class="form-row align-items-center">
                    <div class="col-md-2">
                        <label for="startDate">Start Date:</label>
                        <input type="date" id="startDate" name="start_date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="endDate">End Date:</label>
                        <input type="date" id="endDate" name="end_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="bookCategory">Book Category:</label>
                        <select id="bookCategory" name="book_category" class="form-control">
                            <option value="">All Categories</option>
                            <option value="fiction">Fiction</option>
                            <option value="nonfiction">Non-Fiction</option>
                            <option value="science">Science</option>
                            <option value="history">History</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="userRole">User Role:</label>
                        <select id="userRole" name="user_role" class="form-control">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Chart Section -->
        <div class="chart-container">
            <canvas id="myChart"></canvas>
        </div>

        <!-- Recent Activities Section -->
        <div class="recent-activities">
            <h4>Recent Activities</h4>
            <ul>
                <li>User John borrowed "The Great Gatsby".</li>
                <li>User Emily returned "Moby Dick".</li>
                <li>Admin updated the "Library Rules".</li>
                <li>User Adam reserved "War and Peace".</li>
            </ul>
        </div>

        <!-- Export Buttons -->
        <div class="export-buttons">
            <button class="btn btn-secondary">Export as PDF</button>
            <button class="btn btn-secondary">Export as Excel</button>
        </div>
    </div>

    <!-- Load Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Books', 'Borrowed Books', 'Users', 'Returned Books', 'Overdue Books'],
                datasets: [{
                    label: 'Library Statistics',
                    data: [50, 30, 120, 45, 5],
                    backgroundColor: ['#3498db', '#2ecc71', '#e67e22', '#e74c3c', '#f1c40f'],
                    borderColor: ['#2980b9', '#27ae60', '#d35400', '#c0392b', '#f39c12'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Notification bell dropdown toggle
        const bell = document.querySelector('.notification-bell');
        const dropdown = document.querySelector('.notification-dropdown');

        bell.addEventListener('click', function() {
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Filter form submission
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var startDate = document.getElementById('startDate').value;
            var endDate = document.getElementById('endDate').value;
            var bookCategory = document.getElementById('bookCategory').value;
            var userRole = document.getElementById('userRole').value;

            console.log("Filters applied:", startDate, endDate, bookCategory, userRole);
        });
    </script>

</body>

</html>
