<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <h1>{{$book->bookname}}</h1>
        <p>Author:{{$book->author}}</p>
        <p>Description: {{$book->description}}</p>

        <!-- display the qr code -->
        <div>
            <h3>Scan this QR code to borrow the book:</h3>
            {!! $qrCode!!}
        </div>
</div>
</body>
</html>