<?php

use App\Http\Middleware\ElectionActive;
use App\Http\Middleware\EnsureActiveUser;
use App\Http\Middleware\HasNotVoted;
use App\Http\Middleware\PreventFraudMiddleware;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\UpdateLastLogin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->preventRequestsDuringMaintenance(except: [
            'health',
            'health/*',
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'active' => EnsureActiveUser::class,
            'election.active' => ElectionActive::class,
            'has.not.voted' => HasNotVoted::class,
            'last.login' => UpdateLastLogin::class,
            'prevent.fraud' => PreventFraudMiddleware::class,
            'guest' => RedirectIfAuthenticated::class,
        ]);

        $middleware->appendToGroup('web', [
            SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
