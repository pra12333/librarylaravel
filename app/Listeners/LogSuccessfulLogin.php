<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use App\Models\RecentActivity;
use Carbon\Carbon;

class LogSuccessfulLogin
{
    public function handle(Login $event) {
        $user = $event->user;

        // log the login activity as a recent activity

        RecentActivity::create([
            'user_id' => $user->id,
            'description' => 'User' . $user->name . '('. $user->role .') logged in .',
            'expires_at' => Carbon::now()->addHours(48),
        ]);
    }
}