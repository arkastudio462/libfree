<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_me_returns_null(): void
    {
        $this->getJson('/api/saya')
            ->assertOk()
            ->assertJsonPath('data', null);
    }

    public function test_csrf_token_endpoint_returns_the_session_token(): void
    {
        $response = $this->getJson('/api/csrf-token')->assertOk();

        $this->assertNotEmpty($response->json('token'));
    }

    public function test_register_creates_an_account_and_starts_a_session(): void
    {
        $response = $this->postJson('/api/daftar', [
            'name' => 'Nadia Pembaca',
            'email' => 'nadia@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Nadia Pembaca')
            ->assertJsonPath('data.initials', 'NP')
            ->assertJsonPath('data.is_admin', false)
            ->assertJsonStructure(['data', 'csrf_token']);

        $this->assertNotEmpty($response->json('csrf_token'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'nadia@example.com']);

        $this->getJson('/api/saya')
            ->assertOk()
            ->assertJsonPath('data.email', 'nadia@example.com');
    }

    public function test_register_validates_unique_email_and_password_confirmation(): void
    {
        User::factory()->create(['email' => 'sudah@example.com']);

        $this->postJson('/api/daftar', [
            'name' => 'Pembaca',
            'email' => 'sudah@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'beda12345',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_login_accepts_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $this->postJson('/api/masuk', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ])->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonStructure(['data', 'csrf_token']);

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $this->postJson('/api/masuk', [
            'email' => 'login@example.com',
            'password' => 'salah-password',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');

        $this->assertGuest();
    }

    public function test_logout_ends_the_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/keluar')
            ->assertOk()
            ->assertJsonStructure(['message', 'csrf_token']);

        $this->assertGuest();
    }

    public function test_me_returns_the_authenticated_reader(): void
    {
        $user = User::factory()->create([
            'name' => 'Raka Arsitek',
            'email' => 'raka@example.com',
        ]);

        $this->actingAs($user)
            ->getJson('/api/saya')
            ->assertOk()
            ->assertJsonPath('data.name', 'Raka Arsitek')
            ->assertJsonPath('data.initials', 'RA');
    }

    public function test_register_normalizes_the_email_address(): void
    {
        $this->postJson('/api/daftar', [
            'name' => 'Nadia Pembaca',
            'email' => '  Nadia@Example.COM  ',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])
            ->assertCreated()
            ->assertJsonPath('data.email', 'nadia@example.com');

        $this->assertDatabaseHas('users', ['email' => 'nadia@example.com']);
    }

    public function test_login_normalizes_the_email_address(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $this->postJson('/api/masuk', [
            'email' => 'LOGIN@EXAMPLE.COM',
            'password' => 'password123',
        ])->assertOk()
            ->assertJsonPath('data.id', $user->id);

        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_requires_a_password_with_letters_and_numbers(): void
    {
        $this->postJson('/api/daftar', [
            'name' => 'Pembaca',
            'email' => 'lemah@example.com',
            'password' => 'hanyahuruf',
            'password_confirmation' => 'hanyahuruf',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');
    }

    public function test_login_is_rate_limited_after_repeated_failures(): void
    {
        User::factory()->create([
            'email' => 'throttle@example.com',
            'password' => 'password123',
        ]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->postJson('/api/masuk', [
                'email' => 'throttle@example.com',
                'password' => 'salah-password',
            ])->assertUnprocessable();
        }

        $this->postJson('/api/masuk', [
            'email' => 'throttle@example.com',
            'password' => 'password123',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email')
            ->assertJsonPath('errors.email.0', fn (string $message) => str_contains($message, 'Terlalu banyak'));

        $this->assertGuest();
    }

    public function test_password_reset_link_is_sent_for_a_registered_email(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'reset@example.com']);

        $this->postJson('/api/lupa-sandi', ['email' => 'RESET@EXAMPLE.COM'])
            ->assertOk()
            ->assertJsonStructure(['message']);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
            $url = $notification->toMail($user)->actionUrl;

            return str_contains($url, '/atur-ulang-sandi?token=')
                && str_contains($url, urlencode($user->email));
        });
    }

    public function test_password_reset_request_does_not_reveal_unknown_emails(): void
    {
        Notification::fake();

        $this->postJson('/api/lupa-sandi', ['email' => 'tidak-ada@example.com'])
            ->assertOk()
            ->assertJsonStructure(['message']);

        Notification::assertNothingSent();
    }

    public function test_reader_can_reset_their_password_with_a_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
            'password' => 'lama12345',
        ]);

        $token = Password::broker()->createToken($user);

        $this->postJson('/api/atur-ulang-sandi', [
            'token' => $token,
            'email' => 'RESET@EXAMPLE.COM',
            'password' => 'barusandi123',
            'password_confirmation' => 'barusandi123',
        ])->assertOk();

        $this->assertTrue(Hash::check('barusandi123', $user->refresh()->password));

        $this->postJson('/api/masuk', [
            'email' => 'reset@example.com',
            'password' => 'barusandi123',
        ])->assertOk();

        $this->assertAuthenticatedAs($user);
    }

    public function test_password_reset_rejects_an_invalid_token(): void
    {
        $user = User::factory()->create(['email' => 'reset@example.com']);

        $this->postJson('/api/atur-ulang-sandi', [
            'token' => 'token-palsu',
            'email' => $user->email,
            'password' => 'barusandi123',
            'password_confirmation' => 'barusandi123',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('token');
    }
}
