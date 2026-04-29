<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Admin panel routes
            Route::middleware('web')
                 ->group(base_path('routes/admin.php'));

            // User panel routes — prefixed /user, named user.*
            Route::middleware('web')
                 ->prefix('user')
                 ->group(base_path('routes/user.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // ── Admin middleware ──────────────────────────────────────
            'admin.auth'       => \App\Http\Middleware\AdminAuthenticate::class,
            'admin.guest'      => \App\Http\Middleware\AdminGuest::class,
            'admin.active'     => \App\Http\Middleware\AdminActive::class,
            'admin.permission' => \App\Http\Middleware\AdminPermission::class,

            // ── User middleware ───────────────────────────────────────
            'user.guest'       => \App\Http\Middleware\UserGuest::class,
            'user.active'      => \App\Http\Middleware\UserActive::class,
            'user.role'        => \App\Http\Middleware\EnsureUserRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();