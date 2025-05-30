<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome', [
        'canLogin' => Route::has('usersau.login'),
        'canRegister' => Route::has('usersau.register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::middleware([
    'auth:sanctum',
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Usersau OAuth routes are automatically registered by the users-au/laravel-client package
// Available routes:
// - usersau.login (GET /auth/usersau/redirect)
// - usersau.register (GET /auth/usersau/register)  
// - usersau.logout (GET /auth/usersau/logout)
// - usersau.account (GET /auth/usersau/account)
// - Callback route (GET /auth/usersau/callback) 