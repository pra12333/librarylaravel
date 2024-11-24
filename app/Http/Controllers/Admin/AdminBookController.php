<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\BorrowedBook;
use App\Models\Genre;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminBookController extends Controller
{
    public function showBookList()
    {
        $adminUser = Auth::user();
        $books = Book::all(); // Fetch all books or whatever logic you have
    
        return view('Admin.booklistadmin', compact('adminUser', 'books'));
    }
    


    public function showBookRegister()
    {    
         // fetch all genres from the database to pass to the view
         
         $genres = Genre::all();

         // get the currently authenticated user

         $user = Auth::user();

        return view('Admin.bookregister',compact('genres','user'));
    }

    public function showBookUpdate($id)
    {       
         //fetch the book to be updated

         $book = Book::findOrFail($id);

         // fetch all genres to populate the dropdown

         $genres = Genre::all();

         // get the authenticated user(admin/superadmin)

         $user = Auth::user();

         // pass the book and genres to the view
    
        return view('Admin.bookupdate',compact('book','genres','user'));
    }
          
        public function update(Request $request,$id){
            $request->validate([
                'bookname' =>'required|string|max:255',
                'genreid' =>'required|exists:genres,id',
                'author'=>'required|string|max:255',
                'release_date'=>'required|date',
                'isbn' =>'required|string|max:255|unique:books,isbn,' .$id, // allow the same isbn for the book
                'bookpicture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'total_no_of_copies' =>'required|integer',
            ]);

            $book = Book::findOrFail($id);
            $book->bookname = $request->input('bookname');
            $book->genreid = $request->input('genreid');
            $book->author = $request->input('author');
            // $book->bookpicture = $request->input('bookpicture');
            $book->release_date = $request->input('release_date');
            $book->isbn = $request->input('isbn');
            $book->total_no_of_copies =$request->input('total_no_of_copies');
            $book->featured = $request->has('featured') ? true : false; // if checkbox is checked , set as true

            // set initial_no_of_copies if it was not set before
            if(is_null($book->initial_no_of_copies)) {
                $book->initial_no_of_copies = $book->total_no_of_copies;
            }

            // Handle file upload if a new image is uploaded
    if ($request->hasFile('bookpicture')) {
        // Delete the old image if it exists
        if ($book->bookpicture) {
            Storage::disk('public')->delete($book->bookpicture);
        }

        // Store the new image
        $imagePath = $request->file('bookpicture')->store('images', 'public');
        $book->bookpicture = $imagePath;

        // Log whether the new image exists in storage
    \Log::info('Image exists: ' . Storage::disk('public')->exists($book->bookpicture));
    }
            $book->save();

            return redirect()->route('Admin.booklistadmin')->with('success','book updated successfully');
        }
    

    public function store(Request $request)
    {
        $request->validate([
            'bookname' => 'required|string|max:255',
            'genreid' => 'required|exists:genres,id', //validate that the genre exists
            'bookpicture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author' => 'required|string|max:255',
            'release_date' => 'required|date',
            'isbn' => 'required|string|max:255|unique:books',
            'total_no_of_copies' => 'required|integer',
        ]);

        

        // Handle file upload
        if ($request->hasFile('bookpicture')) {
            $imagePath = $request->file('bookpicture')->store('images', 'public');
        } else {
            $imagePath = null;
        }
        // Log whether the uploaded image exists in storage
    \Log::info('Image exists: ' . Storage::disk('public')->exists($imagePath));

        // Save book details to database
        $book = new Book();
        $book->bookname = strtolower($request->input('bookname')); //convert to lowercase
        $book->genreid = $request->input('genreid');
        $book->bookpicture = $imagePath;
        $book->author = $request->input('author');
        $book->release_date = $request->input('release_date');
        $book->isbn = $request->input('isbn');
        $book->total_no_of_copies = $request->input('total_no_of_copies');
        $book->initial_no_of_copies = $book->total_no_of_copies; // set initial copies to match total copies on creation
        $book->save();

        return redirect()->route('Admin.booklistadmin')->with('success', 'Book registered successfully');
    }

    public function destroy($id){
        $book =Book::findOrFail($id);

        // optionally ,delete the book image from storage if exists 

        if($book->bookpicture) {
            Storage::disk('public')->delete($book->bookpicture);
        }
        // delete the book from the database

        $book->delete();

        // redirect back to the book list with a success message
        return redirect()->route('Admin.booklistadmin')->with('success','book deleted successfully');
    }

    public function search(Request $request) {
        $query = $request->input('query');
    
        // Search for all books matching the query
        $books = Book::whereRaw('BINARY bookname LIKE ?',['%'. $query.'%'])->get();

        // get the currently authenticated user
        $user = Auth::user();
    
        if ($books->isNotEmpty()) {
            // Handle the collection, for example, by listing the results
            return view('admin.booksearchresults', compact('books','user'));
        } else {
            // Redirect back with an error message if no books are found
            return redirect()->route('Admin.booklistadmin')->with('error', 'No books found');
        }
    }
    
    // public function approveRequest($requestId) {
    //     $bookRequest = BookRequest ::findOrFail($requestId);

    //     // update the status to 'approved'

    //     $bookRequest->update(['status' => 'approved']);

    //     // logic to issue the book to the user
    //     // Optionally, log the action for debugging
    // \Log::info('Book request approved: ' . $bookRequest->id);

    //     BorrowedBook::create([
    //         'user_id' =>$bookRequest->user_id,
    //         'book_id' => $bookRequest->book_id,
    //         'borrowed_at' =>now()
    //     ]);

    //     return redirect()->back()->with('message','book request approved and issued successfully');
    // }
    
    // public function showBookRequests() {
    //     \Log::info('showBookRequests method is being called'); 
    //     // Fetch all pending book requests
    //     $bookRequests = BookRequest::where('status', 'pending')->get();
    //     $pendingCount = $bookRequests->count(); // count the number of pending requests
    //     // Log the pending count and book requests
    // \Log::info('Pending Book Requests Count: ' . $pendingCount);
    // \Log::info('Book Requests: ', $bookRequests->toArray());
    // \Log::info('Pending Count: ' . $pendingCount);
    //     // Pass the book requests to the view
    //     return view('Admin.dashboard', compact('bookRequests','pendingCount'));
    // }
    // public function rejectRequest($requestId) {
    //     $bookRequest = BookRequest::findOrFail($requestId);
    
    //     // Update the request status to 'rejected'
    //     $bookRequest->update(['status' => 'rejected']);

    //     // Optionally, log the action for debugging
    // \Log::info('Book request rejected: ' . $bookRequest->id);

    
    //     return redirect()->back()->with('message', 'Book request rejected.');
    // }
   public function viewRequests() {
    $requests = BookRequest::where('status','pending')->with('user','book')->get();
    return view('Admin.requests',compact('requests'));
   }

   public function approveRequest($id) {
    $request = BookRequest::find($id);
   if($request->status !== 'pending') {
    return redirect()->back()->with('error','Request is already processed');
   }

   $request->status ="approved";
   $request->save();

   return redirect()->back()->with('status','Book request approved successfully');
          
   }

   public function rejectRequest(Request $request,$id) {
    $request->validate([
        'admin_note' => 'required|string', //Admin provides a reason for rejection
    ]);
    $bookRequest = BookRequest::find($id);
    if($bookRequest->status !== 'pending') {
        return redirect()->back()->with('error','Request is already processed');
    }
    $bookRequest->status = 'rejected';
    $bookRequest->admin_note = $request->input('admin_note');
    $bookRequest->save();

    return redirect()->back()->with('status','Book request rejected successfully');
   }
}
