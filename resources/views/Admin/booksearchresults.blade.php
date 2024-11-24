<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Search Results</title>
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
        .header .btn {
            margin-left: 10px;
        }
        .sidebar {
            height: 100vh;
            padding-top: 20px;
            background-color: #d3d3d3;
            border-right: 1px solid #dee2e6;
        }
        .sidebar .nav-link {
            margin: 10px 0;
            font-size: 16px; /* Adjusted font size for better readability */
            padding: 10px 15px; /* Added padding for better spacing */
            color: #333;
        }
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: #007bff;
        }
        .sidebar p {
            margin-left: 15px; /* Align the text with the nav links */
            margin-top: 10px; /* Add some space above the text */
            font-size: 16px; /* Match the font size with the nav links */
        }
        .form-container {
            padding: 20px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
        }
        .btn-delete {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <nav class="nav flex-column">
                <p>Features</p>
                    <a href="{{ route('User.homepage') }}" class="btn btn-primary btn-block w-50 mb-3">Homepage</a> 
                    <a href="{{ route('Admin.dashboard') }}" class="btn btn-primary btn-block w-50 mb-3">Dashboard</a>
                    <a href="{{ route('admin.superadmin.panel') }}" class="btn btn-primary btn-block w-50 mb-3">Super Admin</a>
                    <a href="{{ route('Admin.userregister') }}" class="btn btn-primary btn-block w-50 mb-3">User Register</a>
                    <!-- <a href="{{route('Admin.bookregister')}}" class="btn btn-primary btn-block w-50 mb-3"> Book Register</a> -->
                    <a href="{{ route('Admin.booklistadmin') }}" class="btn btn-primary btn-block w-50 mb-3">Update Book</a>
                    <a href="{{ route('genres.create') }}" class="btn btn-primary btn-block w-50 mb-3">Add Genre</a>

                </nav>
                
            </div>
            <div class="col-md-10">
                <div class="header">
                    <button class="btn btn-warning">Library System</button>
                    <div class="d-flex">
                        <span>{{$user->name}}</span>
                        <form id="logout-form" action="{{route('logout')}}" method="POST" style="display:inline;"> 
                            @csrf 
                        <button class="btn btn-primary ml-2" id="logoutButton">ログアウト</button>
    </form>
                    </div>
                </div>
    
                <h2 class="form-title">Search Results</h2>
                <div class="form-container">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Book Name</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                                <tr>
                                    <td>{{ $book->bookname }}</td>
                                    <td>{{ $book->author }}</td>
                                    <td>{{ $book->genre->name }}</td>
                                    <td>
                                        <a href="{{ route('Admin.bookupdate', $book->id) }}" class="btn btn-primary">Update</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
