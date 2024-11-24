<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description', 
        'expires_at',
    ];

    public $timestamps = false;

    protected $dates = ['expires_at'];
}
