<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Usersau\UsersauLaravelClient\Http\Controllers\AuthController;

/**
 * Keep the package's OAuth routes, login and registration entry points while
 * using Laravel's auth facade directly for the application's session lifecycle.
 * Resolving the facade as a container service conflicts with Sanctum's guard.
 */
class UsersauController extends AuthController
{
    public function callback(): RedirectResponse
    {
        try {
            // The stateful provider validates and consumes OAuth state before
            // exchanging the code. Do not use stateless() for browser sign-in.
            $identity = Socialite::driver('usersau')->user();
        } catch (InvalidStateException|GuzzleException $exception) {
            return redirect()->route('login')
                ->with('error', 'Unable to sign in. Please try again.');
        }

        if (! $identity->getId() || ! filter_var($identity->getEmail(), FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('login')
                ->with('error', 'Unable to sign in. Please try again.');
        }

        $attributes = [
            'name' => $identity->getName(),
            'email' => $identity->getEmail(),
            'usersau_access_token' => $identity->token,
            'usersau_refresh_token' => $identity->refreshToken,
        ];

        if ($column = config('usersau.profile_photo_column')) {
            $attributes[$column] = $identity->getAvatar();
        }

        $user = app(config('usersau.user_model'))->updateOrCreate(
            ['usersau_id' => $identity->getId()],
            $attributes
        );

        Auth::login($user);
        request()->session()->regenerate();

        $flow = request()->session()->pull('usersau_flow', 'login');

        return redirect()->to(config(
            $flow === 'register' ? 'usersau.after_register_url' : 'usersau.after_login_url'
        ));
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->away(rtrim(config('services.usersau.host'), '/').'/logout?'.http_build_query([
            'continue' => url(config('usersau.after_logout_url')),
        ]));
    }

    public function account(): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('usersau.login');
        }

        return redirect()->away(rtrim(config('services.usersau.host'), '/').'/account');
    }
}
