<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Generated URLs must stay on HTTPS in production, including when a
        // reverse proxy terminates TLS before the request reaches PHP.
        if (app()->isProduction() && config('app.force_https')) {
            URL::forceScheme('https');
        }

        // Baseline policy for reader accounts: length plus letters and digits.
        PasswordRule::defaults(fn () => PasswordRule::min(8)->letters()->numbers());

        // Password reset emails must point at the Vue route, not a backend view.
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return url('/atur-ulang-sandi?token='.$token.'&email='.urlencode($user->getEmailForPasswordReset()));
        });
    }
}
