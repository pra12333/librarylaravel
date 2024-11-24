<?php

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\TestRoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Middleware\Authenticate;
use App\Models\RecentActivity;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

       

        $middleware->group('web', [
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class, // Ensure CSRF is enabled
            TestRoleMiddleware::class, // Register the middleware directly
           
        ]);

       
        $middleware->group('api', [
            // API middleware
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configure exceptions
    })->create();

    $kernel->schedule(fn() => RecentActivity::where('expires_at','<',now())->delete())->hourly();