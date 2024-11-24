<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Book List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
body, html {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-color: #ebedef;
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

.auth-buttons a {
    margin-left: 10px; /* Space between buttons */
}

.title {
    text-align: center;
    margin: 20px 0;
}

.navbar {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.search-container {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    justify-content: flex-start;
    padding: 0 20px;
}

.search-container input {
    margin-right: 10px;
    width: 200px;
    flex: 1; /* Ensures the input field takes up the available space */
}

.table-container {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    border-radius: 10px;
    width: 100%;
    border-color: #061d1f;
}

.navbar-custom {
    background-color: #f5f5f5;
    color: #fffafa;
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

table th, table td {
    border: 2px solid black; /* Ensures borders are black */
    border-collapse: collapse;
    padding: 12px;
    font-weight: bold; /* Makes text bold */
    color: #333; /* Darker text color */
}

.button-container {
    display: flex;
    gap: 15px; /* Space between buttons */
}

.button-box {
    background-color: white;
    color: black;
    border: 1px solid #000;
    padding: 10px 20px;
    text-align: center;
    display: inline-block;
    width: 100px; /* Fixed width for buttons */
    cursor: pointer;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); /* Optional shadow */
}

.button-box:hover {
    background-color: #f0f0f0; /* Change background on hover */
}

.cart-shake-animation {
    animation: shake 0.5s ease;
}

@keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
    75% { transform: translateX(-5px); }
    100% { transform: translateX(0); }
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

    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
@endif

@if(isset($message))
    <div class="alert alert-warning text-center">{{ $message }}</div>
@endif

@guest
<div class="header">
    <button class="btn btn-warning btn-lg">Library system</button>
    @endguest
    
    @auth
    <!-- Navbar for logged-in users -->
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
                <!-- Check if the user is an admin or superadmin -->
                @if(Auth::user()->role === \App\Models\User::ROLE_ADMIN || Auth::user()->role === \App\Models\User::ROLE_SUPERADMIN)
                    <li class="nav-item"><a class="nav-link" href="{{ route('Admin.booklistadmin') }}"><i class="fa fa-tachometer-alt"></i>Admin Dashboard</a></li>
                @endif

                <!-- Check if the user is a superadmin -->
                @if(Auth::user()->role === \App\Models\User::ROLE_SUPERADMIN)
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.superadmin.panel') }}"><i class="fa fa-user-shield"></i>Super Admin Panel</a></li>
                @endif

                <li class="nav-item">
            <form action="{{ route('top.logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="nav-link btn btn-link" style="cursor:pointer;"><i class="fa fa-sign-out-alt"></i>Logout</button>
            </form>
        </li>
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
    @endauth

    @guest
    <!-- Navbar for guests (logged-out users) -->
    <div class="auth-buttons">
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Sign In</a>
        <a href="{{ route('register') }}" class="btn btn-secondary btn-lg">Sign Up</a>
    </div>
    @endguest
</div>

    <h2 class="title">書籍一覧</h2>  

    <div class="search-container">
         <form action="{{ route('Public.searchBooks') }}" method="GET" style="display: flex;">
            <input type="text" name="query" class="form-control" placeholder="Search by book name" value="{{ request('query') }}">
            <button type="submit" class="btn btn-primary">検索</button>
        </form> 
    </div> 
    <div class="table-container container-fluid">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">画像</th>
                    <th scope="col">本の名</th>
                    <th scope="col">作成者</th>
                    <th scope="col">ジャンルID</th>
                    <th scope="col">出版日</th>
                    <th scope="col">ステータス</th>
                    <th scope="col">アクション</th>
                </tr>
            </thead>
            <tbody>
            @forelse($books as $book)
            {{ \Log::info('Book ID in view: ' . $book->id) }}
                <tr>
                    <td><img src="{{ Storage::url($book->bookpicture) }}" alt="Book Image" class="img-thumbnail" style="width: 50px;"></td> 
                    <td>{{ $book->bookname }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->genreid }}</td>
                    <td>{{ $book->release_date ->format('Y-m-d')}}</td>
                    <td>{{ $book->total_no_of_copies }}</td>
                    <td>
                      @auth 
                       @php 
                         $bookController = new \App\Http\Controllers\User\BookController();
                         $userhasOverdueBooks = $bookController->hasOverdueBooks(Auth::user()->id);
                         @endphp 
                         <script>
    $(document).on('click', '.add-to-cart-btn', function (e) {
        e.preventDefault();
        var bookId = $(this).data('id');

        $.ajax({
            url: '/cart/add/' + bookId,  // Make sure the route matches your Laravel route
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',  // Include CSRF token for security
            },
            success: function (response) {
                if (response.success) {
                    alert("Book added to cart successfully!");
                    // Update the cart count on the navbar
                    $('#cart-count').text(response.cart_count);
                } else {
                    alert(response.message);
                }
            },
            error: function (response) {
                alert("Something went wrong! Please try again.");
            }
        });
    });
</script>

<button class="btn btn-primary add-to-cart-btn" data-id="{{ $book->id }}">Add to Cart</button>

                      {{-- For authenticated users: show borrow button if copies are available,otherwise show reserve button--}}
                      @if($book->total_no_of_copies > 0)
                      <form action="{{route('User.bookborrow',['id' => $book->id]) }}" method="GET" style="display:inline;">
                        @csrf 
                        <button type="submit" class="btn btn-success btn-borrow">借りる</button>
                      </form>
                      <button class="btn btn-warning add-to-wishlist-btn" data-id="{{ $book->id }}">Add to Wishlist</button>
                      @elseif($book->total_no_of_copies >0 && $userCannotBorrow)
                      {{-- show request to borrow button if the user cannot borrow due to overdue books --}}
                      <form action="{{route('User.requestBorrow',['id' => $book->id]) }}" method="POST" style="display: inline;">
                        @csrf 
                        <button type="submit" class="btn btn-warning btn-borrow">リクエスト</button>
                      </form>
                      @else
                      <form action="{{route('User.confirmReserve',['id' =>$book->id])}}" method="POST" style="display: inline;">
                        @csrf 
                        <button type="submit" class="btn btn-warning btn-reserve">予約</button>
                      </form>
                      @endif
                      @else
                      {{-- For non-authenticated users: Always show both "Borrow" and "Reserve" buttons --}} 
                      {{-- only show the "Borrow" button if copies are available(copies>0) --}}
                      @if($book->total_no_of_copies >0)
                      <a href="{{route('login') }}" class="btn btn-success">借りる</a>
                      @endif 
                      {{-- Always show the "Reserve" button --}}
                      <a href="{{route('login')}}" class="btn btn-warning">予約</a>
                      @endauth
</td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center">No books found</td>
</tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const addToCartForms = document.querySelectorAll('.add-to-cart-btn');
    const cartCount = document.getElementById('cart-count');

    addToCartForms.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default form submission
            const bookId = button.dataset.id; // Get the book ID from data attribute

            // Use Fetch API to submit the form via AJAX
            fetch(`/cart/add/${bookId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Animate the cart icon
                    const cartIcon = document.querySelector('.cart-link i');
                    cartIcon.classList.add('cart-shake-animation');
                    
                    setTimeout(() => {
                        cartIcon.classList.remove('cart-shake-animation');
                    }, 500); // Animation duration
                    
                    // Update the cart count with parentheses
                    cartCount.textContent = `(${data.cart_count})`;

                    // Optionally show a success message
                    alert('Item added to cart!');
                } else {
                    alert('Failed to add item to cart: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Fetch cart count on page load
    fetch('/cart/count', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update the cart count in the navbar with parentheses
        document.getElementById('cart-count').textContent = `(${data.cart_count})`;
    })
    .catch(error => console.error('Error fetching cart count:', error));
});
document.addEventListener('DOMContentLoaded', function() {
    const wishlistButtons = document.querySelectorAll('.add-to-wishlist-btn');

    wishlistButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const bookId = this.getAttribute('data-id');

            console.log(bookId);

            fetch(`/wishlist/add/${bookId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Success message
                } else {
                    alert('Failed to add to wishlist.'); // Error message from the backend
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});

</script>
    </document>
    <!-- Add this in the <head> or before your script using jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
