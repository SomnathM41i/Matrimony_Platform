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
            Route::middleware('web')
                 ->group(base_path('routes/admin.php'));   // ← ADD
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin.auth'       => \App\Http\Middleware\AdminAuthenticate::class,
            'admin.guest'      => \App\Http\Middleware\AdminGuest::class,
            'admin.active'     => \App\Http\Middleware\AdminActive::class,
            'admin.permission' => \App\Http\Middleware\AdminPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
