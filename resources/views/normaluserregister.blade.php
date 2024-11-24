<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - User Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f8f9fa;
        }
        .container {
            width: 600px; /* Match the width of the login container */
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 70px; /* To ensure it's below the header */
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
       }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-group .required {
            color: red;
            margin-left: 5px;
        }
        .form-actions {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }
        .login-link {
            margin-top: 10px;
        }
        .error-message {
            color: red;
            display: none;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="form-title">Sign Up</h2>
        <div class="error-message" id="errorMessage">Please fill all the required details.</div>
        <form id="registrationForm" action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="userName">Name<span class="required">*</span></label>
                <input type="text" class="form-control" id="userName" name="name" placeholder="Enter name">
            </div>
            <div class="form-group">
                <label for="email">Email<span class="required">*</span></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="registerButton">Register</button>
                <div class="login-link">
                    <span>Already registered?</span> <a href="{{ route('login') }}">Login</a>
                </div>
            </div>
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('registerButton').addEventListener('click', function(event) {
            var userName = document.getElementById('userName').value;
            var email = document.getElementById('email').value;
            var errorMessage = document.getElementById('errorMessage');
            
            if (userName === '' || email === '') {
                event.preventDefault();
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
            }
        });
    </script>
</body>
</html>

