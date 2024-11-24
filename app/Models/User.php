<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Define constants for roles

    const ROLE_ADMIN ='admin';
    const ROLE_SUPERADMIN ='superadmin';
    const ROLE_USER ='user';
    

    protected $fillable = [
        'name',
        'email',
        'profile_picture',
        'password',
        'remember_token',
        'role',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' =>'datetime'
    ];

    public function isAdmin() {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSuperAdmin() {
        return $this->role === self::ROLE_SUPERADMIN;
    }

   public function isUser() {
    return $this->role === self::ROLE_USER;
   }
    public function hasOverdueBooks(){
        return $this->borrowedBooks()
                    ->whereNull('returned_at')
                    ->whereDate('borrowed_at','<=',now()->subDays(7)) // check if borrowed date +7 days is in the past
                    ->exists();
    }

    public function wishlists(){
        return $this->hasMany(Wishlist::class);
    }
}

