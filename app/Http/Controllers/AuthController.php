<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidateRegistrationRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VoterRegistrationRequest;
use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Position;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $request->ensureIsNotRateLimited();

        if (! $request->authenticate()) {
            RateLimiter::hit($request->throttleKey(), 60);

            return back()->with('error', 'Invalid credentials. Please try again.')->onlyInput('identifier');
        }

        RateLimiter::clear($request->throttleKey());

        $request->session()->regenerate();

        $user = Auth::user();
        $user->update(['last_login_at' => now()]);

        AuditLog::log('login', "User {$user->name} logged in.");

        return match ($user->role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'voter' => redirect()->intended(route('voter.dashboard')),
            'candidate' => redirect()->intended(route('candidate.dashboard')),
            default => redirect()->intended(route('home')),
        };
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            AuditLog::log('logout', "User {$user->name} logged out.");
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function showRegisterVoter()
    {
        return view('auth.register');
    }

    public function registerVoter(VoterRegistrationRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'voter',
            'student_id' => $request->student_id,
            'phone' => $request->phone,
            'faculty' => $request->faculty,
            'department' => $request->department,
            'course' => $request->course,
            'year_of_study' => $request->year_of_study,
        ]);

        event(new Registered($user));

        Auth::login($user);

        AuditLog::log('register', "Voter {$user->name} registered.");

        return redirect()->route('voter.dashboard')->with('status', 'Welcome! Please verify your email address.');
    }

    public function showRegisterCandidate()
    {
        return view('auth.register-candidate');
    }

    public function registerCandidate(CandidateRegistrationRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'candidate',
            'student_id' => $request->student_id,
            'phone' => $request->phone,
            'faculty' => $request->faculty,
            'department' => $request->department,
            'course' => $request->course,
            'year_of_study' => $request->year_of_study,
        ]);

        Candidate::create([
            'user_id' => $user->id,
            'election_id' => Position::find($request->position_id)->election_id,
            'position_id' => $request->position_id,
            'manifesto_title' => $request->manifesto_title,
            'manifesto' => $request->manifesto,
            'slogan' => $request->slogan,
            'status' => 'pending',
        ]);

        event(new Registered($user));

        Auth::login($user);

        AuditLog::log('register', "Candidate {$user->name} registered for position ID {$request->position_id}.");

        return redirect()->route('candidate.dashboard')->with('status', 'Welcome! Your candidacy is pending approval.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(ForgotPasswordRequest $request)
    {
        Password::sendResetLink($request->only('email'));

        return back()->with('status', 'If your email exists in our system, a reset link has been sent.');
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Your password has been reset. Please sign in.');
        }

        return redirect()->back()->withErrors(['email' => trans($status)]);
    }

    public function showVerifyEmail(Request $request)
    {
        if ($request->user()?->hasVerifiedEmail()) {
            return redirect()->route(match ($request->user()->role) {
                'admin' => 'admin.dashboard',
                'candidate' => 'candidate.dashboard',
                default => 'voter.dashboard',
            });
        }

        return view('auth.verify-email');
    }

    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route(match ($request->user()->role) {
                'admin' => 'admin.dashboard',
                'candidate' => 'candidate.dashboard',
                default => 'voter.dashboard',
            });
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'A new verification link has been sent to your email.');
    }
}
