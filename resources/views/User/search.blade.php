<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Search Books</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
   

    <style>
        .container {
            margin-top: 20px;
        }
        .search-results {
            margin-top: 20px;
        }
        .book-list {
            list-style: none;
            padding: 0;
        }
        .book-list li {
            margin: 5px 0;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .available {
            background-color: #d4edda;
        }
        .not-available {
            background-color: #f8d7da;
        }
        .navbar-nav .nav-item .nav-link {
            margin-right: 15px;
            font-size: 16px;
            color: #333;
        }
        .navbar-nav .nav-item .nav-link:hover {
            text-decoration: underline;
            color: #007bff;
        }
        .navbar {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Library System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('User.homepage') }}"><i class="fa fa-fw fa-home"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('User.booklist') }}"><i class="fa fa-fw fa-book"></i>Book List</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('User.myborrow') }}"><i class="fa fa-fw fa-shopping-cart"></i>My Borrowed Books</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('User.myreservedbooks') }}"><i class="fa fa-fw fa-bookmark"></i>My Reserved Books</a></li>
                <!-- <li class="nav-item"><a class="nav-link" href="{{ route('user.viewCart') }}"><i class="fa fa-shopping-cart"></i> My Cart</a></li> -->
                <li class="nav-item"><a class="nav-link" href="{{ route('User.search') }}"><i class="fa fa-fw fa-search"></i>Search Books</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('wishlist.index') }}"><i class="fa fa-heart"></i>Wishlist</a></li>
                <!-- <li class="nav-item"><a class="nav-link" href="{{ route('User.homepage') }}">Home</a></li> -->
                <li class="nav-item"><a class="nav-link" href="{{ route('User.account') }}"><i class="fa fa-fw fa-user"></i>Account Settings</a></li>
                @if(Auth::user()->role === \App\Models\User::ROLE_ADMIN || Auth::user()->role === \App\Models\User::ROLE_SUPERADMIN)
                    <li class="nav-item"><a class="nav-link" href="{{ route('Admin.booklistadmin') }}"><i class="fa fa-tachometer-alt"></i>Admin Dashboard</a></li>
                @endif

                <!-- Check if the user is a superadmin -->
                @if(Auth::user()->role === \App\Models\User::ROLE_SUPERADMIN)
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.superadmin.panel') }}"><i class="fa fa-user-shield"></i>Super Admin Panel</a></li>
                @endif

                <li class="nav-item"><a class="nav-link" href="#" id="logoutButton"><i class="fa fa-fw fa-sign-out-alt"></i>Logout</a></li>
                <form id="logout-form" action ="{{route('logout') }}" method="POST" style="display:none;">
                    @csrf 
                </form>
                  <!-- Updated Cart Item with Correct Classes -->
    <li class="nav-item">
        <a href="{{ route('user.viewCart') }}" class="nav-link cart-link">
            <i class="fa fa-shopping-cart"></i> 
            My Cart 
            <span id="cart-count">({{ $cartCount ?? 0 }})</span>
        </a>
    </li>
</ul>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Search Books</h2>
        <form class="form-inline" id="search-form" action="{{route('user.searchbooks')}}" method="POST">
            @csrf 
            <input class="form-control mr-sm-2" type="search" placeholder="Search for books..." aria-label="Search" id="search-input">
            <button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
        </form>
        <div class="search-results" id="search-results">
            <ul class="book-list" id="book-list">
                <!-- Search results will be dynamically added here -->
            </ul>
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
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script>
       $('#search-form').on('submit', function(e) {
    e.preventDefault(); // Prevent form submission

    var query = $('#search-input').val(); // Get search input

    $.ajax({
        url: '{{ route('user.searchbooks') }}', // POST request URL
        method: 'POST', // Method should match the route method
        data: {
            _token: '{{ csrf_token() }}', // CSRF token for security
            query: query // Pass the search query
        },
        success: function(data) {
            var bookList = $('#book-list');
            bookList.empty(); // Clear the previous results

            // If books are found, display them
            data.forEach(function(book) {
                var bookItem = '<li class="' + (book.total_no_of_copies > 0 ? 'available' : 'not-available') + '">' +
                    '<h3>' + book.bookname + '</h3>' +
                    '<p>Author: ' + book.author + '</p>' +
                    '<p>GenreId: ' + book.genreid + '</p>' +
                    '<p>AvailableCopies: ' + book.total_no_of_copies + '</p>' +
                    '<button class="btn ' + (book.total_no_of_copies > 0 ? 'btn-success' : 'btn-danger') + '">' +
                    (book.total_no_of_copies > 0 ? 'Available' : 'Not Available') +
                    '</button>' +
                    '</li>';
                bookList.append(bookItem);
            });
        },
        error: function(jqXHR) {
            var bookList = $('#book-list'); // Declare bookList variable in error handling
            bookList.empty(); // Clear any existing content

            // If the status is 404, display a "No books found" message
            if (jqXHR.status === 404) {
                bookList.append('<li>No books found.</li>');
            } else {
                // Handle other errors (e.g., server errors)
                alert('An error occurred: ' + jqXHR.statusText);
            }
        }
    });
});


document.getElementById('logoutButton').addEventListener('click', function() {
    $('#logoutModal').modal('show'); // Show the modal
    setTimeout(function() {
        $('#logoutModal').modal('hide'); // Hide the modal after 3 seconds
        document.getElementById('logout-form').submit(); // Submit the logout form, which uses POST
    }, 3000); // 3 seconds delay
});
    </script>
</body>
</html>
