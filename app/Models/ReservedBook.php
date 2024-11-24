<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservedBook extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'book_id',
        'reserved_at',
        'is_ready_for_pickup',
        'expires_at',
        'is_picked_up',
        'reservation_status',
    ];
    protected $dates = ['reserved_at'];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function book(){
        return $this->belongsTo(Book::class,'book_id');
    }

    // protected $casts = [
    //     'reserved_at' => 'datetime',
    //   ];

      protected $casts = [
        'expires_at' => 'datetime',
        'reserved_at' => 'datetime',
      ];
      
}
