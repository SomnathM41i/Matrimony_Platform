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
    return view('front-end.index');
})->name('home');

Route::get('/about', function () {
    return view('front-end.about');
})->name('about');

Route::get('/packages', function () {
    return view('front-end.packages');
})->name('packages');

Route::get('/contact', function () {
    return view('front-end.contact');
})->name('contact');

Route::get('/login', function () {
    return view('front-end.auth.login');
})->name('login');

Route::get('/register', function () {
    return view('front-end.auth.register');
})->name('register');