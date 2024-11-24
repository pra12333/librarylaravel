<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Book</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <h2>Reserve Book</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $book->bookname }}</h5>
                <p class="card-text">{{ $book->author }}</p>
                <p>ジャンルID: {{ $book->genreid }}</p>
                <p>出版日: {{ $book->release_date }}</p>
                <form action="{{ route('User.confirmReserve', $book->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning">Confirm Reserve</button>
                    <a href="{{ route('main') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
