<!-- resources/views/emails/verify.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
</head>
<body>
    <p>Click the link below to verify your email address:</p>
    <a href="{{ url('/verify-email/'.$token) }}">Verify Email</a>
</body>
</html>