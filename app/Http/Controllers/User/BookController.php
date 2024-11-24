<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\BorrowedBook;
use App\Models\ReservedBook;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\RecentActivity;
use App\Models\User;
use App\Notifications\BookAvailableNotification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    public function hasOverdueBooks($userId) {
        $user = User::find($userId);

        //check if the user has overdue books
        $hasOverdueBooks = BorrowedBook::where('user_id',$userId)
                  ->whereNull('returned_at')
                  ->where('borrowed_at','<',Carbon::now()->subDays(7)) // overdue by more than 7 days
                  ->exists();

                  // update the has_overdue_books column in the users table
                  $user->has_overdue_books = $hasOverdueBooks ? 1:0;
                  $user->save();
                //   dd($user);
                  return $hasOverdueBooks;
    }
    
   public function showBook($id) {
    // find the book by its ID
    $book = Book::findOrFail($id);
    //generate the qr code linking to the books automatic borrow route
    $qrCode = QrCode::size(200)->generate(route('books.borrowByQr',['id' => $book->id]));
    // pass the book and the qr code to the view
    return view('User.bookshow',compact('book','qrCode'));
   }
   public function showBookList(): JsonResponse
{
    $books = Book::all();
    return response()->json([
        'books' => $books,
        'userCannotBorrow' => false
    ]);
}

   public function borrowBookByQr($id) {
    $user = Auth::user();
    $book = Book::findOrFail($id); // find the book by its Id
    // check if the bbok is available for borrwoing
    if($book->totoal_no_of_copies >0) {
        //perform borrowing logic
        $book->total_no_of_copies -= 1; // deduct the available copies
        $book->save();

        BorrowedBook::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
            'returned_at' => null, // book is not yet returned
        ]);
        return redirect()->route('User.myborrow')->with('status','You have successfully borrowed the book via QR code!');
    }else{
        return redirect()->route('User.booklist')->with('error', 'This book is not available for borrowing');
    }
   }
    public function showmyBookBorrow()
    {
        $user = Auth::user();

        $this->hasOverdueBooks($user->id);
        \Log::info('Current User:', ['user_id' => $user->id, 'name' => $user->name]);

        $borrowedBooks = BorrowedBook::where('user_id', $user->id)
                                     ->whereNull('returned_at')
                                     ->get()
                                     ->map(function ($borrowedBook) {
                                         $borrowedBook->due_date = Carbon::parse($borrowedBook->borrowed_at)->addDays(6);
                                         return $borrowedBook;
                                     });

        return view('User.myborrow', compact('borrowedBooks'));
    }

    
    public function showBorrowPage($id)
    {
        $book = Book::find($id);

        if (!$book) {
            \Log::error("Book with ID {$id} not found");
            abort(404, 'Book not found');
            return redirect()->route('User.bookborrow')->with('status', 'book not found');
        }

        \Log::info("Book with ID {$id} loaded", ['book' => $book]);
        return view('User.borrow', compact('book'));
    }

    public function confirmBorrow(Request $request, $id)
    {
        $user = Auth::user();
    
        // Check if the user has overdue books
        if ($this->hasOverdueBooks($user->id)) {
            return redirect()->route('User.myborrow')->with('error', 'You cannot borrow a new book until you return your overdue books.');
        }
    
        // Validate number of copies
        $request->validate([
            'no_of_copies' => 'required|integer|min:1',
        ]);
    
        // Find the book by ID
        $book = Book::find($id);
        // dd($book);
    
        // If the book doesn't exist, return an error response
        if (!$book) {
            return redirect()->back()->with('error', 'Book not found.');
        }
    
        // Get the requested number of copies
        $requestedCopies = $request->input('no_of_copies');
    
        // Check if there are enough copies available to borrow
        if ($book->total_no_of_copies >= $requestedCopies) {
            // Reduce the number of available copies
            $book->total_no_of_copies -= $requestedCopies;
            $book->save();
    
            // Create a new borrowed book record
            BorrowedBook::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'borrowed_at' => Carbon::now(),
                'returned_at' => null,
                'no_of_copies' => $requestedCopies,
            ]);
    
            // Create recent activity
            $data = [
                'user_id' => $user->id,
                'description' => 'User ' . $user->name . ' borrowed ' . $requestedCopies . ' copies of the book: ' . $book->bookname,
                'expires_at' => Carbon::now()->addHours(48),
            ];
    
            try {
                RecentActivity::create($data);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error creating recent activity: ' . $e->getMessage());
            }
    
            // Redirect to the user's borrowed books page with a success message
            return redirect()->route('User.myborrow')->with('status', 'Book borrowed successfully');
        } else {
            return redirect()->route('User.bookborrow', ['id' => $id])->with('error', 'Not enough copies available.');
        }
    }
    
    public function nowborrowing()
    {
        $books = Book::all();
        return view('User.bookborrow', compact('books'));
    }
    public function confirmReserve($id)
    {
        $book = Book::find($id);
        $user = auth()->user();
    
        // Check if the book exists
        if (!$book) {
            \Log::error("Book with ID {$id} not found for reservation");
            return redirect()->route('User.bookborrow', ['id' => $id])->with('status', 'Book not found');
        }
    
        // Check if the book is already borrowed (total_no_of_copies is 0)
        if ($book->total_no_of_copies <= 0) {
            // The book is borrowed, create a pending reservation for the user
            $reservation = ReservedBook::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'reserved_at' => now(),
                'reservation_status' => 'pending', // Mark reservation as pending
                'is_ready_for_pickup' => false,
                'is_picked_up' => false,
            ]);
    
            \Log::info("Book reserved successfully", ['user_id' => $user->id, 'book_id' => $book->id]);
    
            return redirect()->route('User.myreservedbooks')->with('status', 'Reservation placed! You will be notified when the book becomes available.');
        } else {
            // If the book is not borrowed, redirect the user to borrow the book
            return redirect()->route('User.bookborrow', ['id' => $book->id])->with('status', 'The book is available for borrowing.');
        }
    }
    
    public function requestBorrow(Request $request, $id)
{
    $user = Auth::user();

    // Check if the user has overdue books
    if ($this->hasOverdueBooks($user->id)) {
        return redirect()->route('User.booklist')->with('error', 'You cannot request to borrow a new book until you return your overdue books.');
    }

    // Validate the request
    $request->validate([
        'book_id' => 'required|exists:books,id',
    ]);

    // Find the book by ID
    $book = Book::findOrFail($id);

    // Check if the book is available
    if ($book->total_no_of_copies <= 0) {
        // Create a borrow request since there are no available copies
        BookRequest::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'pending',
        ]);

        return redirect()->route('User.booklist')->with('status', 'Your request to borrow the book has been sent to the admin.');
    }

    // Continue with any other request logic...
}

  
    public function showMyReservedBooks()
    {
        $user = Auth::user();
        $reservedBooks = ReservedBook::where('user_id', $user->id)->with('book')->get();

        foreach ($reservedBooks as $reservedBook) {
            $currentTime = now();
            if ($reservedBook->expires_at && now()->greaterThan($reservedBook->expires_at)) {
                $book = $reservedBook->book;
                $book->total_no_of_copies += 1;
                $book->save();
                $reservedBook->delete();
            } else {
                $reservedBook->expires_in = $currentTime->diffForHumans($reservedBook->expires_at);
            }
        }

        return view('User.myreservedbooks', compact('reservedBooks'));
    }

    public function confirmReservation($id)
    {
        $reservedBook = ReservedBook::findOrFail($id);
        return redirect()->route('User.borrow', $reservedBook->book_id)->with('status', 'Please proceed to borrow the book');
    }

    public function cancelReservation($id)
    {
        $reservedBook = ReservedBook::findOrFail($id);
        $book = $reservedBook->book;

      // log the reservation cancellation action
      \Log::info('cancelling reservation for book:',['book_id' => $book->id,'user_id' =>$reservedBook->user_id]);

      // simply delete the reservation without changing the number of copies
      $reservedBook->delete();

      \Log::info('Reservation cancelled successfully for user',['book_id' =>$book->id]);
        return redirect()->route('User.myreservedbooks')->with('status', 'Your reservation has been canceled');
    }

    public function searchBooks(Request $request) // search function for search.blade.php 
    {
        $query = $request->input('query');
        $books = Book::whereRaw('BINARY bookname LIKE ?',  ["%{$query}%"])
                     ->orWhereRaw(' BINARY author LIKE ?' ,  ["%{$query}%"])
                     ->orWhereRaw('BINARY genreid LIKE ?',  ["%{$query}%"])
                     ->get();

                     // check if no books were found
                     if($books->isEmpty()) {
                        return response()->json(['message'=>'No books found'],404);
                     }

        return response()->json($books);
    }

    public function returnBook(Request $request, $id)
{
    \Log::info('Return button clicked for book', ['book_id' => $id, 'user_id' => auth()->user()->id]);

    // Fetch the borrowed book record
    $borrowedBook = BorrowedBook::findOrFail($id);
    $book = $borrowedBook->book;

    \Log::info('Returning book:', ['borrowedBook' => $borrowedBook->toArray()]);

    // Calculate the return date (assuming 7 days return window)
    $returnDate = $borrowedBook->borrowed_at->addDays(7); 
    $isOverdue = now()->greaterThan($returnDate);

    // Increment total number of copies but do not exceed the initial number of copies
    $book->total_no_of_copies = min($book->total_no_of_copies + $borrowedBook->no_of_copies, $book->initial_no_of_copies);
    $book->save();

    // Mark the book as returned
    $borrowedBook->returned_at = now();
    $borrowedBook->save();

    \Log::info('Book returned successfully', ['book_id' => $borrowedBook->book_id, 'user_id' => $borrowedBook->user_id]);

    // Notify the user if the book was overdue
    if ($isOverdue) {
        \Log::info('Book was overdue', ['book_id' => $borrowedBook->book_id, 'user_id' => $borrowedBook->user_id]);
        return redirect()->route('User.myborrow')->with('status', 'Book returned successfully, but it was overdue.');
    }

    // Check if there are pending reservations for this book
    $nextReservation = ReservedBook::where('book_id', $borrowedBook->book_id)
                                   ->where('reservation_status', 'pending')
                                   ->orderBy('reservation_order')
                                   ->first();

    if ($nextReservation) {
        // Update the reservation status to ready_for_pickup
        $nextReservation->update([
            'reservation_status' => 'ready_for_pickup',
            'is_ready_for_pickup' => true,
            'expires_at' => now()->addHours(24) // Set the expiration for 24 hours
        ]);

        // Notify the user that their reserved book is available
        $nextReservation->user->notify(new BookAvailableNotification($nextReservation->book_id));

        \Log::info('User notified for reserved book', ['user_id' => $nextReservation->user_id, 'book_id' => $borrowedBook->book_id]);
    }

    // // Log the return activity
    // RecentActivity::create([
    //     'description' => 'User ' . auth()->user()->name . ' returned the book: ' . $borrowedBook->book->bookname, // Use 'bookname' instead of 'title'
    //     'expires_at' => Carbon::now()->addHours(48),
    // ]);

    return redirect()->route('User.myborrow')->with('status', 'Book returned successfully.');
}

    
    public function searchmyBooks(Request $request) {
        // retrieve the search query from the request

        $query = $request->input('query');

        dd('Search query:', $query);

        // search for books basec on the query

        $books = Book::whereRaw('BINARY bookname LIKE ?', ["%{$query}%"])
        ->orWhereRaw('BINARY author LIKE ?',["%{$query}%"])
        ->orWhereRaw('BINARY genreid LIKE ?', ["%{$query}%"])
        ->get();

        dd('Search results:', $books->toArray());

        // return the search results as json

        return response()->json($books);

}

public function searchBooksForHomepage(Request $request) {
 try {
     $query = $request->input('query');
     $books = Book::whereRaw('BINARY bookname LIKE ?',  ["%{$query}%"])
         ->orWhereRaw('BINARY author LIKE ?',  ["%{$query}%"])
         ->orWhereRaw('BINARY genreid LIKE ?',  ["%{$query}%"])
         ->get();

     return response()->json($books);
 } catch (\Exception $e) {
     \Log::error('Search error: ' . $e->getMessage());
     return response()->json(['error' => 'An error occurred while searching'], 500);
 }
}
public function getCategories()
{
try {
 $categories = DB::table('genres')
                 ->join('books', 'genres.id', '=', 'books.genreid')
                 ->select('genres.name')
                 ->distinct()
                 ->get();

 return response()->json($categories);
} catch (\Exception $e) {
 \Log::error('Error fetching categories: ' . $e->getMessage());
 return response()->json(['error' => 'An error occurred while fetching categories'], 500);
}
}

public function getBooksByCategory($genre)
{
try {
 // Fetch the genre ID based on the genre name
 $genreId = DB::table('genres')->where('name', $genre)->value('id');
 
 if (!$genreId) {
     return response()->json([]); // No genre found, return empty array
 }

 // Now, fetch the books with that genre ID
 $books = Book::where('genreid', $genreId)->get();

 return response()->json($books);
} catch (\Exception $e) {
 \Log::error('Error fetching books by category: ' . $e->getMessage());
 return response()->json(['error' => 'An error occurred while fetching books'], 500);
}
}
public function pickupBook($id)
{
    $user = Auth::user();

    // check if the user has overdue books
    if($this->hasOverdueBooks($user->id)) {
        return redirect()->route('User.myreservedbooks')->with('error','You cannot pick up ');
    }
    // find the reservation record
    $reservedBook = ReservedBook::findOrFail($id);
    $book = $reservedBook->book;

    \Log::info('Pickup Check', [
        'is_ready_for_pickup' => $reservedBook->is_ready_for_pickup,
        'is_picked_up' => $reservedBook->is_picked_up
    ]);

    \Log::info('Pickup Check', ['is_ready_for_pickup' => $reservedBook->is_ready_for_pickup, 'is_picked_up' => $reservedBook->is_picked_up]);


    // check if the book is ready for pickup and not already picked up
    if (!$reservedBook->is_ready_for_pickup || $reservedBook->is_picked_up) {
        return redirect()->route('User.myreservedbooks')->with('error', 'This book is not ready for pickup or has already been picked up');
    }

    // Mark the reserved book as picked up
    $reservedBook->is_picked_up = true;
    $reservedBook->save();

    // remove the reservation record from the reserved_books table

    $reservedBook->delete();

    // create a new borrowed book entry
    BorrowedBook::create([
        'user_id' => auth()->user()->id,
        'book_id' => $book->id,
        'borrowed_at' => now(),
        'returned_at' => null, // explicitly set returned_at to nul
        'no_of_copies' =>1, 
    ]);

    // decrease the total number of available copies of the book

    $book->total_no_of_copies -= 1;
    $book->save();
    

    // Redirect to the borrow page with the book details
   return redirect()->route('User.myborrow')->with('status','book picked up and borrowed successfully');
}


public function showBookDetails($id) {
    // get the authenticated user
    $user = Auth::user();
    //define roles
    $roleAdmin ='Admin';
    $roleSuperAdmin ='SuperAdmin';
    // logic for displaying book details
    $book = Book::findOrFail($id);
    return view('User.bookdetails',compact('book','user','roleAdmin','roleSuperAdmin'));
}
    


    public function clearNotification($id)
    {
        $bookRequest = BookRequest::findOrFail($id);
        $bookRequest->status = 'cleared';
    }
    // Notify the user if the book was overdue


    public function getBooksData()
{
    // Fetch all books or apply any filtering logic as needed
    $books = Book::all();
    
    // Return books as JSON
    return response()->json($books);
}

}

