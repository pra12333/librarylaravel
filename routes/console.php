<?php
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Support\Facades\DB;
use App\Models\ReservedBook;
use App\Notifications\BookAvailableNotification;
use Illuminate\Console\Scheduling\Schedule;

app(Schedule::class)->call(function () {
   // Expire reservations that haven't been picked up within 24 hours
   ReservedBook::where('reservation_status', 'ready_for_pickup')
               ->where('expires_at', '<=', now())
               ->update(['reservation_status' => 'expired']);

   // Notify the next user in the queue
   $expiredReservations = ReservedBook::where('reservation_status', 'expired')->get();

   foreach ($expiredReservations as $expired) {
       $nextReservation = ReservedBook::where('book_id', $expired->book_id)
                                      ->where('reservation_status', 'pending')
                                      ->orderBy('reservation_order')
                                      ->first();

       if ($nextReservation) {
           // Update the next reservation to ready for pickup
           $nextReservation->update([
               'reservation_status' => 'ready_for_pickup',
               'is_ready_for_pickup' => true,
               'expires_at' => now()->addHours(24), // Set the expiration time for 24 hours from now
           ]);

           // Notify the user that their book is ready for pickup
           $nextReservation->user->notify(new BookAvailableNotification($nextReservation->book_id));
       }
   }
})->hourly();
