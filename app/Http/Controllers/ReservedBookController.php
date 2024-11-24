<?php

namespace App\Http\Controllers;

use App\Models\ReservedBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;


class ReservedBookController extends Controller
{
    public function reserveBook($bookId)
{
    $user = Auth::user();

    // Check if the book is already reserved by this user
    $existingReservation = ReservedBook::where('user_id', $user->id)
                                       ->where('book_id', $bookId)
                                       ->first();

    if ($existingReservation) {
        return redirect()->back()->with('error', 'You have already reserved this book.');
    }

    // Fetch the book and check availability
    $book = Book::findOrFail($bookId);
    $borrowedCount = $book->borrowedBooks()->count();
    $reservedCount = ReservedBook::where('book_id', $book->id)->where('is_ready_for_pickup', false)->count();
    $availableCopies = $book->total_no_of_copies - ($borrowedCount + $reservedCount);

    if ($availableCopies > 0) {
        // Create a new reservation and mark as ready for pickup
        ReservedBook::create([
            'user_id' => $user->id,
            'book_id' => $bookId,
            'reserved_at' => now(),
            'is_ready_for_pickup' => true, // Automatically set to true
            'is_picked_up' => false,
        ]);

        // Reduce the available copies
        $book->total_no_of_copies -= 1;
        $book->save();

        return redirect()->back()->with('success', 'Book reserved and marked as ready for pickup.');
    } else {
        return redirect()->back()->with('error', 'No available copies to reserve.');
    }
}

    
    public function markAsReadyForPickup($reservedBookId) {
        $reservedBook = ReservedBook::findOrFail($reservedBookId);
    
        // Fetch the associated book
        $book = $reservedBook->book;
    
        // Calculate the number of borrowed and reserved copies
        $borrowedCount = $book->borrowedBooks()->count();
        $reservedCount = ReservedBook::where('book_id', $book->id)->where('is_ready_for_pickup', false)->count();
    
        // Calculate available copies
        $availableCopies = $book->total_no_of_copies - ($borrowedCount + $reservedCount);
    
        if ($availableCopies > 0) {
            // Mark the reserved book as ready for pickup
            $reservedBook->is_ready_for_pickup = true;
            $reservedBook->save();
    
            // Reduce available copies
            $book->total_no_of_copies -= 1;
            $book->save();
    
            return redirect()->back()->with('success', 'Book marked as ready for pickup and available copies reduced');
        } else {
            return redirect()->back()->with('error', 'No available copies to reserve');
        }
    }
    
    public function markAsPickedUp($reservedBookId)
    {
        \Log::info("Mark as Picked Up called for ReservedBook ID: $reservedBookId");
        $reservedBook = ReservedBook::findOrFail($reservedBookId);
    
        if ($reservedBook->is_ready_for_pickup) {
            // Mark the book as picked up
            $reservedBook->is_picked_up = 1;
            $reservedBook->save();
            \Log::info("ReservedBook ID: $reservedBookId marked as picked up.");
            return redirect()->back()->with('success', 'Book marked as picked up successfully.');
        } else {
            \Log::error("ReservedBook ID: $reservedBookId is not ready for pickup.");
            return redirect()->back()->with('error', 'This book is not ready for pickup yet.');
        }
    }
    
    

    
}