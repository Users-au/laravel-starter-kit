<?php

use App\Http\Controllers\UsersauController;
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
    if (auth()->check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
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

// Keep the package's logout URL, but require a CSRF-protected POST. The bound
// application controller refuses the legacy GET handler without signing out.
Route::post('/auth/usersau/logout', [UsersauController::class, 'logout']);

// Usersau OAuth routes are automatically registered by the users-au/laravel-client package
// Available routes:
// - usersau.login (GET /auth/usersau/redirect)
// - usersau.register (GET /auth/usersau/register)
// - usersau.logout (URL for the POST form; GET returns 405)
// - usersau.account (GET /auth/usersau/account)
// - Callback route (GET /auth/usersau/callback)
