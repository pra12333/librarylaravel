<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Account Settings</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .container {
            margin-top: 20px;
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
        /* Profile picture styling */
        .profile-picture-container {
            position: absolute;
            top: 40px;
            right: 20px;
            width: 120px;
            height: 120px;
        }
        .profile-picture-wrapper {
            position: relative;
            display: inline-block;
        }
        .profile-picture {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .edit-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: white;
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
        input[type="file"] {
            display: none;
        }
        .password-hint {
            display: none;
        }
        .navbar {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

    </style>
</head>
<body>
    <!-- Success and Error Messages -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif 

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
                <li class="nav-item"><a class="nav-link" href="{{ route('User.myborrow') }}"><i class="fa fa-shopping-cart"></i> My Borrowed Books</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('User.myreservedbooks') }}"><i class="fa fa-bookmark"></i> My Reserved Books</a></li>
                <!-- <li class="nav-item"><a class="nav-link" href="{{ route('user.viewCart') }}"><i class="fa fa-shopping-cart"></i> My Cart</a></li> -->
                <li class="nav-item"><a class="nav-link" href="{{ route('User.search') }}"><i class="fa fa-search"></i> Search Books</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('wishlist.index') }}"><i class="fa fa-heart"></i>Wishlist</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('User.account') }}"><i class="fa fa-user"></i> Account Settings</a></li>
                @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                    <li class="nav-item"><a class="nav-link" href="{{ route('Admin.dashboard') }}"><i class="fa fa-tachometer-alt"></i> Admin Dashboard</a></li>
                @endif
                @if(Auth::user()->isSuperAdmin())
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.superadmin.panel') }}"><i class="fa fa-user-shield"></i> Super Admin Panel</a></li>
                @endif
                <!-- Logout Button -->
                <li class="nav-item">
                    <a class="nav-link" href="#" id="logoutButton"><i class="fa fa-sign-out-alt"></i> Logout</a>
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

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Profile Picture and Account Settings Form -->
    <div class="container">
        <div class="profile-picture-container">
            <div class="profile-picture-wrapper">
                @if(Auth::user()->profile_picture)
                    <img src="{{ asset('storage/images/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="profile-picture">
                @else
                    <div class="profile-picture" style="background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                        <span>No Image</span>
                    </div>
                @endif
                <!-- Edit Profile Picture Icon -->
                <label for="profilePicture" class="edit-icon">
                    <i class="fas fa-pencil-alt"></i>
                </label>
            </div>
            <!-- Hidden File Input for Profile Picture -->
            <form action="{{ route('user.updateProfilePicture') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="profile_picture" id="profilePicture" accept="image/jpeg,image/png,image/jpg" onchange="this.form.submit();">
            </form>
        </div>

        <h2>Account Settings</h2>
        <div class="row">
            <div class="col-md-8">
                <!-- Update Profile Form -->
                <form id="updateProfileForm" action="{{ route('user.updateProfile') }}" method="POST">
                    @csrf
                    <h3>Personal Information</h3>
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" name="name" class="form-control" id="fullName" placeholder="Enter your full name" value="{{ Auth::user()->name }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" value="{{ Auth::user()->email }}">
                    </div>
                    <div class="form-group">
                        <label for="phoneNumber">Phone Number</label>
                        <input type="text" name="phone" class="form-control" id="phoneNumber" placeholder="Enter your phone number" value="{{ Auth::user()->phone }}" maxlength="11" pattern="0\d{9,10}">
                    </div>
                    <button type="submit" class="btn btn-primary" id="updateProfileButton">Update Profile</button>
                </form>

                <!-- Change Password Form -->
                <form id="updatePasswordForm" action="{{ route('user.updatePassword') }}" method="POST" class="mt-4">
                    @csrf
                    <h3>Account Security</h3>
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <div class="input-group">
                            <input type="password" name="current_password" class="form-control" id="currentPassword" placeholder="Enter your current password" required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fa fa-eye toggle-password" data-toggle="currentPassword"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <div class="input-group">
                            <input type="password" name="new_password" class="form-control" id="newPassword" placeholder="Enter your new password" required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fa fa-eye toggle-password" data-toggle="newPassword"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" name="new_password_confirmation" class="form-control" id="confirmPassword" placeholder="Confirm your new password" required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fa fa-eye toggle-password" data-toggle="confirmPassword"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="updatePasswordButton">Update Password</button>
                </form>

                <!-- Confirmation Modal -->
                <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmationModalLabel">Confirm Changes</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to make these changes?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="confirmActionButton">Yes, Make Changes</button>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var confirmActionButton = document.getElementById('confirmActionButton');
            var formToSubmit = null;

            // Event listener for the Update Profile button
            document.getElementById('updateProfileButton').addEventListener('click', function(e) {
                e.preventDefault();
                formToSubmit = document.getElementById('updateProfileForm');
                $('#confirmationModal').modal('show');
            });

            // Event listener for the Update Password button
            document.getElementById('updatePasswordButton').addEventListener('click', function(e) {
                e.preventDefault();
                formToSubmit = document.getElementById('updatePasswordForm');
                $('#confirmationModal').modal('show');
            });

            // Confirm action button inside modal
            confirmActionButton.addEventListener('click', function() {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
                $('#confirmationModal').modal('hide');
            });

            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(function(element) {
                element.addEventListener('click', function() {
                    var passwordFieldId = this.getAttribute('data-toggle');
                    var passwordField = document.getElementById(passwordFieldId);
                    if (passwordField) {
                        if (passwordField.type === 'password') {
                            passwordField.type = 'text';
                            this.classList.remove('fa-eye');
                            this.classList.add('fa-eye-slash');
                        } else {
                            passwordField.type = 'password';
                            this.classList.remove('fa-eye-slash');
                            this.classList.add('fa-eye');
                        }
                    } else {
                        console.error('Password field not found for ID:', passwordFieldId);
                    }
                });
            });
        });

        document.getElementById('logoutButton').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the immediate form submission

    // Show the logout modal
    $('#logoutModal').modal('show');

    // Wait for 3 seconds before submitting the form (to show the modal for a brief moment)
    setTimeout(function() {
        document.getElementById('logout-form').submit(); // Submit the form (this will trigger the actual logout)
    }, 3000); // 3-second delay before form submission
});

document.getElementById('phoneNumber').addEventListener('input', function (e) {
    const value = e.target.value;
    if (!/^\d*$/.test(value)) {
        e.target.setCustomValidity('Please enter only numbers.');
    } else if (!/^0\d{0,10}$/.test(value)) {
        e.target.setCustomValidity('Please enter a valid Japanese phone number.');
    } else {
        e.target.setCustomValidity('');
    }
    e.target.reportValidity();
});


    </script>
</body>
</html>
