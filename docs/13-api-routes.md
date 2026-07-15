# 13 — API Routes & Controllers

## Overview

Complete route definitions for all controllers, middleware groups, and route name conventions.

## Execution Instructions

1. Replace all closure routes in `routes/web.php` with controller routes
2. Ensure all routes have proper middleware
3. Verify route names match those used in views
4. Run `php artisan route:list` to verify
5. Run `vendor/bin/pint --dirty --format agent`
6. Update `docs/PROGRESS.md`

## Complete Route File

**File:** `routes/web.php`

```php
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// ===== Public Pages =====
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/features', [PageController::class, 'features'])->name('features');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.post');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// ===== Authentication =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterVoter'])->name('register');
    Route::post('/register', [AuthController::class, 'registerVoter'])->middleware('throttle:register')->name('register.post');

    Route::get('/register/candidate', [AuthController::class, 'showRegisterCandidate'])->name('register.candidate');
    Route::post('/register/candidate', [AuthController::class, 'registerCandidate'])->middleware('throttle:register')->name('register.candidate.post');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->middleware('throttle:password-reset')->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:password-reset')->name('password.update');
});

// ===== Authenticated =====
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/verify-email', [AuthController::class, 'showVerifyEmail'])->name('verification.notice');
    Route::post('/verify-email', [AuthController::class, 'resendVerification'])->name('verification.send');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});

// ===== Admin Dashboard =====
Route::prefix('admin')
    ->middleware(['auth', 'verified', 'active', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // Elections
        Route::get('/elections', [AdminController::class, 'elections'])->name('elections.index');
        Route::get('/elections/create', [AdminController::class, 'createElection'])->name('elections.create');
        Route::post('/elections', [AdminController::class, 'storeElection'])->name('elections.store');
        Route::get('/elections/{election}', [AdminController::class, 'showElection'])->name('elections.show');
        Route::get('/elections/{election}/edit', [AdminController::class, 'editElection'])->name('elections.edit');
        Route::put('/elections/{election}', [AdminController::class, 'updateElection'])->name('elections.update');
        Route::delete('/elections/{election}', [AdminController::class, 'destroyElection'])->name('elections.destroy');
        Route::post('/elections/{election}/start', [AdminController::class, 'startElection'])->name('elections.start');
        Route::post('/elections/{election}/end', [AdminController::class, 'endElection'])->name('elections.end');
        Route::post('/elections/{election}/publish-results', [AdminController::class, 'publishResults'])->name('elections.publish-results');

        // Candidates
        Route::get('/elections/{election}/candidates', [AdminController::class, 'manageCandidates'])->name('elections.candidates');
        Route::get('/candidates/{candidate}', [AdminController::class, 'showCandidate'])->name('candidates.show');
        Route::post('/candidates/{candidate}/approve', [AdminController::class, 'approveCandidate'])->name('candidates.approve');
        Route::post('/candidates/{candidate}/reject', [AdminController::class, 'rejectCandidate'])->name('candidates.reject');
        Route::post('/candidates/{candidate}/disqualify', [AdminController::class, 'disqualifyCandidate'])->name('candidates.disqualify');

        // Positions
        Route::get('/elections/{election}/positions', [AdminController::class, 'managePositions'])->name('elections.positions');
        Route::get('/elections/{election}/positions/create', [AdminController::class, 'createPosition'])->name('positions.create');
        Route::post('/elections/{election}/positions', [AdminController::class, 'storePosition'])->name('positions.store');
        Route::get('/positions/{position}/edit', [AdminController::class, 'editPosition'])->name('positions.edit');
        Route::put('/positions/{position}', [AdminController::class, 'updatePosition'])->name('positions.update');
        Route::delete('/positions/{position}', [AdminController::class, 'destroyPosition'])->name('positions.destroy');

        // Voters
        Route::get('/elections/{election}/voters', [AdminController::class, 'manageVoters'])->name('elections.voters');
        Route::post('/elections/{election}/voters', [AdminController::class, 'addVoters'])->name('elections.voters.add');
        Route::delete('/elections/{election}/voters/{user}', [AdminController::class, 'removeVoter'])->name('elections.voters.remove');
        Route::get('/voters', [AdminController::class, 'voters'])->name('voters.index');
        Route::get('/voters/{user}', [AdminController::class, 'showVoter'])->name('voters.show');

        // Audit & Security
        Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs.index');
        Route::get('/security-report', [AdminController::class, 'securityReport'])->name('security-report');

        // Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
        Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

        // Export
        Route::get('/elections/{election}/results/export-csv', [AdminController::class, 'exportResultsCsv'])->name('elections.results.export-csv');
        Route::get('/elections/{election}/audit/export', [AdminController::class, 'exportAuditLog'])->name('elections.audit.export');
    });

// ===== Voter Dashboard =====
Route::prefix('voter')
    ->middleware(['auth', 'verified', 'active', 'role:voter'])
    ->name('voter.')
    ->group(function () {
        Route::get('/', [VoterController::class, 'dashboard'])->name('dashboard');
        Route::get('/elections', [VoterController::class, 'elections'])->name('elections.index');
        Route::get('/elections/{election}', [VoterController::class, 'showElection'])->name('elections.show');
        Route::get('/elections/{election}/positions/{position}/ballot', [VoterController::class, 'showBallot'])->name('ballot.show');
        Route::post('/elections/{election}/positions/{position}/vote', [VoterController::class, 'castVote'])->middleware('throttle:vote')->name('votes.cast');
        Route::get('/elections/{election}/positions/{position}/confirmation', [VoterController::class, 'voteConfirmation'])->name('votes.confirmation');
        Route::get('/verify-vote', [VoterController::class, 'verifyVote'])->name('verify-vote');
        Route::post('/verify-vote', [VoterController::class, 'verifyVoteResult'])->name('verify-vote.result');
        Route::get('/vote-history', [VoterController::class, 'voteHistory'])->name('vote-history');
        Route::get('/elections/{election}/results', [VoterController::class, 'results'])->name('elections.results');
        Route::get('/profile', [VoterController::class, 'profile'])->name('profile');
        Route::put('/profile', [VoterController::class, 'updateProfile'])->name('profile.update');
    });

// ===== Candidate Dashboard =====
Route::prefix('candidate')
    ->middleware(['auth', 'verified', 'active', 'role:candidate'])
    ->name('candidate.')
    ->group(function () {
        Route::get('/', [CandidateController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [CandidateController::class, 'profile'])->name('profile');
        Route::put('/profile', [CandidateController::class, 'updateProfile'])->name('profile.update');
        Route::post('/photo', [CandidateController::class, 'uploadPhoto'])->name('photo.upload');
        Route::get('/election', [CandidateController::class, 'myElection'])->name('election');
        Route::get('/position', [CandidateController::class, 'myPosition'])->name('position');
        Route::get('/results', [CandidateController::class, 'results'])->name('results');
        Route::post('/withdraw', [CandidateController::class, 'withdraw'])->name('withdraw');
    });
```

## PageController

**File:** `app/Http/Controllers/PageController.php`

```php
class PageController extends Controller
{
    public function home() { return view('pages.home'); }
    public function about() { return view('pages.about'); }
    public function features() { return view('pages.features'); }
    public function contact() { return view('pages.contact'); }
    public function sendContact(ContactRequest $request) { /* send email, redirect back */ }
    public function privacy() { return view('pages.privacy'); }
    public function terms() { return view('pages.terms'); }
}
```

## NotificationController

**File:** `app/Http/Controllers/NotificationController.php`

```php
class NotificationController extends Controller
{
    public function index() // paginated notifications for auth user
    public function markAsRead($id) // mark single as read
    public function markAllAsRead() // mark all as read
    public function unreadCount() // JSON response with count (for dropdown polling)
}
```

## Route Verification

```bash
php artisan route:list --except-vendor
```

Expected: 50+ routes, all named, all with proper middleware.

## Tests

### RouteTest

- `it has all public page routes`
- `it has all auth routes`
- `it has all admin routes with middleware`
- `it has all voter routes with middleware`
- `it has all candidate routes with middleware`
- `it has notification routes`
- `guest routes redirect authenticated users`
- `protected routes redirect guests to login`
- `admin routes require admin role`
- `voter routes require voter role`
- `candidate routes require candidate role`

## Do NOT proceed until:
- [ ] `routes/web.php` fully replaced with controller routes
- [ ] PageController created
- [ ] NotificationController created
- [ ] `php artisan route:list` shows all routes correctly
- [ ] All existing Blade views updated to use new route names
- [ ] All 11 Pest tests pass
- [ ] Pint formatting passes
