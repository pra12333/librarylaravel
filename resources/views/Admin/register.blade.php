<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Library System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="form-title">Admin Register Form</h2>
        <div class="form-container">
        <form method="POST" action="{{ route('admin.register.submit') }}">
    @csrf
    <div>
        <label>Name</label>
        <input type="text" name="name" required>
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    <div>
        <label>Password</label>
        <input type="password" name="password" required>
    </div>
    <div>
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" required>
    </div>
    <button type="submit">Register</button>
</form>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
