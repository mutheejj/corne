# 06 — Voter Dashboard

## Overview

Voter panel for viewing elections, casting votes, verifying votes, and viewing results.

## Execution Instructions

1. Create controller, views, and routes
2. Implement vote casting flow
3. Run tests
4. Run `vendor/bin/pint --dirty --format agent`
5. Update `docs/PROGRESS.md`

## Controllers

### VoterController

**File:** `app/Http/Controllers/VoterController.php`

Methods:
- `dashboard()` — overview: active elections available to voter, completed elections with results, voting history
- `elections()` — list of elections the voter is eligible for (filtered by faculty/department if applicable)
- `showElection(Election $election)` — election detail with positions, candidates, vote status per position
- `showBallot(Election $election, Position $position)` — voting ballot with candidate list
- `castVote(CastVoteRequest $request, Election $election, Position $position)` — process vote, generate verification code + receipt hash, redirect to confirmation
- `voteConfirmation(Election $election, Position $position)` — show vote confirmation with verification code and receipt hash
- `verifyVote(Request $request)` — form to verify vote using verification code
- `verifyVoteResult(Request $request)` — lookup vote by verification code, show confirmation
- `voteHistory()` — list of all votes cast by the voter with verification codes
- `results(Election $election)` — view published results (only if results_published_at is set)
- `profile()` — voter profile view
- `updateProfile(UpdateProfileRequest $request)` — update profile

### CastVoteRequest

```php
rules: [
    'candidate_id' => ['required', 'exists:candidates,id'],
    'abstain' => ['boolean'],
]
// Must validate:
// - Election is active
// - Position belongs to election
// - Candidate belongs to position and is approved
// - User hasn't already voted for this position
// - User is in election_voters for this election
```

### UpdateProfileRequest

```php
rules: [
    'name' => ['required', 'string', 'max:255'],
    'phone' => ['required', 'string', 'max:20'],
    'avatar' => ['nullable', 'image', 'max:2048'],
]
```

## Vote Casting Flow

1. Voter navigates to election → sees positions
2. For each position, sees "Vote" button (if not voted) or "Voted" badge (if voted)
3. Clicks "Vote" → sees ballot with approved candidates
4. Selects candidate → confirms → submits
5. System:
   - Validates eligibility
   - Creates `Vote` record with encrypted choice, verification code, receipt hash
   - Creates `VoteRecord` linking user to position (for double-vote prevention)
   - Logs to audit trail (without revealing vote choice)
   - Redirects to confirmation page
6. Confirmation page shows:
   - Verification code (16-char alphanumeric)
   - Receipt hash (SHA-256)
   - Instructions to save these for later verification
   - "Download Receipt" button (generates PDF or printable page)

## Views

Create in `resources/views/dashboard/voter/`:

1. `dashboard.blade.php` — stats: available elections, votes cast, pending elections
2. `elections/index.blade.php` — list of available elections with status and vote progress
3. `elections/show.blade.php` — election detail with positions list
4. `ballot.blade.php` — voting ballot with candidate cards (photo, name, manifesto summary, slogan)
5. `vote-confirmation.blade.php` — confirmation with verification code and receipt hash
6. `verify-vote.blade.php` — form to enter verification code
7. `verify-result.blade.php` — verification result showing vote was counted
8. `vote-history.blade.php` — table of past votes with codes
9. `results.blade.php` — published results with charts
10. `profile.blade.php` — profile view/edit form

### Ballot View Details

- Shows position title and description
- Lists approved candidates as cards with:
  - Photo (or placeholder)
  - Name
  - Slogan
  - "View Manifesto" link (opens modal or expands)
  - Radio button or selection card
- "Abstain" option (if election settings allow_abstain = true)
- "Cast Vote" button with confirmation modal ("Are you sure? This cannot be undone.")
- Timer if election has voting_time_limit_minutes set

### Sidebar Navigation Items

- Dashboard (`voter.dashboard`)
- My Elections (`voter.elections.index`)
- Vote History (`voter.vote-history`)
- Verify Vote (`voter.verify-vote`)
- Profile (`voter.profile`)

## Routes

```php
Route::prefix('voter')->middleware(['auth', 'verified', 'active', 'role:voter'])->name('voter.')->group(function () {
    Route::get('/', [VoterController::class, 'dashboard'])->name('dashboard');
    Route::get('/elections', [VoterController::class, 'elections'])->name('elections.index');
    Route::get('/elections/{election}', [VoterController::class, 'showElection'])->name('elections.show');
    Route::get('/elections/{election}/positions/{position}/ballot', [VoterController::class, 'showBallot'])->name('ballot.show');
    Route::post('/elections/{election}/positions/{position}/vote', [VoterController::class, 'castVote'])->name('votes.cast');
    Route::get('/elections/{election}/positions/{position}/confirmation', [VoterController::class, 'voteConfirmation'])->name('votes.confirmation');
    Route::get('/verify-vote', [VoterController::class, 'verifyVote'])->name('verify-vote');
    Route::post('/verify-vote', [VoterController::class, 'verifyVoteResult'])->name('verify-vote.result');
    Route::get('/vote-history', [VoterController::class, 'voteHistory'])->name('vote-history');
    Route::get('/elections/{election}/results', [VoterController::class, 'results'])->name('elections.results');
    Route::get('/profile', [VoterController::class, 'profile'])->name('profile');
    Route::put('/profile', [VoterController::class, 'updateProfile'])->name('profile.update');
});
```

## Tests

### VoterDashboardTest

- `it shows voter dashboard for voter`
- `it prevents non-voter from accessing voter dashboard`
- `it lists elections available to voter`
- `it shows election detail with positions`
- `it shows ballot for unvoted position`
- `it prevents access to ballot for already voted position`
- `it can cast a vote`
- `it prevents double voting`
- `it shows vote confirmation with verification code`
- `it can verify vote with valid code`
- `it cannot verify vote with invalid code`
- `it shows vote history`
- `it shows published results`
- `it hides unpublished results`
- `it can update profile`
- `it prevents voting on non-active election`
- `it prevents voting for non-approved candidate`
- `it can abstain from a position`
- `it logs vote in audit trail without revealing choice`

## Do NOT proceed until:
- [ ] VoterController with all methods
- [ ] CastVoteRequest and UpdateProfileRequest
- [ ] All 10 voter views created
- [ ] All routes registered
- [ ] Vote casting flow fully working
- [ ] Vote verification working
- [ ] All 19 Pest tests pass
- [ ] Pint formatting passes
