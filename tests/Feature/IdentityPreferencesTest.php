<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\IdentityPreferences;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Usersau\UsersauLaravelClient\Events\UserProfileSynchronized;
use Usersau\UsersauLaravelClient\Identity\UserProfile;

class IdentityPreferencesTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        config(['services.usersau.host' => 'https://identity.test']);
        $user = User::factory()->create(['usersau_id' => 'subject', 'usersau_access_token' => 'private-access']);
        event(new UserProfileSynchronized($user, new UserProfile(['sub' => 'subject', 'given_name' => 'Jane',
            UserProfile::PREFERENCES_CLAIM => $this->preferences()]), 'https://identity.test'));

        return $user->fresh();
    }

    private function preferences(int $version = 1): array
    {
        return ['sub' => 'subject', 'locale' => 'zh-CN', 'timezone' => 'Australia/Sydney', 'theme' => 'dark', 'version' => $version];
    }

    public function test_profile_event_and_dashboard_use_confirmed_account_preferences(): void
    {
        $user = $this->user();
        Http::fake(['*' => Http::response($this->preferences())]);
        $this->assertSame('Jane', $user->usersau_profile['given_name']);
        $this->actingAs($user)->get('/dashboard')->assertOk()->assertSee('账户偏好')->assertSee('Australia/Sydney')
            ->assertDontSee('private-access');
    }

    public function test_same_origin_writes_are_subject_bound_and_conflicts_keep_newest_values(): void
    {
        $user = $this->user();
        Http::fake(['*' => Http::sequence()->push(array_merge($this->preferences(2), ['theme' => 'light']))
            ->push(['preferences' => $this->preferences(3)], 409)]);
        $this->actingAs($user)->patchJson('/account/preferences', ['theme' => 'light', 'expected_version' => 1, 'user_id' => 999])
            ->assertOk()->assertJsonPath('theme', 'light');
        Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer private-access') && $request->data() === ['theme' => 'light', 'expected_version' => 1]);
        $this->patchJson('/account/preferences', ['theme' => 'light', 'expected_version' => 1])->assertStatus(409)->assertJsonPath('preferences.version', 3);
        $this->assertSame('dark', $user->fresh()->interface_preferences['theme']);
    }

    public function test_old_responses_cannot_replace_newer_cached_preferences(): void
    {
        $user = $this->user();
        $identity = app(IdentityPreferences::class);
        $identity->remember($user, $this->preferences(5));
        $identity->remember($user, array_merge($this->preferences(2), ['theme' => 'light']));
        $this->assertSame(5, $user->fresh()->interface_preferences['version']);
    }

    public function test_guests_validation_and_csrf_are_enforced(): void
    {
        $this->getJson('/account/preferences')->assertUnauthorized();
        $user = $this->user();
        $this->actingAs($user)->patchJson('/account/preferences', ['theme' => 'auto', 'timezone' => 'Mars/Base'])->assertUnprocessable();
        $this->app->instance('env', 'production');
        $this->patchJson('/account/preferences', ['theme' => 'light', 'expected_version' => 1])->assertStatus(419);
        Http::assertNothingSent();
    }

    public function test_outages_keep_confirmed_preferences_and_changed_issuers_never_receive_old_tokens(): void
    {
        $user = $this->user();
        Http::fake(['*' => Http::response([], 503)]);
        $this->actingAs($user)->get('/dashboard')->assertOk()->assertSee('账户偏好');
        config(['services.usersau.host' => 'https://another.test']);
        Http::fake(['*' => Http::response($this->preferences())]);
        $this->getJson('/account/preferences')->assertStatus(502);
        Http::assertNotSent(fn ($request) => str_starts_with($request->url(), 'https://another.test'));
    }
}
