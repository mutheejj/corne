# 12 — Security & Audit

## Overview

Comprehensive security layer — encryption, audit logging, rate limiting, vulnerability prevention, and security reporting.

## Execution Instructions

1. Create security middleware and services
2. Implement audit logging on all actions
3. Configure rate limiting
4. Run security tests
5. Run `vendor/bin/pint --dirty --format agent`
6. Update `docs/PROGRESS.md`

## AuditService

**File:** `app/Services/AuditService.php`

```php
class AuditService
{
    public function log(string $action, string $description, array $data = []): AuditLog
    public function logModelChange(Model $model, string $action, array $oldValues, array $newValues): AuditLog
    public function getAuditTrail(string $modelType, int $modelId): Collection
    public function getRecentActivity(int $days = 30): Collection
    public function getUserActivity(User $user): Collection
    public function exportAuditLog(Election $election): string
    public function verifyIntegrity(Election $election): array
}
```

### Actions to Audit

| Action | Description | Trigger |
|--------|-------------|---------|
| `user_login` | User logged in | After successful login |
| `user_logout` | User logged out | After logout |
| `user_registered` | New user registered | After registration |
| `election_created` | Election created | Admin creates election |
| `election_updated` | Election updated | Admin updates election |
| `election_started` | Election started | Admin/auto-start |
| `election_ended` | Election ended | Admin/auto-end |
| `election_published` | Results published | Admin publishes |
| `election_cancelled` | Election cancelled | Admin cancels |
| `position_created` | Position created | Admin creates position |
| `position_updated` | Position updated | Admin updates position |
| `position_deleted` | Position deleted | Admin deletes position |
| `candidate_approved` | Candidate approved | Admin approves |
| `candidate_rejected` | Candidate rejected | Admin rejects |
| `candidate_disqualified` | Candidate disqualified | Admin disqualifies |
| `candidate_withdrawn` | Candidate withdrawn | Candidate withdraws |
| `vote_cast` | Vote cast | Voter casts vote |
| `voter_added` | Voter added to election | Admin adds voters |
| `voter_removed` | Voter removed from election | Admin removes voter |
| `settings_updated` | System settings updated | Admin updates settings |
| `user_deactivated` | User deactivated | Admin deactivates user |
| `user_activated` | User activated | Admin activates user |
| `password_reset` | Password reset | User resets password |
| `email_verified` | Email verified | User verifies email |

## SecurityService

**File:** `app/Services/SecurityService.php`

```php
class SecurityService
{
    public function encryptVoteChoice(int $candidateId, string $verificationCode): string
    public function decryptVoteChoice(string $encryptedChoice): int
    public function generateReceiptHash(...$args): string
    public function verifyReceiptHash(...$args): bool
    public function generateVerificationCode(): string
    public function checkVoteIntegrity(Election $election): array
    public function getSecurityReport(): array
}
```

### Encryption Details

- **Algorithm:** AES-256-CBC
- **Key:** Derived from `APP_KEY` using `hash('sha256', config('app.key'))`
- **IV:** Random 16-byte IV per encryption, prepended to ciphertext
- **Storage:** Base64-encoded `IV + ciphertext` string

```php
public function encryptVoteChoice(int $candidateId, string $verificationCode): string
{
    $key = hash('sha256', config('app.key'));
    $iv = random_bytes(16);
    $data = json_encode(['candidate_id' => $candidateId, 'code' => $verificationCode]);
    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}
```

## Rate Limiting

Configure in `bootstrap/app.php` or `app/Providers/AppServiceProvider.php`:

```php
// Authentication rate limiting
RateLimiter::for('login', fn (Request $r) => Limit::perMinute(5)->by($r->input('email') . $r->ip()));
RateLimiter::for('register', fn (Request $r) => Limit::perMinute(3)->by($r->ip()));
RateLimiter::for('password-reset', fn (Request $r) => Limit::perMinute(3)->by($r->input('email') . $r->ip()));

// Voting rate limiting
RateLimiter::for('vote', fn (Request $r) => Limit::perMinute(10)->by($r->user()?->id ?? $r->ip()));

// API rate limiting
RateLimiter::for('api', fn (Request $r) => Limit::perMinute(60)->by($r->user()?->id ?? $r->ip()));
```

Apply to routes:
```php
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.post');
Route::post('/register', [AuthController::class, 'registerVoter'])->middleware('throttle:register')->name('register.post');
Route::post('/elections/{election}/positions/{position}/vote', [VoteController::class, 'castVote'])->middleware('throttle:vote')->name('votes.cast');
```

## Security Middleware

### PreventFraudMiddleware

**File:** `app/Http/Middleware/PreventFraudMiddleware.php`

- Checks for suspicious patterns (rapid requests, same IP multiple accounts)
- Logs suspicious activity
- Blocks requests from flagged IPs

### SanitizeInputMiddleware

- Use Laravel's built-in `TrimStrings` and `ConvertEmptyStringsToNull`
- Add XSS prevention: all output in Blade uses `{{ }}` (auto-escaped)
- SQL injection prevention: use Eloquent (parameterized queries)

## Security Headers

Add to `bootstrap/app.php` middleware:

```php
$middleware->appendToGroup('web', [
    \App\Http\Middleware\SecurityHeaders::class,
]);
```

**File:** `app/Http/Middleware/SecurityHeaders.php`

```php
headers:
    X-Content-Type-Options: nosniff
    X-Frame-Options: DENY
    X-XSS-Protection: 1; mode=block
    Referrer-Policy: strict-origin-when-cross-origin
    Permissions-Policy: geolocation=(), microphone=(), camera=()
    Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' cdn.tailwindcss.com cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' cdn.tailwindcss.com fonts.googleapis.com; font-src 'self' fonts.gstatic.com; img-src 'self' data: blob:; connect-src 'self'
```

## CSRF Protection

- Laravel's `VerifyCsrfToken` middleware is active by default on web routes
- All forms must include `@csrf`
- API routes (if any) use Sanctum tokens

## Password Security

- Minimum 8 characters with mixed case, numbers, symbols (enforced in Form Requests)
- Passwords hashed using Laravel's `Hash` facade (bcrypt/argon2)
- Password confirmation for sensitive actions
- Rate limit password reset attempts

## Session Security

Configure in `config/session.php`:
- `expire_on_close` => false (use remember me)
- `same_site` => 'lax'
- `secure` => env('SESSION_SECURE_COOKIE', true) in production
- `http_only` => true

## Integrity Verification

### verifyIntegrity(Election $election)

```php
return [
    'total_votes' => Vote::where('election_id', $election->id)->count(),
    'total_vote_records' => VoteRecord::where('election_id', $election->id)->count(),
    'matches' => Vote::count() === VoteRecord::count(),
    'all_receipts_valid' => bool, // verify all receipt hashes
    'all_verification_codes_unique' => bool,
    'no_duplicate_votes' => bool, // no user has 2 vote_records for same position
    'all_candidates_approved' => bool, // all votes are for approved candidates
]
```

## Security Report View

**File:** `resources/views/dashboard/admin/security-report.blade.php`

- Overall security status
- Vote integrity check results
- Recent failed login attempts
- Rate limit hits
- Suspicious activity log
- Encryption status
- Security headers status

## Tests

### SecurityAuditTest

- `it encrypts vote choices`
- `it decrypts vote choices with correct key`
- `it cannot decrypt with wrong key`
- `it generates unique verification codes`
- `it generates valid receipt hashes`
- `it logs all auditable actions`
- `it prevents rapid login attempts (rate limiting)`
- `it prevents rapid registration attempts`
- `it prevents rapid vote attempts`
- `it sets security headers`
- `it verifies vote integrity`
- `it detects duplicate votes`
- `it detects vote count mismatches`
- `it generates security report`
- `audit log stores old and new values`
- `audit log records ip address and user agent`

## Do NOT proceed until:
- [ ] AuditService and SecurityService created
- [ ] All actions audited
- [ ] Rate limiting configured and applied
- [ ] SecurityHeaders middleware created
- [ ] PreventFraudMiddleware created
- [ ] Integrity verification working
- [ ] Security report view created
- [ ] All 16 Pest tests pass
- [ ] Pint formatting passes
