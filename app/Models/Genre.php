<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $table = 'genres';

    public static function boot()
    {
        parent::boot(); // Ensure that the parent's boot method is called

        // Normalize the genre name before saving
        static::saving(function ($genre) {
            $genre->name = strtolower($genre->name);
        });
    }

    // Relationship to the Book model
    public function books()
    {
        return $this->hasMany(Book::class, 'genreid');
    }

    protected $fillable =[
        'name',
    ];
}

