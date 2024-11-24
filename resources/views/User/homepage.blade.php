<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Homepage</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .container {
            margin-top: 20px;
        }
        .welcome-message {
            margin-bottom: 20px;
            font-size: 1.5em;
            font-weight: bold;
        }
        .category-list {
            list-style: none;
            padding: 0;
        }
        .category-list li {
            margin: 10px 0;
        }
        .category-list a {
            text-decoration: none;
            color: #007bff;
            font-size: 18px;
        }
        .category-list a:hover {
            text-decoration: underline;
        }
        .category-results {
            margin-top: 10px;
        }
        /* Updated styling for search bar */
        .search-bar-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            justify-content: flex-end;
        }
        .search-bar-container input {
            width: 300px; /* Slightly wider search bar */
            margin-right: 10px; /* Space between input and button */
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
        /* Search Results styling */
        .search-results li {
            list-style: none;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
        }
        .search-results li h5 {
            font-weight: bold;
        }
        /* Loading spinner styling */
        .loading-spinner {
            display: none;
            width: 3rem;
            height: 3rem;
            border: 0.4em solid rgba(0, 0, 0, 0.1);
            border-top: 0.4em solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
        /* Styling for the borrowed books list */
.borrowed-books-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.borrowed-books-list li {
    margin-bottom: 15px; /* Add some spacing between items */
    font-size: 16px;
}

/* Optional: Style the book titles */
.borrowed-books-list li strong {
    font-size: 18px;
    color: #333;
}

/* Optional: Style the dates */
.borrowed-books-list li p {
    margin: 5px 0;
    color: #666;
}
.notification-bell {
    position: fixed;
    top: 40px;
    right: 20px;
    z-index: 1000;
}
.notification-bell .badge {
    position: absolute;
    top: -10px;
    right: -10px;
    background-color: red;
    color: white;
}
.navbar {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Make the links appear as box-shaped buttons */
a.category-link {
    display: inline-block; /* Makes the link behave like a block, so padding works */
    padding: 10px 15px; /* Add space around the link text */
    border: 2px solid #007bff; /* Add a border */
    border-radius: 8px; /* Rounded corners for a modern look */
    color: #007bff; /* Link color */
    background-color: #f9f9f9; /* Light background color for the button */
    text-decoration: none; /* Remove underline from the link */
    transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition for hover effects */
}

/* Hover state for the links */
a.category-link:hover {
    background-color: #007bff; /* Change background color on hover */
    color: #fff; /* Change text color on hover */
    border-color: #0056b3; /* Slightly darker border */
    text-decoration: none; /* Keep underline removed on hover */
}

/* Active and Focus states to maintain consistency */
a.category-link:active,
a.category-link:focus {
    background-color: #0056b3; /* Slightly darker color for clicked state */
    color: #fff; /* Keep text white */
    outline: none; /* Remove focus outline */
    box-shadow: none; /* Remove focus shadow */
}
.alert{
    margin-bottom: 20px;
}

.list-group-item{
    padding: 15px;
    font-size: 16px;
}

.list-group-item .text-muted {
    font-size:12px;
}
    </style>
</head>
<body>
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

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
                
                @if($user->role === $userModel::ROLE_ADMIN || $user->role === $userModel::ROLE_SUPERADMIN)
                    <li class="nav-item"><a class="nav-link" href="{{ route('Admin.dashboard') }}"><i class="fa fa-tachometer-alt"></i>Admin Dashboard</a></li>
                @endif

                @if($user->role === $userModel::ROLE_SUPERADMIN)
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
    <!-- Bell Icon for Notifications -->
    <div class="notification-bell">
    <a class="btn btn-light position-relative dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-bell fa-2x"></i>
        @if (auth()->user()->unreadNotifications->count() > 0)
            <span class="badge badge-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationsDropdown">
        @if (auth()->user()->unreadNotifications->isEmpty())
            <a class="dropdown-item" href="#">No new notifications</a>
        @else
            @foreach (auth()->user()->unreadNotifications as $notification)
                <a class="dropdown-item" href="{{ route('notifications.markAsRead', $notification->id) }}">
                    {{ $notification->data['message'] }}
                </a>
            @endforeach
        @endif
    </div>
</div>

    <div class="container">
        <div class="welcome-message">
            @php
                $hours = now()->format('H');
                $greeting = $hours < 12 ? 'Good Morning' : ($hours < 18 ? 'Good Afternoon' : 'Good Evening');
            @endphp
            {{ $greeting }}, {{ $user->name }}!
        </div>

        <!-- Search bar -->
        <div class="search-bar-container">
            <input type="text" id="searchQuery" class="form-control" placeholder="Search for books...">
            <button class="btn btn-primary" id="searchButton">Search</button>
        </div>

        <!-- Search results section -->
        <div class="search-results" id="searchResults">
            <!-- Search results will be displayed here -->
        </div>

        <!-- Borrowed Books Section -->
        <!-- Borrowed Books Section -->
        <div class="container my-5">
    <div class="row">
        <h3>My Borrowed Books</h3>
    </div>
    <div class="row">
    <p class="card-text">
    @foreach($borrowedBooks as $borrowedBook)
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $borrowedBook->book->bookname ?? 'Unknown Book' }}</h5>
                <p class="card-text">
                    @if($borrowedBook->book)
                        <strong>Author:</strong> {{ $borrowedBook->book->author ?? 'Author Not Available' }} <br>
                        {{-- Check if borrowed_at exists --}}
                        @if($borrowedBook->borrowed_at)
                            <strong>Borrowed On:</strong> {{ $borrowedBook->borrowed_at->format('Y-m-d') }} <br>
                            <strong>Return By:</strong> {{ $borrowedBook->due_date ? $borrowedBook->due_date->format('Y-m-d') : 'Not Returned Yet' }} <br>
                        @else
                            <strong>Borrowed On:</strong> Not Borrowed Yet<br>
                            <strong>Return By:</strong> Not Calculated Yet<br>
                        @endif
                    @else
                        <strong>Book information is not available</strong>
                    @endif
                </p>

                <div class="d-flex justify-content-between">
                    @if($borrowedBook->returned_at)
                        <span class="badge badge-success">Returned</span>
                    {{-- Check if the book is overdue --}}
                    @elseif($borrowedBook->is_overdue)
                        <span class="badge badge-danger">Overdue</span>
                    {{-- If not returned and not overdue, it's still borrowed --}}
                    @else
                        <span class="badge badge-warning">Borrowed</span>
                    @endif
                    <i class="fas fa-clock"></i> <!-- Clock icon for borrowed date -->
                </div>
            </div>
        </div>
    </div>
@endforeach


    </div>
</div>

<div class="container my-4">
    <h3>Categories</h3>
    <div class="links-container">
        <a href="User.getCategories" class="category-link" data-category="name">Name</a>
        <a href="User.getCategories" class="category-link" data-category="php">PHP</a>
        <a href="User.getCategories" class="category-link" data-category="programming">Programming</a>
    </div>
</div>
<div id="category-content" class="mt-4">
    <!-- Books for the selected category will be displayed here -->
    <h4>Books in Selected Category</h4>
    <div id="books-container" class="row">
        <!-- Books will be dynamically added here -->
    </div>
</div>
<!-- Add the loading spinner -->
<div class="loading-spinner" style="display:none;"></div>

<div class="container my-5">
    <h3>Featured Books</h3>
    <div class="row">
        @foreach($featuredBooks as $book)
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="{{ asset('storage/' . $book->bookpicture) }}" class="card-img-top" alt="{{ $book->bookname }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $book->bookname }}</h5>
                    <p class="card-text">{{ $book->author }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


        <!-- Loading spinner for fetch requests -->
        <div class="loading-spinner"></div>

        <!-- Notifications Section -->
        <!-- Notifications Section -->
<div class="container my-4">
    <div class="row">
        <div class="col-12">
        <h3>Notifications</h3>
</div>
</div>
<div class="row">
    <!-- check if there are any notifications -->
     @if($dueSoonBooks->count()>0 || $reservedBooks->count()>0)
    <!--Due soon books notification -->
    @if($dueSoonBooks->count()>0)
    <div class="col-md-6 mb-3">
        <div class="card border-info shadow">
            <div class="card-body">
                <h5 class="card-title text-info">
                    <i class="fas fa-exclamation-circle"></i> Books Due Soon
                </h5>
                <p class="card-text">
                    You have{{$dueSoonBooks->count() }} book{{$dueSoonBooks->count()>1?'s':''}} due soon:
                </p>
                <ul class="list-group list-group-flush">
                    @foreach($dueSoonBooks as $book)
                    <li class="list-group-item d-flex justify-content-between align-items-center"> 
                        {{$book->bookname}}
                        <span class="badge badhe-info">Due: {{$book->due_date->format('Y-m-d')}}</span>
</li>
@endforeach 
                </ul>
            </div>
        </div>
    </div>
@endif
@else 
<!-- No notifications --> 
 <div class="col-12"> 
    <div class="alert alert-secondary" role="alert">
        <i class="fas fa-info-circle"></i>No new notifications
    </div>
</div>
@endif
</div>
</div>
<!-- Reserved books notification -->
 @if($reservedBooks->count()>0)
 <div class="col-md-6 mb-3">
    <div class="card border-warning shadow">
        <div class="card-body">
            <h5 class="card-title text-warning">
                <i class="fas fa-book"></i>Reserved Books Ready for Pickup
            </h5>
            <p class="card-text">
                You have{{$reservedBooks->count()}} reserved book{{$reservedBooks->count()>1?'s':''}} available for pick-up:
            </p>
            <ul class="list-group list-group-flush">
                @foreach($reservedBooks as $book)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  {{$book->title}}
                  <span class="badge badge-warning">Reserved on: {{$book->created_at->format('Y-m-d')}}</span>  
                </li>
                @endforeach
            </ul>
        </div>
    </div>
 </div>
 @endif 
                </div>
                </div>              
<div class="container my-5">
    <h3>Recommended For You</h3>
    <div class="row">
        @foreach($recommendations as $book)
        <div class="col-md-3 mb-4">
        <div class="card h-100">
            <img src="{{ asset('storage/' . $book->bookpicture) }}" class="card-img-top" alt="{{ $book->bookname }}" style="max-height: 200px; object-fit: cover">
            <div class="card-body">
                <h5 class="card-title">{{$book->bookname,20}}</h5>
                <p class="card-text">{{Str::limit($book->description,60)}}</p>
                <a href="{{route('User.bookdetails',['id' => $book->id]) }}" class="btn btn-primary">View Details</a>
            </div>
        </div>
    </div>
    @endforeach 
</div>
</div>
<div class="container my-5">
    <h3>Recent Activities</h3>
    @if($recentActivities->isEmpty())
    <p> No Recent Activities found.</p>
    @else
    <ul class="list-group">
        @foreach($recentActivities as $activity)
        <li class="list-group-item">
            {{$activity->description}}<br>
            <small class="text-muted">
    Date: {{ \Carbon\Carbon::parse($activity->created_at)->format('Y-m-d H:i') }}
</small>

        </li>
        @endforeach
    </ul>
    @endif
</div>
<div id="chatbot">
    <h3>Chat with us</h3>
    <botman-tinker></botman-tinker>
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
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Popper.js for Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<!-- Bootstrap 4 JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.getElementById('logoutButton').addEventListener('click', function() {
        $('#logoutModal').modal('show'); // Show the modal
        setTimeout(function() {
            $('#logoutModal').modal('hide'); // Hide the modal after 3 seconds
            document.getElementById('logout-form').submit(); // Submit the logout form, which uses POST
        }, 3000); // 3 seconds delay
    });

    // Search Functionality
    document.getElementById('searchButton').addEventListener('click', function() {
        const query = document.getElementById('searchQuery').value;
        const loadingSpinner = document.querySelector('.loading-spinner');
        const resultsContainer = document.getElementById('searchResults');

        loadingSpinner.style.display = 'block'; // Show spinner

        fetch(`/user-homepage/search-books?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                resultsContainer.innerHTML = ''; // Clear previous results
                loadingSpinner.style.display = 'none'; // Hide spinner

                if (data.length > 0) {
                    data.forEach(book => {
                        const bookItem = document.createElement('li');
                        bookItem.innerHTML = `
                            <h5>${book.bookname}</h5>
                            <p>Author: ${book.author}</p>
                            <p>Genre: ${book.genreid}</p>
                        `;
                        resultsContainer.appendChild(bookItem);
                    });
                    resultsContainer.scrollIntoView({ behavior: 'smooth' }); // Scroll to results
                } else {
                    resultsContainer.innerHTML = '<p>No books found.</p>';
                }
            })
            .catch(error => {
                loadingSpinner.style.display = 'none'; // Hide spinner
                resultsContainer.innerHTML = '<p>Error fetching search results.</p>';
                console.error('Error fetching search results:', error);
            });
    });

    // Fetch Categories and Books by Category
    
    document.querySelectorAll('.category-link').forEach(link => {
    link.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default link behavior

        const genreName = this.getAttribute('data-category');
        const loadingSpinner = document.querySelector('.loading-spinner');
        const resultsContainer = document.getElementById('books-container');

        // If the current link is active, hide the results and remove the active state
        if (this.classList.contains('active')) {
            resultsContainer.innerHTML = ''; // Clear previous results
            this.classList.remove('active'); // Remove active state
        } else {
            // Remove active state from all links
            document.querySelectorAll('.category-link').forEach(link => link.classList.remove('active'));
            // Set current link as active
            this.classList.add('active');

            // Show the loading spinner
            loadingSpinner.style.display = 'block';

            // Clear previous results
            resultsContainer.innerHTML = '';

            // Make an AJAX request to fetch books for the selected category
            fetch(`/user-homepage/books-by-category/${encodeURIComponent(genreName)}`)
                .then(response => response.json())
                .then(data => {
                    loadingSpinner.style.display = 'none'; // Hide spinner

                    if (data.length > 0) {
                        data.forEach(book => {
                            const bookItem = document.createElement('div');
                            bookItem.className = 'col-md-4 mb-4';
                            bookItem.innerHTML = `
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${book.bookname}</h5>
                                        <p>Author: ${book.author}</p>
                                    </div>
                                </div>
                            `;
                            resultsContainer.appendChild(bookItem);
                        });
                    } else {
                        resultsContainer.innerHTML = '<p>No books found in this category.</p>';
                    }
                })
                .catch(error => {
                    loadingSpinner.style.display = 'none'; // Hide spinner
                    console.error('Error fetching books:', error);
                    resultsContainer.innerHTML = '<p>An error occurred while fetching books.</p>';
                });
        }
    });
});

    
</script>

<script>
    var botmanWidget = {
        chatServer: '/botman',
        title: 'Chat Support',
        mainColor: '#3490dc',
        bubbleBackground: '#3490dc',
        headerTextColor: '#fff',
        aboutText: 'Powered by BotMan',
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js"></script>
</body>
</html>
