<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\IdentityPreferences;
use Closure;
use Illuminate\Http\Request;
use Usersau\UsersauLaravelClient\Identity\PreferencesException;

class ApplyIdentityPreferences
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $preferences = null;
        if ($user instanceof User && $user->usersau_id) {
            try {
                $identity = app(IdentityPreferences::class);
                $preferences = $identity->cached($user);
                if ($request->isMethod('get') && ! $request->expectsJson()) {
                    $preferences = $identity->fetch($user);
                }
            } catch (PreferencesException $exception) {
                // Keep the already issuer-checked confirmed copy during outages.
            }
        }
        $language = strtolower(explode('-', $preferences['locale'] ?? 'en')[0]);
        app()->setLocale(in_array($language, ['en', 'zh', 'de', 'fr', 'es', 'ja'], true) ? $language : 'en');
        view()->share('interfacePreferences', $preferences);

        return $next($request);
    }
}
