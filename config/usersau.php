<?php
return [
    'after_login_url' => '/dashboard',
    'after_logout_url' => '/',
    'after_register_url' => '/dashboard',
    'user_model' => \App\Models\User::class,
    'middleware' => ['web'],
    'profile_photo_column' => null,
]; 