<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Login</title>
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
        .header {
            width: 100%;
            background: #343a40;
            padding: 10px 20px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            position: fixed;
            top: 0;
            z-index: 1000;
        }
        .container {
            width: 600px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 70px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
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
        <h2 class="form-title">Login</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="error-message" id="errorMessage">Please fill all the required fields.</div>
        <form id="loginForm" action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email<span class="required">*</span></label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="form-group">
                <label for="password">Password<span class="required">*</span></label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Login</button>
                <div class="login-link">
                    <a href="#" data-toggle="modal" data-target="#forgotPasswordModal">Forgot your password?</a>
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

    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="forgotPasswordForm">
                        @csrf
                        <div class="form-group">
                            <label for="reset-email">Email address</label>
                            <input type="email" class="form-control" id="reset-email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Reset Link</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('forgotPasswordForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const email = document.getElementById('reset-email').value;

            fetch('{{ route('password.email') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    $('#forgotPasswordModal').modal('hide');
                } else {
                    alert(data.email);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>

