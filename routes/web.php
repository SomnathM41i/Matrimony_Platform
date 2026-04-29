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

Route::get('/', function () {
    return view('user.index');
})->name('home');

Route::get('/about', function () {
    return view('user.about');
})->name('about');

Route::get('/packages', function () {
    return view('user.packages');
})->name('packages');

Route::get('/contact', function () {
    return view('user.contact');
})->name('contact');

Route::get('/login', function () {
    return redirect()->route('user.login');
})->name('login');

Route::get('/register', function () {
    return redirect()->route('user.register');
})->name('register');