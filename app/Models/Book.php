<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PharIo\Manifest\Author;
use App\Notifications\BookOverdueNotification;
use Illuminate\Support\Facades\Notification;

class Book extends Model
{
    use HasFactory;

    protected $fillable =[
        'bookname',
        'genreid',
        'bookpicture',
        'author',
        'release_date',
        'isbn',
        'total_no_of_copies',
        'featured',
    ];

    public function borrowedBooks() {
        return $this->hasMany(BorrowedBook::class);
    }

    public function genre(){
        return $this->belongsTo(Genre::class,'genreid');
    }

    public function reservedBooks()
    {
        return $this->hasMany(ReservedBook::class);
    }
     
    protected $casts = [
        'featured' => 'boolean',
        'release_date' => 'date',
        
    ];
    public function checkOverDue()
    {
        $borrowedBook = $this->borrowedBooks()->whereNull('returned_at')->first();
    
        if ($borrowedBook) {
            $dueDate = $borrowedBook->borrowed_at->addDays(7);
    
            // Debugging: Log the due date and current date
            \Log::info("BorrowedBook ID {$borrowedBook->id} has due date: {$dueDate}. Current date: " . now());
    
            if (now()->greaterThan($dueDate)) {
                // Mark the borrowed book as overdue
                $borrowedBook->update(['status' => 'overdue']);
                \Log::info("BorrowedBook ID {$borrowedBook->id} marked as overdue.");
    
                // Update the user's overdue status
                $user = $borrowedBook->user;
                if ($user) {
                    $user->update(['has_overdue_books' => true]);
                    \Log::info("User ID {$user->id} marked with has_overdue_books = true.");
                } else {
                    \Log::warning("No user found for BorrowedBook ID {$borrowedBook->id}");
                }
            } else {
                \Log::info("BorrowedBook ID {$borrowedBook->id} is not overdue yet.");
            }
        } else {
            \Log::info("No borrowed book found for Book ID {$this->id} with a null returned_at");
        }
    }
    
    public function wishlists() {
        return $this->hasMany(Wishlist::class);
    }
    

}
