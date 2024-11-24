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
        .header .btn {
            margin-left: 10px;
        }
        .sidebar {
            height: 100vh;
            padding: 20px 0;
            margin: 0;
            background-color: #fdf5e6;
            position:fixed;
            top:0;
            left:0;
            width: 300px;
            border-right: 1px solid #dee2e6;
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
            margin-left: 30px;
            margin-top: 10px;
            font-size: 16px;
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
            background-color: e6e6fa;
        }
        .wrapper {
            display: flex;
            min-height: 100vh;
            margin-left:280px;
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
    margin-left: 30px; /* Add left margin to move the button to the right */
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
        }

        /* Force title centering within flexbox context */
        .centered-title {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .alert {
        position: relative;
        z-index: 1000;
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
                <a href="{{ route('Admin.usermanagementadmin') }}" class="btn btn-primary btn-block w-50 mb-3">User Update</a>
                @if(Auth::user()->role === 'superadmin')
                   <a href="{{route('Admin.userregister')}}" class="btn btn-primary btn-block w-50 mb-3">User Register</a>
                   @endif
                <a href="{{ route('genres.create') }}" class="btn btn-primary btn-block w-50 mb-3">Add Genre</a>
            </nav>
        </div>

        <div class="col-md-10 main-content">
            <div class="header">
                <button class="btn btn-warning">Library system</button>
                <div class="d-flex">
                    <span>{{$adminUser->name}}</span> 
                    <form id="logout-form"action="{{route('logout')}}" method="POST" style="display:none;">
                        @csrf
                    </form>
                    <button class="btn btn-primary ml-2" id="logoutButton">ログアウト</button>
                </div>
            </div>

            <!-- Apply a flex container to force the title to center -->
            <div class="centered-title">
                <h2 class="form-title">書籍一覧</h2>
            </div>

            <div class="form-container">
                <div class="search-container d-flex justify-content-between align-items-center">
                    <form action="{{ route('Admin.booksearch') }}" method="GET" class="d-flex">
                        <input type="text" class="form-control" name="query" placeholder="Search" required>
                        <button type="submit" class="btn btn-primary ml-2">検索</button>
                    </form>
                    <a href="{{ route('Admin.bookregister') }}" class="btn btn-primary">Book Register</a>
                </div>
            </div>

            <div class="table-container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">画像</th>
                            <th scope="col">本の名</th>
                            <th scope="col">作成者</th>
                            <th scope="col">ISBN</th>
                            <th scope="col">出版日</th>
                            <th scope="col">ジャンル</th>
                            <th scope="col">本の数</th>
                            <th scope="col">Action</th>
                            <th scope="col">Featured</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr>
                            <td><img src="{{ asset('storage/' . $book->bookpicture) }}" alt="Book Image" class="img-thumbnail" style="width: 50px;"></td>
                            <td>{{ $book->bookname }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->isbn }}</td>
                            <td>{{ $book->release_date->format('Y-m-d') }}</td>
                            <td>{{ $book->genre->name }}</td>
                            <td>{{ $book->total_no_of_copies }}</td>
                            <td>
                                <a href="{{ route('Admin.bookupdate', ['id' => $book->id]) }}" class="btn btn-primary">Update</a>
                                <form action="{{ route('Admin.bookdelete', ['id' => $book->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                            <td>
                                @if($book->featured)
                                <span class="badge badge-success">Featured</span>
                                @else
                                <span class="badge badge-secondary">Not Featured</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
            }, 3000)
        });
    </script>
</body>
</html>
