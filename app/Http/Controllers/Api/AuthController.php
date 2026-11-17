<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Return a fresh CSRF token for the SPA after a session change.
     */
    public function csrfToken(Request $request): JsonResponse
    {
        return response()->json(['token' => $request->session()->token()]);
    }

    /**
     * Register a new reader and start a session.
     */
    public function register(Request $request): JsonResponse
    {
        $request->merge(['email' => $this->normalizeEmail($request->input('email'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', PasswordRule::defaults()],
        ]);

        $user = User::query()->create($data);

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return response()->json([
            'data' => $this->userResource($user),
            'csrf_token' => $request->session()->token(),
        ], 201);
    }

    /**
     * Start a session for an existing account.
     */
    public function login(Request $request): JsonResponse
    {
        $request->merge(['email' => $this->normalizeEmail($request->input('email'))]);

        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        $throttleKey = Str::transliterate($credentials['email'].'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => 'Terlalu banyak percobaan masuk. Coba lagi dalam '.$seconds.' detik.',
            ]);
        }

        if (! Auth::guard('web')->attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']],
            (bool) ($credentials['remember'] ?? false),
        )) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi tidak cocok.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return response()->json([
            'data' => $this->userResource($request->user()),
            'csrf_token' => $request->session()->token(),
        ]);
    }

    /**
     * Send a password reset link when the email belongs to an account.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->merge(['email' => $this->normalizeEmail($request->input('email'))]);

        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $status = Password::sendResetLink($data);

        if ($status === Password::RESET_THROTTLED) {
            throw ValidationException::withMessages([
                'email' => 'Tautan atur ulang baru saja dikirim. Tunggu satu menit sebelum mencoba lagi.',
            ]);
        }

        // Jangan bocorkan apakah email terdaftar atau tidak.
        return response()->json([
            'message' => 'Jika email tersebut terdaftar, tautan atur ulang kata sandi sudah dikirim.',
        ]);
    }

    /**
     * Consume a password reset token and store the new password.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->merge(['email' => $this->normalizeEmail($request->input('email'))]);

        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset($data, function (User $user, string $password) {
            $user->forceFill([
                'password' => $password,
                'remember_token' => Str::random(60),
            ])->save();
        });

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'token' => 'Tautan atur ulang tidak valid atau sudah kedaluwarsa. Minta tautan baru.',
            ]);
        }

        return response()->json([
            'message' => 'Kata sandi berhasil diperbarui. Silakan masuk dengan kata sandi baru.',
        ]);
    }

    /**
     * End the current session.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Berhasil keluar.',
            'csrf_token' => $request->session()->token(),
        ]);
    }

    /**
     * Return the authenticated user (or null for guests).
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(['data' => $user ? $this->userResource($user) : null]);
    }

    /**
     * Shape the public user payload used by the SPA.
     */
    private function userResource(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => (bool) $user->is_admin,
            'initials' => $this->initials($user->name),
        ];
    }

    private function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $initials = '';

        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }

        return $initials !== '' ? $initials : 'LF';
    }

    private function normalizeEmail(mixed $email): string
    {
        return Str::lower(trim((string) $email));
    }
}
