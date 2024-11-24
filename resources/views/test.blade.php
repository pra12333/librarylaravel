<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Add Genre (Admin)</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Full page background */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #e6f7e3; /* Light green background */
        }
        
        /* Form container styles */
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 400px; /* Set the width */
        }

        .form-container h3 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
            color: #2a2a2a;
        }

        .form-control {
            border-radius: 30px; /* Rounded input fields */
            padding: 15px;
            font-size: 16px;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            border-radius: 30px;
            background-color: #4caf50; /* Green button */
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-container {
                width: 90%; /* Make the form take up more space on smaller screens */
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h3>Add New Genre</h3>
        <form action="{{ route('genres.create') }}" method="POST">
            @csrf
            <input type="text" class="form-control" id="genreName" name="name" placeholder="Enter genre name" required>
            <button type="submit" class="btn-submit">Add Genre</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
