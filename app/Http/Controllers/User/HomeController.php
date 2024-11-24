<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BorrowedBook;
use App\Models\ReservedBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Book;
use App\Models\RecentActivity;

class HomeController extends Controller
{
    
    public function showHomePage()
    {
        $user = Auth::user();
        $roleAdmin = User::ROLE_ADMIN;
        $roleSuperAdmin = User::ROLE_SUPERADMIN;

        // fetch featured books 

        $featuredBooks = Book::where('featured',true)->get();

        // fetch the borrowed books for the current user

        $borrowedBooks = BorrowedBook::with('book')
             ->where('user_id',$user->id)
             ->whereNotNull('borrowed_at')
             ->orderBy('borrowed_at','desc')
             ->limit(5) // limit to 5 borrowed bookos for the display
             ->get()
             ->map(function ($borrowedBook){
                $borrowedBook->due_date = $borrowedBook->borrowed_at ? $borrowedBook->borrowed_at->addDays(7) : null;
                //check if the book is overdue by comparing the due date with the current date
                $borrowedBook->is_overdue = $borrowedBook->due_date && Carbon::now()->greaterThan($borrowedBook->due_date);
                // Debugging: Log the dates and overdue status
        \Log::info('Book Check:', [
            'book_id' => $borrowedBook->book_id,
            'borrowed_at' => $borrowedBook->borrowed_at,
            'due_date' => $borrowedBook->due_date,
            'is_overdue' => $borrowedBook->is_overdue,
            'current_time' => Carbon::now(),
        ]);
                return $borrowedBook;
             });
        //      dd($borrowedBooks->toArray());

            // fetch all overdue books
            $overdueBooks = BorrowedBook::with('book')
                          ->where('user_id',$user->id)
                          ->whereNull('returned_at')
                          ->where('borrowed_at','<',Carbon::now()->subDays(7)) // overdue by more than 7 days
                          ->orderBy('borrowed_at','desc')
                          ->get();

                      
            
            // fetch non-overdue books with a limit
            $nonOverdueBooks = BorrowedBook::with('book')
                            ->where('user_id',$user->id)
                            ->whereNull('returned_at')
                            ->where('borrowed_at','>=',Carbon::now()->subDays(7)) // not overdue
                            ->orderBy('borrowed_at','desc')
                            ->take(5-$overdueBooks->count()) // adjust limit based on the number of overdue books
                            ->get();

                            \Log::info('Non-Overdue Books (No Limit):', ['count' => $nonOverdueBooks->count(), 'books' => $nonOverdueBooks->toArray()]);

                // merge the overdue books and non overdue books

                $borrowedBooks = $overdueBooks->merge($nonOverdueBooks)
                            ->sortByDesc('borrowed_at') // sort by borrowed date to maintain order
                            ->take(5); // ensure total limit of 5 books
                            // map to add due date and overdue status
                           $borrowedBooks = $borrowedBooks->map(function($borrowedBook) {
                            $borrowedBook->due_date = $borrowedBook->borrowed_at ? $borrowedBook->borrowed_at->addDays(7) : null;
                            $borrowedBook->is_overdue = $borrowedBook->due_date && Carbon::now()->greaterThan($borrowedBook->due_date);
                            return $borrowedBook;
                           });
        
        // Fetch borrowed books that are due soon (e.g., due in the next 7 days)
        $dueSoonBooks = BorrowedBook::where('user_id', $user->id)
            ->whereNull('returned_at')  // Only consider books that haven't been returned
            ->get()
            ->map(function($borrowedBook) {
                // Calculate the due date (assuming a 14-day borrowing period)
                $borrowedBook->due_date = Carbon::parse($borrowedBook->borrowed_at)->addDays(14);
                return $borrowedBook;
            });  

            // dd($borrowedBooks);

        // Fetch reserved books
        $reservedBooks = ReservedBook::where('user_id', $user->id)->get();

        // fetch the genres of books the user has borrowed
        $borrowedGenres = BorrowedBook::where('user_id',$user->id)
                                      ->with('book.genre') // make sure the book and the genre is loaded
                                      ->get()
                                      ->pluck('book.genre.id')
                                      ->unique();

        // Fetch recommended books based on the genres the user has borrowed
        $recommendations = Book::whereIn('genreid',$borrowedGenres)
                         ->whereNotIn('id',$borrowedBooks->pluck('book_id')) // exclude already borrowed books
                         ->limit(5)
                         ->get();
        
        // Fetch recent activities for the logged-in user
        $recentActivities = RecentActivity::where('user_id',$user->id) // filter by the logged-in user
                                          ->orderBy('created_at','desc')
                                          ->limit(5) // limit to the 5 most recent activities
                                          ->get();

        return view('User.homepage', compact('user', 'roleAdmin', 'roleSuperAdmin','dueSoonBooks', 'reservedBooks','borrowedBooks','featuredBooks','recommendations','recentActivities'))->with('userModel',new User);
    }




    
}
