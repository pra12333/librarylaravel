<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Book Registration</title>
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
            justify-content: flex-end;
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
                    <a href="{{ route('Admin.userregister') }}" class="btn btn-primary btn-block w-50 mb-3">User Register</a>
                    <a href="{{ route('admin.superadmin.panel') }}" class="btn btn-primary btn-block w-50 mb-3">Super Admin</a>
                    <!-- <a href="{{route('Admin.bookregister')}}" class="btn btn-primary btn-block w-50 mb-3"> Book Register</a> -->
                    <a href="{{ route('Admin.booklistadmin') }}" class="btn btn-primary btn-block w-50 mb-3">Update Book</a>
                    <a href="{{ route('genres.create') }}" class="btn btn-primary btn-block w-50 mb-3">Add Genre</a>

                </nav>
             
            </div>
            <div class="col-md-10">
                <div class="header">
                    <button class="btn btn-warning">Library system</button>
                    <div class="d-flex">
                        <span>{{$user->name}}</span>
                        <form id="logout-form" action="{{route('logout')}}" method="POST" style="display:inline;"> 
                            @csrf 
                        <button class="btn btn-primary ml-2" id="logoutButton">ログアウト</button>
    </form>
                    </div>
                </div>
                <h2 class="form-title">本の新規登録</h2>
                <div class="form-container">
                <form action="{{ route('Admin.bookregister.post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="bookName">本の名：</label>
                <input type="text" class="form-control" id="bookName" name="bookname" placeholder="Enter book name" required>
            </div>
            <div class="form-group">
                <label for="bookImage">本の画像：</label>
                <input type="file" class="form-control-file" id="bookImage" name="bookpicture">
            </div>
            <div class="form-group">
                <label for="author">作成者：</label>
                <input type="text" class="form-control" id="author" name="author" placeholder="Enter author name" required>
            </div>
            <div class="form-group">
                <label for="genre">ジャンル：</label>
                <select class="form-control" id="genre" name="genreid" required>
                    @foreach($genres as $genre)
                    <option value="{{$genre->id}}">{{$genre->name}}</option>
              @endforeach
    </select>
            </div>
            <div class="form-group">
                <label for="publicationDate">出版日：</label>
                <input type="date" class="form-control" id="publicationDate" name="release_date" required>
            </div>
            <div class="form-group">
                <label for="isbn">ISBN：</label>
                <input type="text" class="form-control" id="isbn" name="isbn" placeholder="Enter ISBN" required>
            </div>
            <div class="form-group">
                <label for="total_no_of_copies">Total Copies：</label>
                <input type="number" class="form-control" id="total_no_of_copies" name="total_no_of_copies" placeholder="Enter total number of copies" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
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