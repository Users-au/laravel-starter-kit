<?php

namespace App\Services;

use App\Models\User;
use Usersau\UsersauLaravelClient\Events\UserProfileSynchronized;
use Usersau\UsersauLaravelClient\Identity\IdentityClient;
use Usersau\UsersauLaravelClient\Identity\PreferencesException;
use Usersau\UsersauLaravelClient\Identity\UserProfile;

class IdentityPreferences
{
    public function __construct(private IdentityClient $client) {}

    public function import(UserProfileSynchronized $event): void
    {
        if (! $event->user instanceof User || $event->issuer !== $this->client->issuer()
            || $event->profile->subject() !== (string) $event->user->usersau_id) {
            return;
        }
        $event->user->getConnection()->transaction(function () use ($event): void {
            $user = $event->user->newQuery()->whereKey($event->user->id)->lockForUpdate()->firstOrFail();
            abort_if($user->usersau_issuer && $user->usersau_issuer !== $event->issuer, 502);
            $profile = $event->profile->toArray();
            unset($profile[UserProfile::PREFERENCES_CLAIM]);
            $user->forceFill(['usersau_issuer' => $event->issuer, 'usersau_profile' => $profile])->save();
            if ($preferences = $event->profile->preferences()) {
                $this->remember($user, $preferences);
            }
        });
        $event->user->refresh();
    }

    public function remember(User $user, array $preferences): array
    {
        abort_unless($preferences['sub'] === (string) $user->usersau_id, 502);

        return $user->getConnection()->transaction(function () use ($user, $preferences): array {
            $current = $user->newQuery()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            abort_if($current->usersau_issuer && $current->usersau_issuer !== $this->client->issuer(), 502);
            $saved = $current->interface_preferences;
            if (is_array($saved) && $saved['version'] > $preferences['version']) {
                return $saved;
            }
            $current->forceFill(['interface_preferences' => $preferences, 'usersau_issuer' => $this->client->issuer()])->save();
            $user->setRawAttributes($current->getAttributes(), true);

            return $preferences;
        });
    }

    public function cached(User $user): ?array
    {
        return $user->usersau_issuer === $this->client->issuer() ? $user->interface_preferences : null;
    }

    public function fetch(User $user): array
    {
        $this->assertIssuer($user);

        return $this->remember($user, $this->client->fetch($user));
    }

    public function update(User $user, array $changes, int $version): array
    {
        $this->assertIssuer($user);
        try {
            return $this->remember($user, $this->client->update($user, $changes, $version));
        } catch (PreferencesException $exception) {
            if ($exception->preferences) {
                $this->remember($user, $exception->preferences);
            }
            throw $exception;
        }
    }

    private function assertIssuer(User $user): void
    {
        if ($user->usersau_issuer && $user->usersau_issuer !== $this->client->issuer()) {
            throw new PreferencesException(502);
        }
    }
}
