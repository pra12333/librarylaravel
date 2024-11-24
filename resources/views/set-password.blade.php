<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password</title>
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
        .form-group label {
            font-weight: bold;
        }
        .form-actions {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
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
        <h2 class="form-title">Set Your Password</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="setPasswordForm" action="{{ route('set-password.post') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <label for="password">New Password<span class="required">*</span></label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password<span class="required">*</span></label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="setPasswordButton">Set Password</button>
            </div>
        </form>
    </div>
</body>
</html>
