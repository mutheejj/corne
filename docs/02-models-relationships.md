# 02 — Models & Relationships

## Overview

All Eloquent models for Cornelect. Each model must include: fillable attributes, casts, relationships, scopes, accessors, and a factory.

## Execution Instructions

1. Create each model using `php artisan make:model {Name} --no-interaction`
2. Create factories using `php artisan make:factory {Name}Factory --no-interaction`
3. Implement all relationships, scopes, and accessors exactly as specified
4. Run `vendor/bin/pint --dirty --format agent`
5. Update `docs/PROGRESS.md`

## Models

### User

**File:** `app/Models/User.php` (modify existing)

```php
// Add to fillable: role, student_id, phone, faculty, department, course, year_of_study, avatar, is_active
// Add to casts: email_verified_at => datetime, password => hashed, last_login_at => datetime, is_active => boolean

// Relationships:
public function candidates() // hasMany Candidate
public function elections() // belongsToMany Election via election_voters
public function voteRecords() // hasMany VoteRecord
public function auditLogs() // hasMany AuditLog
public function notifications() // hasMany Notification (morphTo via Notifiable trait)
public function createdElections() // hasMany Election (created_by)
public function approvedCandidates() // hasMany Candidate (approved_by)

// Scopes:
public function scopeAdmins($query) // where role = admin
public function scopeVoters($query) // where role = voter
public function scopeCandidates($query) // where role = candidate
public function scopeActive($query) // where is_active = true

// Accessors:
public function getIsAdminAttribute(): bool
public function getIsVoterAttribute(): bool
public function getIsCandidateAttribute(): bool
public function getAvatarUrlAttribute(): string // returns avatar or default placeholder

// Methods:
public function hasVotedIn(Election $election): bool
public function hasVotedForPosition(Position $position): bool
```

**Factory states:** `admin()`, `voter()`, `candidate()`, `verified()`, `unverified()`

### Faculty

**File:** `app/Models/Faculty.php`

```php
#[Fillable(['name', 'code', 'description', 'is_active'])]

// Relationships:
public function departments() // hasMany Department
public function elections() // hasMany Election
public function users() // hasMany User (by faculty string match — no FK)

// Scopes:
public function scopeActive($query)
```

### Department

**File:** `app/Models/Department.php`

```php
#[Fillable(['faculty_id', 'name', 'code', 'is_active'])]

// Relationships:
public function faculty() // belongsTo Faculty
public function elections() // hasMany Election

// Scopes:
public function scopeActive($query)
```

### Election

**File:** `app/Models/Election.php`

```php
#[Fillable(['title', 'slug', 'description', 'status', 'type', 'faculty_id', 'department_id', 'starts_at', 'ends_at', 'results_published_at', 'is_anonymous', 'require_2fa', 'created_by', 'settings'])]

// Casts:
// status => string (enum), starts_at => datetime, ends_at => datetime, results_published_at => datetime, settings => array

// Relationships:
public function creator() // belongsTo User (created_by)
public function faculty() // belongsTo Faculty
public function department() // belongsTo Department
public function positions() // hasMany Position
public function candidates() // hasMany Candidate
public function votes() // hasMany Vote
public function voteRecords() // hasMany VoteRecord
public function voters() // belongsToMany User via election_voters
public function settings() // hasOne ElectionSetting
public function auditLogs() // hasMany AuditLog (by model_type filter)

// Scopes:
public function scopeActive($query) // where status = active
public function scopeScheduled($query) // where status = scheduled
public function scopeCompleted($query) // where status = completed
public function scopeDraft($query) // where status = draft
public function scopeOngoing($query) // where status in [active, paused]

// Accessors:
public function getIsOngoingAttribute(): bool
public function getIsCompletedAttribute(): bool
public function getIsUpcomingAttribute(): bool
public function getTimeRemainingAttribute(): ?int // seconds remaining, null if not active
public function getTotalVotersAttribute(): int
public function getTurnoutPercentageAttribute(): float
public function getUrlAttribute(): string // route('elections.show', $this)

// Methods:
public function canStart(): bool
public function canEnd(): bool
public function hasVoted(User $user): bool
public function totalVotesCast(): int
```

**Factory states:** `draft()`, `scheduled()`, `active()`, `completed()`, `cancelled()`

### Position

**File:** `app/Models/Position.php`

```php
#[Fillable(['election_id', 'title', 'description', 'max_votes', 'sort_order'])]

// Relationships:
public function election() // belongsTo Election
public function candidates() // hasMany Candidate
public function votes() // hasMany Vote
public function voteRecords() // hasMany VoteRecord

// Scopes:
public function scopeOrdered($query) // orderBy sort_order

// Accessors:
public function getTotalVotesAttribute(): int
public function getApprovedCandidatesAttribute() // candidates where status = approved
```

### Candidate

**File:** `app/Models/Candidate.php`

```php
#[Fillable(['user_id', 'election_id', 'position_id', 'manifesto_title', 'manifesto', 'photo', 'slogan', 'status', 'rejection_reason', 'approved_at', 'approved_by'])]

// Casts: approved_at => datetime

// Relationships:
public function user() // belongsTo User
public function election() // belongsTo Election
public function position() // belongsTo Position
public function approver() // belongsTo User (approved_by)
public function votes() // hasMany Vote

// Scopes:
public function scopeApproved($query)
public function scopePending($query)
public function scopeRejected($query)

// Accessors:
public function getVoteCountAttribute(): int
public function getVotePercentageAttribute(): float
public function getPhotoUrlAttribute(): string
public function getIsApprovedAttribute(): bool
public function getIsPendingAttribute(): bool
```

**Factory states:** `pending()`, `approved()`, `rejected()`

### Vote

**File:** `app/Models/Vote.php`

```php
#[Fillable(['election_id', 'position_id', 'candidate_id', 'verification_code', 'receipt_hash', 'encrypted_choice', 'cast_at'])]

// Casts: cast_at => datetime

// Relationships:
public function election() // belongsTo Election
public function position() // belongsTo Position
public function candidate() // belongsTo Candidate

// Scopes:
public function scopeForElection($query, Election $election)
public function scopeForPosition($query, Position $position)

// Methods:
public static function generateVerificationCode(): string // 16-char alphanumeric
public static function generateReceiptHash(): string // SHA-256 hash
```

### VoteRecord

**File:** `app/Models/VoteRecord.php`

```php
#[Fillable(['user_id', 'election_id', 'position_id', 'verification_code', 'receipt_hash', 'voted_at'])]

// Casts: voted_at => datetime

// Relationships:
public function user() // belongsTo User
public function election() // belongsTo Election
public function position() // belongsTo Position

// Scopes:
public function scopeForElection($query, Election $election)
public function scopeForUser($query, User $user)
```

### AuditLog

**File:** `app/Models/AuditLog.php`

```php
#[Fillable(['user_id', 'action', 'model_type', 'model_id', 'description', 'old_values', 'new_values', 'ip_address', 'user_agent'])]

// Casts: old_values => array, new_values => array

// Relationships:
public function user() // belongsTo User

// Scopes:
public function scopeForModel($query, string $modelType, int $modelId)
public function scopeByAction($query, string $action)
public function scopeRecent($query, int $days = 30)

// Methods:
public static function log(string $action, string $description, array $data = []): void
```

### ElectionVoter

**File:** `app/Models/ElectionVoter.php`

```php
#[Fillable(['election_id', 'user_id', 'notified'])]

// Casts: notified => boolean

// Relationships:
public function election() // belongsTo Election
public function user() // belongsTo User
```

### ElectionSetting

**File:** `app/Models/ElectionSetting.php`

```php
#[Fillable(['election_id', 'allow_abstain', 'show_results_live', 'show_vote_count', 'require_student_id_verification', 'max_votes_per_position', 'voting_time_limit_minutes'])]

// Casts: all booleans => boolean

// Relationships:
public function election() // belongsTo Election
```

### Notification (use Laravel's built-in notification system)

Use the `Notifiable` trait on User. For in-app notifications, create a custom model:

**File:** `app/Models/Notification.php`

```php
#[Fillable(['user_id', 'type', 'title', 'message', 'data', 'read_at'])]

// Casts: data => array, read_at => datetime

// Relationships:
public function user() // belongsTo User

// Scopes:
public function scopeUnread($query)
public function scopeRecent($query, int $days = 7)

// Methods:
public function markAsRead(): void
public function markAsUnread(): void
```

## Verification

```bash
php artisan tinker --execute '
$u = User::factory()->voter()->create();
echo $u->role . "\n";
echo $u->is_voter ? "voter-ok\n" : "voter-fail\n";
$e = Election::factory()->active()->create();
echo $e->status . "\n";
echo $e->is_ongoing ? "ongoing-ok\n" : "ongoing-fail\n";
'
```

## Do NOT proceed until:
- [ ] All 12 models created with full relationships, scopes, accessors
- [ ] All factories created with states
- [ ] `php artisan tinker` verification passes
- [ ] Pint formatting passes
