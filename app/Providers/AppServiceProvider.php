<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('login', fn (Request $r) => Limit::perMinute(5)->by($r->input('identifier').$r->ip()));

        RateLimiter::for('register', fn (Request $r) => Limit::perMinute(3)->by($r->ip()));

        RateLimiter::for('password-reset', fn (Request $r) => Limit::perMinute(3)->by($r->input('email').$r->ip()));

        RateLimiter::for('vote', fn (Request $r) => Limit::perMinute(10)->by($r->user()?->id ?? $r->ip()));

        RateLimiter::for('api', fn (Request $r) => Limit::perMinute(60)->by($r->user()?->id ?? $r->ip()));
    }
}
