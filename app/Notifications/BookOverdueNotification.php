<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\BorrowedBook;

class BookOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $borrowedBook; // Declare the property to store the BorrowedBook instance

    /**
     * Create a new notification instance.
     *
     * @param  BorrowedBook  $borrowedBook
     * @return void
     */
    public function __construct(BorrowedBook $borrowedBook)
    {
        $this->borrowedBook = $borrowedBook; // Initialize the borrowedBook property
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Store the notification in the database
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $book = $this->borrowedBook->book; // Get the associated book
        $user = $this->borrowedBook->user; // Get the associated user

        return [
            'book_id' => $book->id,
            'message' => 'The book "' . $book->bookname . '" is overdue.',
            'user_id' => $user->id,
            'due_date' => $this->borrowedBook->borrowed_at->addDays(7), // Due date for reference
        ];
    }
}
