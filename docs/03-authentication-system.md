# 03 — Authentication System

## Overview

Complete authentication system for Cornelect with multi-role support (admin, voter, candidate), email verification, password reset, 2FA support, and role-based registration.

## Execution Instructions

1. Create all controllers, form requests, and notifications listed
2. Replace existing closure routes with controller routes
3. Implement all views (Blade templates already exist — wire them up)
4. Run tests after each component
5. Run `vendor/bin/pint --dirty --format agent`
6. Update `docs/PROGRESS.md`

## Controllers

Create using: `php artisan make:controller {Name} --no-interaction`

### AuthController

**File:** `app/Http/Controllers/AuthController.php`

Methods:
- `showLogin()` — returns `auth.login` view
- `login(LoginRequest $request)` — validates credentials, authenticates, redirects by role
- `logout(Request $request)` — logs out, redirects to home
- `showRegisterVoter()` — returns `auth.register` view
- `registerVoter(VoterRegistrationRequest $request)` — creates voter, sends verification email, logs in, redirects
- `showRegisterCandidate()` — returns `auth.register-candidate` view
- `registerCandidate(CandidateRegistrationRequest $request)` — creates candidate user + candidate record, redirects
- `showForgotPassword()` — returns `auth.forgot-password` view
- `sendResetLink(ForgotPasswordRequest $request)` — sends reset email
- `showResetPassword($token)` — returns `auth.reset-password` view with token
- `resetPassword(ResetPasswordRequest $request)` — resets password, redirects to login
- `showVerifyEmail()` — returns `auth.verify-email` view
- `resendVerification(Request $request)` — resends verification email

**Login logic:**
```php
// After authentication, redirect by role:
// admin -> route('admin.dashboard')
// voter -> route('voter.dashboard')
// candidate -> route('candidate.dashboard')
```

**Registration logic:**
- Voter: create User with role=voter, set student_id, faculty, department, course, year_of_study
- Candidate: create User with role=candidate, create Candidate record linked to a position (if election is open for registration), set manifesto fields
- Both: fire `Registered` event, send email verification notification

## Form Requests

Create using: `php artisan make:request {Name} --no-interaction`

### LoginRequest

```php
rules: [
    'email' => ['required', 'email'],
    'password' => ['required', 'string'],
    'remember' => ['boolean'],
]
// authenticate() method: attempt auth with remember
```

### VoterRegistrationRequest

```php
rules: [
    'name' => ['required', 'string', 'max:255'],
    'student_id' => ['required', 'string', 'unique:users,student_id'],
    'email' => ['required', 'email', 'unique:users,email'],
    'phone' => ['required', 'string', 'max:20'],
    'faculty' => ['required', 'string'],
    'department' => ['required', 'string'],
    'course' => ['required', 'string'],
    'year_of_study' => ['required', 'integer', 'min:1', 'max:6'],
    'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
    'terms' => ['required', 'accepted'],
]
```

### CandidateRegistrationRequest

```php
rules: [
    'name' => ['required', 'string', 'max:255'],
    'student_id' => ['required', 'string', 'unique:users,student_id'],
    'email' => ['required', 'email', 'unique:users,email'],
    'phone' => ['required', 'string', 'max:20'],
    'faculty' => ['required', 'string'],
    'department' => ['required', 'string'],
    'course' => ['required', 'string'],
    'year_of_study' => ['required', 'integer', 'min:1', 'max:6'],
    'position_id' => ['required', 'exists:positions,id'],
    'manifesto_title' => ['required', 'string', 'max:255'],
    'manifesto' => ['required', 'string', 'min:100'],
    'slogan' => ['nullable', 'string', 'max:255'],
    'photo' => ['nullable', 'image', 'max:2048'],
    'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
    'terms' => ['required', 'accepted'],
]
```

### ForgotPasswordRequest

```php
rules: [
    'email' => ['required', 'email'],
]
```

### ResetPasswordRequest

```php
rules: [
    'token' => ['required', 'string'],
    'email' => ['required', 'email'],
    'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
]
```

## Notifications

Create using: `php artisan make:notification {Name} --no-interaction`

### VerifyEmailNotification

- Sent to user after registration
- Contains signed URL for email verification
- Uses `verify-email` route

### PasswordResetNotification

- Sent when user requests password reset
- Contains reset link with token
- Uses `reset-password` route

### RegistrationApprovedNotification (for candidates)

- Sent when admin approves candidate registration
- Informs candidate they can now campaign

### RegistrationRejectedNotification (for candidates)

- Sent when admin rejects candidate
- Includes rejection reason

## Email Verification

- Implement `MustVerifyEmail` interface on User model
- Create verification route handler
- After verification, redirect to appropriate dashboard
- Unverified users cannot access dashboards (middleware)

## Routes (replace existing closures)

```php
// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterVoter'])->name('register');
Route::post('/register', [AuthController::class, 'registerVoter'])->name('register.post');

Route::get('/register/candidate', [AuthController::class, 'showRegisterCandidate'])->name('register.candidate');
Route::post('/register/candidate', [AuthController::class, 'registerCandidate'])->name('register.candidate.post');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/verify-email', [AuthController::class, 'showVerifyEmail'])->name('verification.notice');
Route::post('/verify-email', [AuthController::class, 'resendVerification'])->name('verification.send');
```

## Middleware

### EnsureEmailIsVerified

Use Laravel's built-in `verified` middleware on dashboard routes.

### UpdateLastLogin

**File:** `app/Http/Middleware/UpdateLastLogin.php`

- Updates `last_login_at` on the authenticated user after successful login
- Register in `bootstrap/app.php` as middleware alias

## Views

The Blade views already exist in `resources/views/auth/`. They need to be wired up:
- Ensure forms post to the correct named routes
- Add `@csrf` to all forms
- Add validation error display (`@error('field')`)
- Add flash message display for status messages
- Password strength meter should work with JS (already in `app.js`)

## Tests

Create using: `php artisan make:test {Name} --pest --no-interaction`

### AuthFeatureTest

Tests to write:
- `it can show the login page`
- `it can login with valid credentials`
- `it cannot login with invalid credentials`
- `it redirects admin to admin dashboard after login`
- `it redirects voter to voter dashboard after login`
- `it redirects candidate to candidate dashboard after login`
- `it can logout`
- `it can show voter registration page`
- `it can register a new voter`
- `it validates voter registration fields`
- `it can show candidate registration page`
- `it can register a new candidate`
- `it validates candidate registration fields`
- `it can request password reset link`
- `it can reset password with valid token`
- `it cannot reset password with invalid token`
- `it can show email verification notice`
- `it can resend verification email`
- `it prevents unverified users from accessing dashboard`
- `it updates last login timestamp on login`

## Do NOT proceed until:
- [ ] AuthController with all methods implemented
- [ ] All 5 Form Requests created with validation rules
- [ ] All 4 Notifications created
- [ ] Routes updated to use controllers
- [ ] Existing Blade views wired up with CSRF, errors, flash messages
- [ ] UpdateLastLogin middleware created and registered
- [ ] All 20 Pest tests pass
- [ ] Pint formatting passes
