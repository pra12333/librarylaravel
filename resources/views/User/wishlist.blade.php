<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        /* Custom styles */
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 110px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .cart-link {
    display: flex;
    align-items: center;
    font-size: 16px; /* Match the font size with other nav items */
    color: #333; /* Ensure the color matches other nav links */
    margin-right: 15px; /* Space between cart and other items */
}

.cart-link i {
    margin-right: 5px; /* Space between the icon and the cart count */
}

#cart-count {
    font-weight: normal;
    margin-left: 3px;
    color: inherit; /* Adjust the color of the cart count if necessary */
}

        .navbar {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px; /* Add padding for spacing */
            background-color: #f5f5f5;
            border-bottom: 1px solid #dee2e6;
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }
        .header .btn {
            margin-left: 10px;
        }

        .alert {
            margin-bottom: 20px;
        }
        .list-group-item {
        margin-bottom: 10px;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .list-group-item .btn-link {
        text-decoration: none;
        font-weight: 500;
    }

    .list-group-item .btn-danger {
        margin-left: 10px;
    }

    h1 {
        margin-bottom: 20px;
        font-size: 28px;
        color: #333;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Library System</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('User.homepage') }}"><i class="fa fa-home"></i> Home</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('User.booklist') }}"><i class="fa fa-book"></i> Book List</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('User.search') }}"><i class="fa fa-fw fa-search"></i> Search Books</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('User.myborrow') }}"><i class="fa fa-shopping-cart"></i> My Borrowed Books</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('User.myreservedbooks') }}"><i class="fa fa-bookmark"></i> My Reserved Books</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('wishlist.index') }}"><i class="fa fa-heart"></i>Wishlist</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('User.account') }}"><i class="fa fa-user"></i> Account Settings</a></li>
            <!-- Admin and Super Admin Links -->
            @if($user->role === $roleAdmin || $user->role === $roleSuperAdmin)
                <li class="nav-item"><a class="nav-link" href="{{ route('Admin.dashboard') }}"><i class="fa fa-tachometer-alt"></i> Admin Dashboard</a></li>
            @endif

            @if($user->role === $roleSuperAdmin)
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.superadmin.panel') }}"><i class="fa fa-user-shield"></i> Super Admin Panel</a></li>
            @endif

            <!-- Logout Button -->
            <li class="nav-item">
                <a class="nav-link" href="#" id="logoutButton"><i class="fa fa-sign-out-alt"></i> Logout</a>
            </li>
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
<div class="container">
    <h1>My Wishlist</h1>

    <!-- Display success message -->
    <!-- @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif -->

    <!-- Display error message -->
    <!-- @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif -->

    @if($wishlistBooks->isEmpty())
        <p>Your wishlist is empty.</p>
    @else
        <div class="list-group">
            @foreach($wishlistBooks as $wishlistBook)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $wishlistBook->book->title }}</strong> 
                        by {{ $wishlistBook->book->author }} 
                        <a href="{{ route('books.show', $wishlistBook->book->id) }}" class="btn btn-link">View Details</a>
                    </div>
                    <form action="{{ route('wishlist.remove', $wishlistBook->book->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this book from your wishlist?')">
                            Remove
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
// Logout functionality with modal
    document.getElementById('logoutButton').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the immediate form submission

        // Show the logout modal
        $('#logoutModal').modal('show');

        // Wait for 3 seconds before submitting the form (to show the modal for a brief moment)
        setTimeout(function() {
            document.getElementById('logout-form').submit(); // Submit the form (this will trigger the actual logout)
        }, 3000); // 3-second delay before form submission
    });

    // automatically hide success and error message after 3 seconds
    setTimeout(function(){
        $('.alert').fadeOut('slow');
    },3000);

</script>
<script>
    @if(session('success'))
        // Display success message in a JS alert
        alert('{{ session('success') }}');
    @endif

    @if(session('error'))
        // Display error message in a JS alert
        alert('{{ session('error') }}');
    @endif
</script>

</body>
</html>