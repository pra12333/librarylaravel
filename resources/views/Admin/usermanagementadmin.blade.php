<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - User Management</title>
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
            background-color:  #fdf5e6;
            border-right: 1px solid #dee2e6;
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
            justify-content: flex-end;
        }
        .table-container {
            margin-top: 20px;
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
        .search-container .btn-search {
            margin-right: 10px;
        }
        .search-container .btn-add {
            margin-left: auto;
        }
        .sidebar-button {
            display:block;
            margin-bottom: 10px;
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
                    <a href="{{ route('Admin.dashboard') }}" class="btn btn-primary btn-block w-50 mb-3">Dashboard</a>
                    <a href="{{ route('admin.superadmin.panel') }}" class="btn btn-primary btn-block w-50 mb-3">Super Admin</a>
                    <!-- <a href="{{ route('Admin.userregister') }}" class="btn btn-primary btn-block w-50 mb-3">User Register</a> -->
                    <!-- <a href="{{route('Admin.bookregister')}}" class="btn btn-primary btn-block w-50 mb-3"> Book Register</a> -->
                    <a href="{{ route('Admin.booklistadmin') }}" class="btn btn-primary btn-block w-50 mb-3">Update Book</a>
                    <a href="{{ route('genres.create') }}" class="btn btn-primary btn-block w-50 mb-3">Add Genres</a>

                </nav>
               
            </div>
            <div class="col-md-10">
                <div class="header">
                    <button class="btn btn-warning">Library System</button>
                    <div class="d-flex">
                        <span>{{ $superAdminName }}</span> 
                        <form id ="logout-form" action="{{route('logout')}}" method="POST" style="display:inline;">
                            @csrf 
                        <button class="btn btn-primary ml-2" id="logoutButton">ログアウト</button>
    </form>
                    </div>
                </div>
                <h2 class="form-title">ユーザ管理画面</h2>
                <div class="form-container">
                    <form action="{{ route('Admin.usermanagementadmin') }}" method="GET" class="search-container">
                        <input type="text" class="form-control" name="query" placeholder="Search users">
                        <button type="submit" class="btn btn-primary btn-search">検索</button>
                        <a href="{{ route('Admin.userregister') }}" class="btn btn-primary btn-add">Add user</a>
                    </form>
                    <div class="table-container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Last login</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($users->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">No users found.</td>
                                    </tr>
                                @else
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{$user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('Y-m-d h:i:s') : 'Never Logged In'}}</td> 
                                            <td>{{ $user->role }}</td>
                                            <td>
                                                @if(Auth::user()->role === \App\Models\User::ROLE_ADMIN && $user->role === \App\Models\User::ROLE_SUPERADMIN)
                                                    <button class="btn btn-primary" onclick="showPermissionError()">Edit</button>
                                                    <button class="btn btn-danger" onclick="showPermissionError()">Delete</button>
                                                @else
                                                    <a href="{{ route('Admin.user.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                                                    <form action="{{ route('Admin.user.delete', $user->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
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
        function showPermissionError() {
            alert("You do not have permission to perform this action.");
        }

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
