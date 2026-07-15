# 04 — Middleware & Policies

## Overview

Authorization layer for Cornelect — role middleware, model policies, and route guards.

## Execution Instructions

1. Create all middleware and policies
2. Register them in `bootstrap/app.php`
3. Apply to routes
4. Run tests
5. Run `vendor/bin/pint --dirty --format agent`
6. Update `docs/PROGRESS.md`

## Middleware

Create using: `php artisan make:middleware {Name} --no-interaction`

### RoleMiddleware

**File:** `app/Http/Middleware/RoleMiddleware.php`

```php
// Usage: RoleMiddleware:admin OR RoleMiddleware:admin,voter
// Checks if authenticated user has the required role(s)
// If not, abort 403 with appropriate message

public function handle(Request $request, Closure $next, string ...$roles): Response
{
    if (!in_array($request->user()?->role, $roles)) {
        abort(403, 'You do not have permission to access this page.');
    }
    return $next($request);
}
```

### EnsureEmailIsVerified (use Laravel built-in `verified`)

### EnsureActiveUser

**File:** `app/Http/Middleware/EnsureActiveUser.php`

```php
// Checks if user is_active = true
// Inactive users get logged out and redirected to login with error
```

### ElectionActive

**File:** `app/Http/Middleware/ElectionActive.php`

```php
// Checks if the election in the route is active (status = active)
// Used on voting routes to prevent voting on non-active elections
```

### HasNotVoted

**File:** `app/Http/Middleware/HasNotVoted.php`

```php
// Checks if the authenticated user has NOT already voted for the position
// Prevents double voting
```

### Registration in bootstrap/app.php

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'active' => \App\Http\Middleware\EnsureActiveUser::class,
        'election.active' => \App\Http\Middleware\ElectionActive::class,
        'has.not.voted' => \App\Http\Middleware\HasNotVoted::class,
    ]);
})
```

## Policies

Create using: `php artisan make:policy {Name}Policy --no-interaction`

### ElectionPolicy

**File:** `app/Policies/ElectionPolicy.php`

Methods:
- `viewAny(User $user): bool` — any authenticated user can view elections list
- `view(User $user, Election $election): bool` — voters see active/completed; admins see all; candidates see their election
- `create(User $user): bool` — only admin
- `update(User $user, Election $election): bool` — only admin who created it (or any admin)
- `delete(User $user, Election $election): bool` — only admin
- `start(User $user, Election $election): bool` — only admin
- `end(User $user, Election $election): bool` — only admin
- `publishResults(User $user, Election $election): bool` — only admin
- `vote(User $user, Election $election): bool` — voter who is in election_voters, election is active, hasn't voted

### CandidatePolicy

**File:** `app/Policies/CandidatePolicy.php`

Methods:
- `viewAny(User $user): bool` — any authenticated user
- `view(User $user, Candidate $candidate): bool` — all authenticated users
- `create(User $user): bool` — voters can become candidates (if election allows)
- `update(User $user, Candidate $candidate): bool` — candidate owner or admin
- `delete(User $user, Candidate $candidate): bool` — admin only
- `approve(User $user, Candidate $candidate): bool` — admin only
- `reject(User $user, Candidate $candidate): bool` — admin only
- `disqualify(User $user, Candidate $candidate): bool` — admin only

### PositionPolicy

**File:** `app/Policies/PositionPolicy.php`

Methods:
- `viewAny(User $user): bool` — any authenticated user
- `view(User $user, Position $position): bool` — any authenticated user
- `create(User $user, Election $election): bool` — admin only
- `update(User $user, Position $position): bool` — admin only
- `delete(User $user, Position $position): bool` — admin only

### VotePolicy

**File:** `app/Policies/VotePolicy.php`

Methods:
- `cast(User $user, Position $position): bool` — voter, in election_voters, election active, hasn't voted for this position
- `verify(User $user, string $verificationCode): bool` — any authenticated user with a vote record

### AuditLogPolicy

**File:** `app/Policies/AuditLogPolicy.php`

Methods:
- `viewAny(User $user): bool` — admin only
- `view(User $user, AuditLog $auditLog): bool` — admin only

## Route Application

```php
// Dashboard routes with middleware
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        // admin routes
    });

    Route::prefix('voter')->middleware('role:voter')->group(function () {
        // voter routes
    });

    Route::prefix('candidate')->middleware('role:candidate')->group(function () {
        // candidate routes
    });
});

// Voting routes with full middleware stack
Route::middleware(['auth', 'verified', 'active', 'role:voter'])
    ->group(function () {
        Route::post('/elections/{election}/positions/{position}/vote', [VoteController::class, 'cast'])
            ->middleware(['election.active', 'has.not.voted'])
            ->name('votes.cast');
    });
```

## Tests

### MiddlewareTest

- `it allows admin to access admin dashboard`
- `it prevents voter from accessing admin dashboard`
- `it prevents unauthenticated access to dashboards`
- `it prevents inactive users from accessing dashboards`
- `it prevents voting on non-active elections`
- `it prevents double voting`

### PolicyTest

- `it allows admin to create elections`
- `it prevents voter from creating elections`
- `it allows voter to vote in active election`
- `it prevents voter from voting twice`
- `it allows admin to approve candidates`
- `it prevents voter from approving candidates`
- `it allows candidate to update own profile`
- `it prevents candidate from updating others profiles`

## Do NOT proceed until:
- [ ] All 4 middleware created and registered
- [ ] All 5 policies created with all methods
- [ ] Middleware applied to routes
- [ ] All 14 Pest tests pass
- [ ] Pint formatting passes
