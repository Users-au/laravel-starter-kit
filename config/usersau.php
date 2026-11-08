<?php

use App\Models\User;
use Usersau\UsersauLaravelClient\OAuth\ConfiguredAuthContextProvider;

return [
    'par_enabled' => true,
    'context_provider' => ConfiguredAuthContextProvider::class,
    'context' => [
        'context_id' => env('USERSAU_CONTEXT_ID'),
        'name' => env('APP_NAME', 'My application'),
        'logo_url' => env('USERSAU_LOGO_URL'),
        'logo_dark_url' => env('USERSAU_LOGO_DARK_URL'),
        'favicon_url' => env('USERSAU_FAVICON_URL'),
    ],
    'after_login_url' => '/dashboard',
    'after_logout_url' => '/',
    'after_register_url' => '/dashboard',
    'user_model' => User::class,
    'middleware' => ['web'],
    'profile_photo_column' => null,
];
