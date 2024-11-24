<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\support\Facades\View;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() {
        View::composer('*',function($view) {
            $cartCount = Cart::where('user_id',Auth::id())->count();
            $view->with('cartCount',$cartCount);
        });
    }
    
}
