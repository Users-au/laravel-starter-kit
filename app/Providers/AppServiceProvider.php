<?php

namespace App\Providers;

use App\Http\Controllers\UsersauController;
use Illuminate\Support\ServiceProvider;
use Usersau\UsersauLaravelClient\Http\Controllers\AuthController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthController::class,
            UsersauController::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
