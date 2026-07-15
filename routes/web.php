<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\VoterController;
use App\Models\Election;
use App\Models\Position;
use App\Services\SecurityService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

// ===== Public Pages =====
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/features', [PageController::class, 'features'])->name('features');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.post');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// Health Check
Route::get('/health', [HealthController::class, 'check'])->name('health');

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

// ===== Email Verification Handler =====
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('home');
})->middleware(['auth', 'signed'])->name('verification.verify');

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

    // ===== Admin Dashboard =====
    Route::prefix('admin')
        ->middleware(['verified', 'active', 'role:admin'])
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

            // Elections
            Route::get('/elections', [AdminController::class, 'elections'])->name('elections.index');
            Route::get('/elections/create', [AdminController::class, 'createElection'])->name('elections.create');
            Route::post('/elections', [AdminController::class, 'storeElection'])->name('elections.store');
            Route::get('/elections/{election}', [AdminController::class, 'showElection'])->name('elections.show');
            Route::get('/elections/{election}/edit', [AdminController::class, 'editElection'])->name('elections.edit');
            Route::put('/elections/{election}', [AdminController::class, 'updateElection'])->name('elections.update');
            Route::delete('/elections/{election}', [AdminController::class, 'deleteElection'])->name('elections.destroy');
            Route::post('/elections/{election}/start', [AdminController::class, 'startElection'])->name('elections.start');
            Route::post('/elections/{election}/end', [AdminController::class, 'endElection'])->name('elections.end');
            Route::post('/elections/{election}/pause', [AdminController::class, 'pauseElection'])->name('elections.pause');
            Route::post('/elections/{election}/resume', [AdminController::class, 'resumeElection'])->name('elections.resume');
            Route::post('/elections/{election}/cancel', [AdminController::class, 'cancelElection'])->name('elections.cancel');
            Route::post('/elections/{election}/publish-results', [AdminController::class, 'publishResults'])->name('elections.publish-results');
            Route::get('/elections/{election}/results', [AdminController::class, 'results'])->name('elections.results');
            Route::get('/elections/{election}/results/csv', [AdminController::class, 'exportCsv'])->name('elections.results.csv');
            Route::get('/elections/{election}/results/pdf', [AdminController::class, 'exportPdf'])->name('elections.results.pdf');
            Route::get('/elections/{election}/results/live', [AdminController::class, 'liveResults'])->name('elections.results.live');

            // Positions
            Route::post('/elections/{election}/positions', [AdminController::class, 'storePosition'])->name('positions.store');
            Route::put('/positions/{position}', [AdminController::class, 'updatePosition'])->name('positions.update');
            Route::delete('/positions/{position}', [AdminController::class, 'deletePosition'])->name('positions.destroy');

            // Candidates
            Route::get('/candidates', [AdminController::class, 'candidates'])->name('candidates.index');
            Route::post('/candidates/{candidate}/approve', [AdminController::class, 'approveCandidate'])->name('candidates.approve');
            Route::post('/candidates/{candidate}/reject', [AdminController::class, 'rejectCandidate'])->name('candidates.reject');
            Route::post('/candidates/{candidate}/disqualify', [AdminController::class, 'disqualifyCandidate'])->name('candidates.disqualify');

            // Voters
            Route::get('/voters', [AdminController::class, 'voters'])->name('voters.index');
            Route::post('/elections/{election}/voters', [AdminController::class, 'addVoters'])->name('voters.add');
            Route::delete('/elections/{election}/voters/{user}', [AdminController::class, 'removeVoter'])->name('voters.remove');

            // Settings & Audit
            Route::put('/elections/{election}/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
            Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs.index');
            Route::get('/search', [AdminController::class, 'search'])->name('search');
            Route::get('/security-report', function () {
                $report = app(SecurityService::class)->getSecurityReport();

                return view('dashboard.admin.security-report', ['report' => $report]);
            })->name('security-report');
        });

    // ===== Voter Dashboard =====
    Route::prefix('voter')
        ->middleware(['verified', 'active', 'role:voter'])
        ->name('voter.')
        ->group(function () {
            Route::get('/dashboard', [VoterController::class, 'dashboard'])->name('dashboard');
            Route::get('/elections', [VoterController::class, 'elections'])->name('elections.index');
            Route::get('/elections/{election}', [VoterController::class, 'showElection'])->name('elections.show');
            Route::get('/elections/{election}/positions/{position}/ballot', [VoterController::class, 'ballot'])->name('ballot.show');
            Route::post('/elections/{election}/positions/{position}/vote', [VoterController::class, 'castVote'])->middleware('throttle:vote')->name('votes.cast');
            Route::get('/elections/{election}/positions/{position}/confirmation/{code}', [VoterController::class, 'voteConfirmation'])->name('votes.confirmation');
            Route::get('/verify-vote', [VoterController::class, 'verifyVote'])->name('verify-vote');
            Route::post('/verify-vote', [VoterController::class, 'verifyVote'])->name('verify-vote.result');
            Route::get('/vote-history', [VoterController::class, 'voteHistory'])->name('vote-history');
            Route::get('/elections/{election}/results', [VoterController::class, 'results'])->name('elections.results');
            Route::get('/profile', [VoterController::class, 'profile'])->name('profile');
            Route::put('/profile', [VoterController::class, 'updateProfile'])->name('profile.update');
        });

    // ===== Candidate Dashboard =====
    Route::prefix('candidate')
        ->middleware(['verified', 'active', 'role:candidate'])
        ->name('candidate.')
        ->group(function () {
            Route::get('/dashboard', [CandidateController::class, 'dashboard'])->name('dashboard');
            Route::get('/profile', [CandidateController::class, 'profile'])->name('profile');
            Route::put('/profile', [CandidateController::class, 'updateProfile'])->name('profile.update');
            Route::post('/photo', [CandidateController::class, 'uploadPhoto'])->name('photo.upload');
            Route::get('/election', [CandidateController::class, 'myElection'])->name('election');
            Route::get('/position', [CandidateController::class, 'myPosition'])->name('position');
            Route::get('/results', [CandidateController::class, 'results'])->name('results');
            Route::post('/withdraw', [CandidateController::class, 'withdraw'])->name('withdraw');
        });
});

// ===== Test routes for middleware tests =====
Route::middleware(['auth', 'role:admin'])->get('/test-role-admin', fn () => response('OK'));
Route::middleware(['auth', 'role:voter'])->get('/test-role-voter', fn () => response('OK'));
Route::middleware(['auth', 'role:admin,voter'])->get('/test-role-admin-voter', fn () => response('OK'));
Route::middleware(['auth', 'active'])->get('/test-active-user', fn () => response('OK'));
Route::middleware(['auth', 'election.active'])->get('/test-election-active/{election}', fn (Election $election) => response('OK'));
Route::middleware(['auth', 'has.not.voted'])->get('/test-has-not-voted/{position}', fn (Position $position) => response('OK'));
