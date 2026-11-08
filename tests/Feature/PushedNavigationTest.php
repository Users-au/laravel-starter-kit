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
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class PushedNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.url' => 'https://app.example', 'app.name' => 'My application', 'usersau.context.name' => 'My application',
            'services.usersau' => ['host' => 'https://identity.example', 'client_id' => 'client', 'client_secret' => 'secret', 'redirect' => 'https://app.example/auth/usersau/callback']]);
        Http::preventStrayRequests();
        Http::fake(['https://identity.example/*' => Http::response(['request_uri' => 'urn:ietf:params:oauth:request_uri:'.str_repeat('x', 64), 'expires_in' => 300], 201)]);
    }

    public function test_default_login_pushes_brand_and_pkce_then_completes_the_verified_callback(): void
    {
        $this->assertTrue(config('usersau.par_enabled'));
        $this->get('/auth/usersau/redirect')->assertRedirectContains('https://identity.example/oauth/authorize?');
        $state = session('state');
        $verifier = session('code_verifier');
        Http::assertSent(fn ($request) => $request['state'] === $state
            && $request['code_challenge'] === rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=')
            && json_decode($request['context'], true)['links']['home_url'] === 'https://app.example/');
        Socialite::forgetDrivers();
        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['access_token' => 'fixture-access', 'refresh_token' => 'fixture-refresh', 'token_type' => 'Bearer'])),
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['id' => 'subject-42', 'name' => 'Example User', 'email' => 'user@example.test'])),
        ]));
        $handler->push(Middleware::history($history));
        $client = new Client(['handler' => $handler]);
        $this->app['events']->listen(RouteMatched::class, function () use ($client): void {
            Socialite::driver('usersau')->setHttpClient($client);
        });
        $this->get('/auth/usersau/callback?'.http_build_query(['state' => $state, 'code' => 'fixture-code']))->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        parse_str((string) $history[0]['request']->getBody(), $fields);
        $this->assertSame($verifier, $fields['code_verifier']);
        $this->assertSame('https://app.example/auth/usersau/callback', $fields['redirect_uri']);
        $this->assertDatabaseHas('users', ['usersau_id' => 'subject-42']);
    }

    public function test_account_and_csrf_protected_logout_create_fresh_application_contexts(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get('/auth/usersau/account')->assertRedirectContains('https://identity.example/account?');
        $this->get('/auth/usersau/logout')->assertStatus(405);
        $this->assertAuthenticated();
        $this->post('/auth/usersau/logout')->assertRedirectContains('https://identity.example/logout?');
        $this->assertGuest();
        foreach (['account', 'logout'] as $purpose) {
            Http::assertSent(fn ($request) => ($request['purpose'] ?? null) === $purpose
                && $request->url() === 'https://identity.example/oauth/context'
                && json_decode($request['context'], true)['links']['logout_url'] === 'https://app.example/');
        }
    }
}
