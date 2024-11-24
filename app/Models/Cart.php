<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable =['user_id','book_id','quantity'];

    // relationship to book
    public function book(){
        return $this->belongsTo(Book::class,'book_id');
    }

    // relationship to user
    public function user(){
        return $this->belongsTo(User::class);
    }
}
