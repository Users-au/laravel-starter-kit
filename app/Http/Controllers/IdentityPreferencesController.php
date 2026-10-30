<?php

namespace App\Http\Controllers;

use App\Services\IdentityPreferences;
use Illuminate\Http\Request;
use Usersau\UsersauLaravelClient\Identity\PreferencesException;

class IdentityPreferencesController
{
    public function __invoke(Request $request, IdentityPreferences $identity)
    {
        try {
            if ($request->isMethod('get')) {
                $result = $identity->fetch($request->user());
            } else {
                $input = $request->validate([
                    'locale' => ['sometimes', 'required', 'string', 'max:35', 'regex:/^[a-zA-Z]{2,8}(?:-[a-zA-Z0-9]{1,8})*$/D'],
                    'timezone' => ['sometimes', 'required', 'timezone:all_with_bc'],
                    'theme' => ['sometimes', 'required', 'in:system,light,dark'],
                    'expected_version' => ['required', 'integer', 'min:0'],
                ]);
                $result = $identity->update($request->user(), $input, (int) $input['expected_version']);
            }

            return $request->expectsJson()
                ? response()->json($result)->header('Cache-Control', 'no-store')
                : back()->with('status', __('Preferences saved.'));
        } catch (PreferencesException $exception) {
            return $request->expectsJson()
                ? response()->json(['error' => $exception->getMessage(), 'preferences' => $exception->preferences], $exception->status)->header('Cache-Control', 'no-store')
                : back()->withErrors(['preferences' => __('Unable to save preferences. Sign in again or retry.')]);
        }
    }
}
