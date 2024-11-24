<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @if(session('error'))
    <div class="alert alert-danger">
        {{session('error')}}
    </div>
    @endif
    <div class="container mt-5">
        <h2>Borrow Book</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $book->bookname }}</h5>
                <p class="card-text">{{ $book->author }}</p>
                <p>ジャンルID: {{ $book->genreid }}</p>
                <p>出版日: {{ $book->release_date }}</p>
                <form action="{{ route('User.confirmBorrow', $book->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="no_of_copies">Number of Copies:</label>
                        <input type="number" name="no_of_copies" id="no_of_copies" class="form-control" min="1" max="{{ $book->total_no_of_copies }}" value="1" required>
                    </div>
                    <button type="submit" class="btn btn-success">Confirm</button>
                    <a href="{{ route('User.booklist', $book->id) }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
