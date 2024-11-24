<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - User Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
       /* Header Section */
       .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .header .btn {
            margin-left: 10px;
        }
        .sidebar {
           padding-top: 20px;
           background-color:  #fdf5e6;
           border-right: 1px solid #dee2e6;
           overflow-y: auto;

       }
       .sidebar .nav-link {
           margin: 10px 0;
           font-size: 16px;
           padding: 10px 15px;
           color: #333;
           text-decoration: auto;
       }
       .sidebar .nav-link:hover {
           background-color: #e9ecef;
           color: #007bff;
       }
       .sidebar p {
            margin-left: 15px;
            margin-top: 10px;
            font-size: 16px;
        }
       .sidebar .btn {
    display: block;
    width: 90%; /* Adjust the button width */
    margin: 10px auto; /* Centers the button within the sidebar */
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    text-align: center;
    font-size: 16px;
    text-decoration: none;
    margin-left: 20px; /* Add left margin to move the button to the right */
}

        /* Cards Layout for Library Stats */
        .card {
            text-align: center;
            padding: 30px 20px;
            border-radius: 8px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 180px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-blue { background-color: #007bff; /* Bootstrap Blue */ }
        .card-green { background-color: #28a745; /* Bootstrap Green */ }
        .card-orange { background-color: #fd7e14; /* Bootstrap Orange */ }
        .card-red { background-color: #dc3545; /* Bootstrap Red */ }
        .card-yellow { background-color: #ffc107; /* Bootstrap Yellow */ }

        .card .icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .card-value {
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <nav class="nav flex-column">
                    <p>Features</p>
                    <a href="{{ route('User.homepage') }}" class="btn btn-primary btn-block w-50 mb-3">Homepage</a>
                    <a href="{{ route('Admin.booklistadmin') }}" class="btn btn-primary btn-block w-50 mb-3">Manage Books</a>
                    <a href="{{ route('genres.create') }}" class="btn btn-primary btn-block w-50 mb-3">Add Genre</a>
                    <a href="{{ route('admin.superadmin.panel') }}" class="btn btn-primary btn-block w-50 mb-3">Super Admin</a>
                   @if(Auth::user()->role === 'superadmin')
                   <a href="{{route('Admin.userregister')}}" class="btn btn-primary btn-block w-50 mb-3">User Register</a>
                   @endif
                   <a href="{{route('Admin.usermanagementadmin')}}" class="btn btn-primary btn-block w-50 mb-3">User Update</a>
                </nav>
            </div>

            <div class="col-md-10">
                <div class="header">
                    <button class="btn btn-warning">Library System</button>
                    <div class="d-flex">
                        <span class="navbar-text">
                            {{Auth::user()->name}}
                        </span>
                        <form id="logout-form" action="{{route('logout')}}" method="POST" style="display:inline;">
                            @csrf 
                        <button class="btn btn-primary ml-2" id="logoutButton">ログアウト</button>
                        </form>
                    </div>
                </div>

                <div class="container mt-4">
                    <h1>Admin Dashboard</h1>

                      <!-- < summary cards section> -->

                      <div class="main-content">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card card-blue">
                                    <i class="fas fa-book icon"></i>
                                    <div class="card-title">Total Books</div>
                                    <div class="card-value">{{ $totalBooks }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-green">
                                    <i class="fas fa-shopping-cart icon"></i>
                                    <div class="card-title">Borrowed Books</div>
                                    <div class="card-value">{{ $borrowedBooks }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-orange">
                                    <i class="fas fa-users icon"></i>
                                    <div class="card-title">Users</div>
                                    <div class="card-value">{{ $totalUsers }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-red">
                                    <i class="fas fa-undo icon"></i>
                                    <div class="card-title">Reserved Books</div>
                                    <div class="card-value">{{ $reservedBooks }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-yellow">
                                    <i class="fas fa-exclamation-triangle icon"></i>
                                    <div class="card-title">Overdue Books</div>
                                    <div class="card-value">{{ $overdueBooks }}</div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Filter Section -->
                    <div class="filter-section mt-4">
                        <h3>Filters</h3>
                        <form action="{{ route('Admin.dashboard') }}" method="GET">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="startDate">Start Date:</label>
                                    <input type="date" id="startDate" name="start_date" class="form-control" value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="endDate">End Date:</label>
                                    <input type="date" id="endDate" name="end_date" class="form-control" value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="category">Book Category:</label>
                                    <select id="category" name="book_filter" class="form-control">
                                        <option value="">All Categories</option>
                                        @foreach($bookGenres as $genre)
                                            <option value="{{ $genre->genreid }}" {{ request('book_filter') == $genre->genreid ? 'selected' : '' }}>
                                                {{ $genre->genreid }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="user_filter">User Role:</label>
                                    <select id="user_filter" name="user_filter" class="form-control">
                                        <option value="">All Roles</option>
                                        @foreach($userRoles as $role)
                                            <option value="{{ $role->role }}" {{ request('user_filter') == $role->role ? 'selected' : '' }}>
                                                {{ ucfirst($role->role) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-center">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="container">
                        <h2>Library Statistics</h2>
                        <canvas id="libraryChart"></canvas>
                    </div>

                    <h2 class="mt-4">Recent Activities</h2>
                    <ul class="list-group">
                        @forelse($recentActivities as $activity)
                            <li class="list-group-item">{{ $activity->description }} - {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</li>
                        @empty
                            <li class="list-group-item">No recent activities.</li>
                        @endforelse
                    </ul>

                    <!-- Tasks and Reminders -->
                    <div class="tasks-reminders mt-4">
                        <h2>Tasks and Reminders</h2>
                        <ul class="list-group">
                            <li class="list-group-item">Review user registrations</li>
                            <li class="list-group-item">Check overdue books</li>
                            <li class="list-group-item">Approve new book entries</li>
                        </ul>
                    </div>

                    <!-- Notifications -->
                    <div class="notifications mt-4">
                        <h2>System Notifications</h2>
                        <div class="alert alert-info" role="alert">
                            <strong>Update Available:</strong> A new version of the library management system is available. Please contact the administrator for more details.
                        </div>
                    </div>

                    <!-- Export Options -->
                    <div class="export-options mt-4">
                        <button class="btn btn-secondary">Export as PDF</button>
                        <button class="btn btn-secondary">Export as Excel</button>
                    </div>
                </div>
            </div>
        </div>

    <!-- Load Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Render the chart -->
    <script>
        var ctx = document.getElementById('libraryChart').getContext('2d');
        var libraryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Users', 'Total Books', 'Borrowed Books', 'Reserved Books', 'Overdue Books'],
                datasets: [{
                    label: 'Library Statistics',
                    data: [{{ $totalUsers }}, {{ $totalBooks }}, {{ $borrowedBooks }}, {{ $reservedBooks }}, {{ $overdueBooks }}],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
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
    </script>

    <!-- Logout Success Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Curity</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                            <path d="M15.854 4.146a.5.5 0 1 0-.708-.708L7 11.293 4.854 9.146a.5.5 0 1 0-.708.708l2.5 2.5a.5.5 0 0 0 .708 0l8-8z"/>
                            <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
                        </svg>
                    </div>
                    <p>You have been logged out</p>
                    <p>Thank you</p>
                </div>
                <div class="modal-footer">
                    <p class="text-muted">Powered by Curity</p>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap Scripts for Modal -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        document.getElementById('logoutButton').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default button action
             // Show the modal
             $('#logoutModal').modal('show');

           // Delay before submitting the form and redirecting
           setTimeout(function() {
                document.getElementById('logout-form').submit(); // Submit the logout form
            }, 3000); // 3-second delay before submission
        });
    </script>

</body>
</html>
