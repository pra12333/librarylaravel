<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Book List (Admin)</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .body{
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;

        }
        .header .btn {
            margin-left: 10px;
        }
        .sidebar {
            height: 100vh;
            padding: 20px,0;
            margin:0;
            background-color: #fdf5e6;
            border-right: 1px solid #dee2e6;
            top:0;
            left:0;
            position: fixed;
        }
        .sidebar .nav-link {
            margin: 10px 0;
            font-size: 16px;
            padding: 10px 15px;
            color: #333;
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

        .table-container {
            flex-grow: 1;
            margin-top: 20px;
            padding-bottom: 20px;
            overflow: auto;
            height: calc(100vh - 180px); /* Adjusted height to ensure it fits the viewport */
        }
        .search-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .search-container input {
            width: 200px;
            margin-right: 10px;
        }
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }
        .wrapper {
            display: flex;
            min-height: 100vh;
            margin-left: 285px;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        /* Updated centering for the title */
        .form-title {
            text-align: center;
            font-size: 30px;
            margin-top: 10px;
            width: 100%;
            margin-bottom: 20px;
        }

        /* Force title centering within flexbox context */
        .centered-title {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        /* Form container styles */
        .form-container {
        background-color: white;
        padding: 50px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        width: 500px; /* Set a fixed width */
        margin: 0 auto; /* Center horizontally */
        margin-top: 30px; /* Adjust margin to create space between the title and form */
        display: flex;
        flex-direction: column;
        align-items: center;
        }

        .form-container h3 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
            justify-content: center;
            color: #2a2a2a;
            align-items: center;
        }

        .form-control {
            border-radius: 30px; /* Rounded input fields */
            padding: 20px;
            font-size: 18px;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            margin-bottom: 25px;
        }

        .btn-submit {
            width: 100%;
            padding: 20px;
            border-radius: 30px;
            justify-content: center;
            background-color: #4caf50; /* Green button */
            color: white;
            border: none;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-container {
                width: 90%; /* Make the form take up more space on smaller screens */
            }
        }

        .form-title{
            font-size: 32px;
            margin-bottom: 30px;
            text-align:center;
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

    <div class="container-fluid wrapper">
        <div class="col-md-2 sidebar">
            <nav class="nav flex-column">
                <p>Features</p>
                <a href="{{ route('User.homepage') }}" class="btn btn-primary btn-block w-50 mb-3">Homepage</a> 
                <a href="{{ route('Admin.dashboard') }}" class="btn btn-primary btn-block w-50 mb-3">Dashboard</a>
                <a href="{{ route('admin.superadmin.panel') }}" class="btn btn-primary btn-block w-50 mb-3">Super Admin</a>
                <a href="{{ route('Admin.booklistadmin') }}" class="btn btn-primary btn-block w-50 mb-3">Update Book</a>
                <a href="{{ route('Admin.usermanagementadmin') }}" class="btn btn-primary btn-block w-50 mb-3">User Update</a>
            </nav>
        </div>

        <div class="col-md-10 main-content">
            <div class="header">
                <button class="btn btn-warning">Library system</button>
                <div class="d-flex">
                    <span>{{$adminUser->name}}</span> 
                    <form id="logout-form" action="{{route('logout')}}" method="POST" style="display:inline;"> 
                        @csrf 
                    <button class="btn btn-primary ml-2" id="logoutButton">ログアウト</button>
                    </form>
                </div>
            </div>
             <!-- Form Title -->
             <div class="form-title">Add New Genre</div>
              <!-- Add Genre Form -->
            <div class="form-container">
                <form action="{{ route('genres.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="genreName">Genre Name</label>
                        <input type="text" class="form-control" id="genreName" name="name" placeholder="Enter genre name" required>
                    </div>
                    <button type="submit" class="btn btn-success submit-btn">Add Genre</button>
                </form>
            </div>

        </div>
    </div>
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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