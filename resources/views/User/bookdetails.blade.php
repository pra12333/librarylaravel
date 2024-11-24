<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* Custom styles */
        body {
            background-color: #f8f9fa; /* Light background for a clean look */
        }

        .container {
            margin-top: 110px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
            padding: 20px;
        }

        .card {
            margin-top: 30px;
            border-radius: 15px; /* Rounded corners */
            overflow: hidden;
        }

        .card-body {
            line-height: 1.8;
        }

        h2 {
            font-weight: bold;
            font-size: 2.2em;
        }

        p {
            font-size: 1.1em;
        }

        .btn-lg {
            font-size: 1.2em;
            width: 100%;
        }

        .btn-success {
            margin-top: 10px;
        }

        .text-muted {
            color: #6c757d;
        }

        .badge {
            font-size: 0.9em;
        }

        .book-image-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .book-image-container img {
            max-height: 400px;
            max-width: 100%;
            object-fit: cover;
            border-radius: 10px;
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

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('User.homepage') }}"><i class="fa fa-home"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('User.booklist') }}"><i class="fa fa-book"></i>Book List</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('User.search') }}"><i class="fa fa-fw fa-search"></i>Search Books</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('User.myborrow') }}"><i class="fa fa-shopping-cart"></i>My Borrowed Books</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('User.myreservedbooks') }}"><i class="fa fa-bookmark"></i>My Reserved Books</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('wishlist.index') }}"><i class="fa fa-heart"></i>Wishlist</a></li>
                <!-- <li class="nav-item"><a class="nav-link" href="{{ route('user.viewCart') }}"><i class="fa fa-shopping-cart"></i> My Cart</a></li> -->
                <li class="nav-item"><a class="nav-link" href="{{ route('User.account') }}"><i class="fa fa-user"></i>Account Settings</a></li>
                
                @if($user->role === $roleAdmin || $user->role === $roleSuperAdmin)
                    <li class="nav-item"><a class="nav-link" href="{{ route('Admin.dashboard') }}"><i class="fa fa-tachometer-alt"></i>Admin Dashboard</a></li>
                @endif

                @if($user->role === $roleSuperAdmin)
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.superadmin.panel') }}"><i class="fa fa-user-shield"></i>Super Admin Panel</a></li>
                @endif
                <li class="nav-item"><a class="nav-link" href="#" id="logoutButton"><i class="fa fa-sign-out-alt"></i>Logout</a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
    <div class="container my-5">
        <h1 class="text-center text-primary mb-5">Book Details</h1>
        <div class="row">
            <!-- Book Image -->
            <div class="col-md-6 book-image-container">
                <img src="{{ asset('storage/' . $book->bookpicture) }}" alt="{{ $book->bookname }}" class="img-fluid shadow">
            </div>

            <!-- Book Details -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4">
                    <div class="card-body">
                        <h2 class="text-primary">{{ $book->bookname }}</h2>
                        <p>
                            <strong>Author:</strong>
                            <span class="badge badge-primary p-2">{{ $book->author ?? 'Unknown Author' }}</span>
                        </p>
                        <p>
                            <strong>Genre:</strong>
                            <span class="badge badge-info p-2">{{ $book->genre->name ?? 'Unknown Genre' }}</span>
                        </p>
                        <p>
                            <strong>Description:</strong>
                        </p>
                        <p class="text-muted">{{ $book->description ?? 'No description available' }}</p>
                        <p>
                            <strong>Published on:</strong>
                            <span class="text-muted">{{ $book->release_date ? $book->release_date->format('Y-m-d') : 'N/A' }}</span>
                        </p>
                        <p>
                            <strong>Availability:</strong>
                            @if(isset($book->total_no_of_copies) && $book->total_no_of_copies > 0)
                                <span class="text-success">Available ({{ $book->total_no_of_copies }} copies)</span>
                            @else
                                <span class="text-danger">Currently unavailable</span>
                            @endif
                        </p>

                        <!-- Borrow or Reserve Button -->
                        <div class="mt-4">
                            @if($book->total_no_of_copies > 0)
                                <a href="{{ route('User.bookborrow', ['id' => $book->id]) }}" class="btn btn-lg btn-success">
                                    <i class="fas fa-book-open"></i> Borrow Now
                                </a>
                            @else
                                <a href="{{ route('reserve.book', ['bookId' => $book->id]) }}" class="btn btn-lg btn-warning">
                                    <i class="fas fa-calendar-plus"></i> Reserve
                                </a>
                            @endif
                            <!-- Back to Book List -->
                            <a href="{{ route('User.booklist') }}" class="btn btn-lg btn-secondary mt-3">
                                <i class="fas fa-arrow-left"></i> Back to Book List
                            </a>
                        </div>
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

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
         document.getElementById('logoutButton').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the immediate form submission

    // Show the logout modal
    $('#logoutModal').modal('show');

    // Wait for 3 seconds before submitting the form (to show the modal for a brief moment)
    setTimeout(function() {
        document.getElementById('logout-form').submit(); // Submit the form (this will trigger the actual logout)
    }, 3000); // 3-second delay before form submission
});
    </script>
</body>

</html>
