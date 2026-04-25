<?php

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Artisan;

Route::get('/setup-db', function () {
    Artisan::call('migrate', ['--force' => true]);
    Artisan::call('db:seed', [
        '--class' => 'RolePermissionSeeder'
    ]);

    return "Database setup completed";
});