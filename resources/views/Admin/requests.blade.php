<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>Pending Book Requests</h1>

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<table>
    <thead>
        <tr>
            <th>User</th>
            <th>Book</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requests as $request)
            <tr>
                <td>{{ $request->user->name }}</td>
                <td>{{ $request->book->title }}</td>
                <td>{{ ucfirst($request->status) }}</td>
                <td>
                    <form action="{{ route('admin.approveRequest', $request->id) }}" method="POST">
                        @csrf
                        <button type="submit">Approve</button>
                    </form>

                    <form action="{{ route('admin.rejectRequest', $request->id) }}" method="POST">
                        @csrf
                        <button type="submit">Reject</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>