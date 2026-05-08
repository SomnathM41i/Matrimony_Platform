<?php

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Artisan;

// Route::get('/setup-db', function () {

//     // 🔁 Fresh migration (drops all tables and recreates)
//     Artisan::call('migrate:fresh', ['--force' => true]);

//     // 🌱 Run multiple seeders
//     Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder', '--force' => true]);
//     // Artisan::call('db:seed', ['--class' => 'LookupSeeder', '--force' => true]);

//     // 🔗 Storage link
//     Artisan::call('storage:link');

//     return response()->json([
//         'status' => true,
//         'message' => 'Database refreshed, seeded & storage linked successfully ✅'
//     ]);
// });

Route::get('/migrate', function () {

    // Run migration
    Artisan::call('migrate', ['--force' => true]);

    return response()->json([
        'status' => true,
        'message' => 'Database migrated successfully ✅'
    ]);
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