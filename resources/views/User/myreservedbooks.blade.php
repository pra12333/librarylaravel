<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Search Books</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
            font-size: 16px; /* Adjusted font size for better readability */
            color: #333; /* Set the link color */
        }
        .navbar-nav .nav-item .nav-link:hover {
            text-decoration: underline; /* Underline on hover */
            color: #007bff; /* Change color on hover */
        }
        .navbar {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

    </style>
</head>
<body>
@if(session('status'))
    <div class="alert alert-success mt-3">
        {{ session('status') }}
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Library System</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
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
            <form id="logout-form" action="{{route('logout')}}" method="POST" style="display:none;">
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
<div class="container mt-5">
    <h2>My Reserved Books</h2>

    @if ($reservedBooks->isEmpty())
        <p>You have not reserved any books yet.</p>
    @else
    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Reserved Date</th>
            <th>Expires In</th>
            <th>Actions</th> <!-- Combine all actions under one column -->
        </tr>
    </thead>
    <tbody>
    @foreach($reservedBooks as $book)
    <tr>
        <!-- Assuming you have a 'book' relationship in the ReservedBook model -->
        <td>{{ $book->book->bookname }}</td> 
        <td>{{ $book->book->author }}</td>
        <td>{{ $book->reserved_at }}</td>
        <td>
         @if($book->expires_at)
         @if(now()->lessThanOrEqualTo($book->expires_at))
         {{$book->expires_at->diffForHumans()}}
         @else
         Reservation Expired
         @endif
         @else
         Not Set
         @endif
        </td>
        <td>
        <p>Reservation Status: {{ $book->reservation_status }}</p>

            @if($book->reservation_status == 'ready_for_pickup')
            <a href="{{route('User.pickup',$book->id)}}" class="btn btn-success">Pick Up</a>
            @elseif($book->reservation_status == 'pending')
            <span class="badge badge-warning">Reservation Pending</span>
            @endif

     @if(!$book->is_picked_up)
    <a href="{{ route('User.cancelReservation', $book->id) }}" class="btn btn-danger">Cancel Reservation</a>
     @endif

      @if($book->is_picked_up)
    <a href="{{ route('User.return', $book->id) }}" class="btn btn-warning">Return</a>
      @endif
        </td>
    </tr>
    @endforeach
    </tbody>
</table>

    @endif
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