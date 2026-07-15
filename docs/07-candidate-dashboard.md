# 07 — Candidate Dashboard

## Overview

Candidate panel for managing campaign profile, viewing election status, and viewing results.

## Execution Instructions

1. Create controller, views, and routes
2. Implement campaign profile management
3. Run tests
4. Run `vendor/bin/pint --dirty --format agent`
5. Update `docs/PROGRESS.md`

## Controllers

### CandidateController

**File:** `app/Http/Controllers/CandidateController.php`

Methods:
- `dashboard()` — overview: current candidacy status, election info, position, vote count (if results published)
- `profile()` — view own candidate profile
- `updateProfile(UpdateCandidateProfileRequest $request)` — update manifesto, slogan, photo
- `uploadPhoto(Request $request)` — upload/update campaign photo
- `myElection()` — view the election the candidate is running in
- `myPosition()` — view position details and competitors (approved candidates only)
- `results()` — view published results for own election/position
- `withdraw()` — withdraw candidacy (sets status to rejected with reason "Withdrawn by candidate")

### UpdateCandidateProfileRequest

```php
rules: [
    'manifesto_title' => ['required', 'string', 'max:255'],
    'manifesto' => ['required', 'string', 'min:100'],
    'slogan' => ['nullable', 'string', 'max:255'],
    'photo' => ['nullable', 'image', 'max:2048'],
]
```

## Views

Create in `resources/views/dashboard/candidate/`:

1. `dashboard.blade.php` — status card (pending/approved/rejected), election info, position info
2. `profile.blade.php` — edit campaign profile form
3. `election.blade.php` — election details with timeline and status
4. `position.blade.php` — position info with list of approved competitors
5. `results.blade.php` — results view (only if published) with vote count, percentage, ranking
6. `withdraw.blade.php` — withdrawal confirmation form

### Dashboard Status Display

- **Pending**: Yellow banner "Your candidacy is under review"
- **Approved**: Green banner "Your candidacy is approved! Start campaigning."
- **Rejected**: Red banner with rejection reason
- **Disqualified**: Red banner with disqualification reason

### Sidebar Navigation Items

- Dashboard (`candidate.dashboard`)
- My Profile (`candidate.profile`)
- My Election (`candidate.election`)
- My Position (`candidate.position`)
- Results (`candidate.results`)

## Routes

```php
Route::prefix('candidate')->middleware(['auth', 'verified', 'active', 'role:candidate'])->name('candidate.')->group(function () {
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

## Tests

### CandidateDashboardTest

- `it shows candidate dashboard for candidate`
- `it prevents non-candidate from accessing candidate dashboard`
- `it shows pending status for pending candidate`
- `it shows approved status for approved candidate`
- `it shows rejected status with reason for rejected candidate`
- `it can update campaign profile`
- `it validates profile update fields`
- `it can upload photo`
- `it can view own election`
- `it can view own position with competitors`
- `it can view published results`
- `it hides unpublished results`
- `it can withdraw candidacy`
- `it prevents editing profile after election starts`
- `it shows vote count only after results published`

## Do NOT proceed until:
- [ ] CandidateController with all methods
- [ ] UpdateCandidateProfileRequest
- [ ] All 6 candidate views created
- [ ] All routes registered
- [ ] All 15 Pest tests pass
- [ ] Pint formatting passes
