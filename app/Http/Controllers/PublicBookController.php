<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Log;

class PublicBookController extends Controller
{
    public function index()
    {    
        Log::debug('homepage route accessed');
        $books = Book::all();
        return view('User.bookborrow', compact('books'));
    }

    public function searchBooks(Request $request)
    {
        $query = $request->input('query');
        $books = Book::whereRaw('BINARY bookname LIKE ?',  ["%{$query}%"])->get();

        // Check if any books were found
        if ($books->isEmpty()) {
            // Return the view with a message if no books were found
            return view('User.bookborrow', ['books' => $books, 'message' => 'Book not found']);
        }

        // If books are found, return the view with the books
        return view('User.bookborrow', compact('books'));
    }


}
