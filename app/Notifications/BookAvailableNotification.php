<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookAvailableNotification extends Notification
{
    use Queueable;

    protected $bookId;

    /**
     * Create a new notification instance.
     */
    public function __construct($bookId)
    {
        $this->bookId = $bookId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via( $notifiable)
    {
        return ['database'];
    }

    // define the data stored in the database

    public function toDatabase($notifiable) {
        return[
            'book_id' => $this->bookId,
            'message'=>'The book you reserved is now available for pickup',
            'url' => url('/user/myreservedbooks') // url to the reserved books page
        ];
    }
}
