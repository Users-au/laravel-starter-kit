<?php

namespace Tests\Feature;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Events\RouteMatched;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.usersau' => [
            'client_id' => 'test-client',
            'client_secret' => 'test-secret',
            'redirect' => 'http://localhost/auth/usersau/callback',
            'host' => 'https://identity.example.test',
        ]]);
    }

    private function useProviderHttpClient(Client $client): void
    {
        // Resolve the real Socialite provider after the HTTP request is bound.
        $this->app['events']->listen(RouteMatched::class, function () use ($client): void {
            Socialite::driver('usersau')->setHttpClient($client);
        });
    }

    public function test_login_page_renders_and_guests_cannot_access_dashboard(): void
    {
        $this->get('/login')->assertOk()->assertSee('Welcome Back')
            ->assertSee(route('usersau.login'));
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->getJson('/api/user')->assertUnauthorized();
    }

    public function test_authorization_redirect_contains_state_and_registered_callback(): void
    {
        $response = $this->get('/auth/usersau/redirect');
        $response->assertRedirect();
        $location = $response->headers->get('Location');
        $this->assertStringStartsWith('https://identity.example.test/oauth/authorize?', $location);
        parse_str(parse_url($location, PHP_URL_QUERY), $query);
        $this->assertSame('http://localhost/auth/usersau/callback', $query['redirect_uri']);
        $this->assertSame('test-client', $query['client_id']);
        $this->assertSame('code', $query['response_type']);
        $this->assertNotEmpty($query['state']);
        $this->assertSame(session('state'), $query['state']);
        $this->assertStringNotContainsString('test-secret', $location);
    }

    public function test_callback_rejects_invalid_state_without_sending_network_requests(): void
    {
        $mock = new MockHandler;
        $this->useProviderHttpClient(new Client(['handler' => $mock]));

        $this->withSession(['state' => 'expected-state'])
            ->get('/auth/usersau/callback?state=wrong-state&code=test-code')
            ->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_callback_rejects_missing_state_without_sending_network_requests(): void
    {
        $this->useProviderHttpClient(new Client(['handler' => new MockHandler]));

        $this->get('/auth/usersau/callback?code=test-code')
            ->assertRedirect(route('login'))->assertSessionHas('error');

        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_valid_callback_exchanges_code_and_authenticates_the_local_user(): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token' => 'test-access-token',
                'refresh_token' => 'test-refresh-token',
                'token_type' => 'Bearer',
                'expires_in' => 3600,
            ])),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'id' => 'test-usersau-id',
                'name' => 'Example User',
                'email' => 'user@example.test',
            ])),
        ]));
        $handler->push(Middleware::history($history));
        $this->useProviderHttpClient(new Client(['handler' => $handler]));

        $this->withSession(['state' => 'expected-state', 'usersau_flow' => 'login']);
        $previousSessionId = session()->getId();
        $this->get('/auth/usersau/callback?state=expected-state&code=test-code')
            ->assertRedirect('/dashboard')->assertSessionMissing('state')
            ->assertSessionMissing('usersau_flow');
        $this->assertNotSame($previousSessionId, session()->getId());

        $user = User::where('usersau_id', 'test-usersau-id')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertCount(2, $history);
        $this->assertSame('https://identity.example.test/oauth/token', (string) $history[0]['request']->getUri());
        parse_str((string) $history[0]['request']->getBody(), $tokenFields);
        $this->assertSame('test-code', $tokenFields['code']);
        $this->assertSame('http://localhost/auth/usersau/callback', $tokenFields['redirect_uri']);
        $this->assertSame('Bearer test-access-token', $history[1]['request']->getHeaderLine('Authorization'));

        $this->get('/dashboard')->assertOk()->assertSee('Example User')
            ->assertDontSee('test-access-token')->assertDontSee('test-refresh-token');
        // Hydrate authentication from the session, as a new HTTP request does.
        $this->app['auth']->forgetGuards();
        $this->getJson('/api/user')->assertOk()->assertJsonPath('email', 'user@example.test')
            ->assertJsonMissingPath('usersau_access_token')->assertJsonMissingPath('usersau_refresh_token');
    }

    public function test_provider_failure_does_not_create_a_session_or_expose_credentials(): void
    {
        $this->useProviderHttpClient(new Client([
            'handler' => HandlerStack::create(new MockHandler([new Response(401, [], '{"error":"invalid_grant"}')])),
        ]));

        $this->withSession(['state' => 'expected-state'])
            ->get('/auth/usersau/callback?state=expected-state&code=expired-code')
            ->assertRedirect(route('login'))
            ->assertSessionHas('error', 'Unable to sign in. Please try again.');

        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_authenticated_account_redirects_to_identity_service(): void
    {
        $this->actingAs(User::factory()->create())->get('/auth/usersau/account')
            ->assertRedirect('https://identity.example.test/account');
    }

    public function test_logout_clears_local_authentication_and_redirects_to_identity_service(): void
    {
        $this->withSession(['private-data' => 'must-be-cleared', '_token' => 'old-csrf-token']);
        $previousSessionId = session()->getId();
        $this->actingAs(User::factory()->create())->get('/auth/usersau/logout')
            ->assertRedirect('https://identity.example.test/logout?continue=http%3A%2F%2Flocalhost')
            ->assertSessionMissing('private-data');

        $this->assertGuest();
        $this->assertNotSame($previousSessionId, session()->getId());
        $this->assertNotSame('old-csrf-token', session()->token());
    }
}
