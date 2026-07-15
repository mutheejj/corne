# 05 — Admin Dashboard

## Overview

Complete admin panel for managing elections, candidates, positions, viewing results, audit logs, and system settings.

## Execution Instructions

1. Create all controllers, views, and routes
2. Implement all CRUD operations
3. Create dashboard layout
4. Run tests
5. Run `vendor/bin/pint --dirty --format agent`
6. Update `docs/PROGRESS.md`

## Controllers

### AdminController

**File:** `app/Http/Controllers/AdminController.php`

Methods:
- `dashboard()` — overview stats: active elections, pending candidates, total voters, total votes cast, recent audit logs
- `elections()` — paginated list of all elections with filters (status, type, date)
- `createElection()` — form to create new election
- `storeElection(StoreElectionRequest $request)` — save election
- `showElection(Election $election)` — election detail with positions, candidates, votes
- `editElection(Election $election)` — edit form
- `updateElection(UpdateElectionRequest $request, Election $election)` — update
- `destroyElection(Election $election)` — delete (only draft elections)
- `startElection(Election $election)` — set status to active
- `endElection(Election $election)` — set status to completed
- `publishResults(Election $election)` — set results_published_at
- `manageCandidates(Election $election)` — list all candidates for an election
- `approveCandidate(Candidate $candidate)` — set status to approved, send notification
- `rejectCandidate(RejectCandidateRequest $request, Candidate $candidate)` — set status to rejected with reason, send notification
- `disqualifyCandidate(DisqualifyCandidateRequest $request, Candidate $candidate)` — set status to disqualified with reason
- `managePositions(Election $election)` — list positions for an election
- `createPosition(Election $election)` — form to add position
- `storePosition(StorePositionRequest $request, Election $election)` — save position
- `editPosition(Position $position)` — edit form
- `updatePosition(UpdatePositionRequest $request, Position $position)` — update
- `destroyPosition(Position $position)` — delete
- `manageVoters(Election $election)` — list eligible voters, add/remove voters
- `addVoters(AddVotersRequest $request, Election $election)` — bulk add voters by faculty/department/year
- `removeVoter(Election $election, User $user)` — remove voter from election
- `auditLogs()` — paginated audit logs with filters (action, user, date range)
- `voters()` — paginated list of all voters
- `showVoter(User $user)` — voter detail with voting history
- `settings()` — system settings form
- `updateSettings(UpdateSettingsRequest $request)` — save settings

### Form Requests

- `StoreElectionRequest` — title, description, type, faculty_id?, department_id?, starts_at, ends_at, is_anonymous, require_2fa
- `UpdateElectionRequest` — same as store (some fields optional)
- `StorePositionRequest` — title, description, max_votes, sort_order
- `UpdatePositionRequest` — same as store
- `RejectCandidateRequest` — rejection_reason (required string min:10)
- `DisqualifyCandidateRequest` — rejection_reason (required string min:10)
- `AddVotersRequest` — faculty?, department?, year_of_study?, user_ids[]?
- `UpdateSettingsRequest` — various settings fields

## Views

### Layout

**File:** `resources/views/layouts/dashboard.blade.php`

- Sidebar navigation (role-based: admin/voter/candidate)
- Top bar with user info, notifications dropdown, logout
- Content area yielding `dashboard-content`
- Responsive: sidebar collapses on mobile with hamburger toggle
- Uses same Tailwind CDN config and color scheme as main layout
- Links to `public/css/app.css` and `public/js/app.js`

### Admin Views

Create in `resources/views/dashboard/admin/`:

1. `dashboard.blade.php` — stats cards, charts, recent activity
2. `elections/index.blade.php` — elections table with status badges, actions
3. `elections/create.blade.php` — election form
4. `elections/show.blade.php` — election detail with tabs (overview, positions, candidates, voters, results, audit)
5. `elections/edit.blade.php` — edit form
6. `positions/index.blade.php` — positions list
7. `positions/create.blade.php` — position form
8. `positions/edit.blade.php` — edit form
9. `candidates/index.blade.php` — candidates table with approve/reject actions
10. `candidates/show.blade.php` — candidate detail with manifesto, photo
11. `voters/index.blade.php` — voters table
12. `voters/show.blade.php` — voter detail with voting history
13. `audit-logs/index.blade.php` — audit logs table with filters
14. `settings/index.blade.php` — settings form

### Sidebar Navigation Items

- Dashboard (`admin.dashboard`)
- Elections (`admin.elections.index`)
- Voters (`admin.voters.index`)
- Audit Logs (`admin.audit-logs.index`)
- Settings (`admin.settings.index`)

### Dashboard Stats Cards

1. **Active Elections** — count of active elections
2. **Pending Candidates** — count of candidates awaiting approval
3. **Total Voters** — count of voter role users
4. **Votes Cast Today** — count of votes cast today
5. **Upcoming Elections** — elections scheduled but not started
6. **Completed Elections** — completed elections count

### Charts (using vanilla JS / Chart.js via CDN)

1. **Voter Turnout** — doughnut chart showing voted vs not voted
2. **Votes Over Time** — line chart of votes cast per day
3. **Election Status Distribution** — bar chart of elections by status

## Routes

```php
Route::prefix('admin')->middleware(['auth', 'verified', 'active', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

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

    Route::get('/elections/{election}/candidates', [AdminController::class, 'manageCandidates'])->name('elections.candidates');
    Route::post('/candidates/{candidate}/approve', [AdminController::class, 'approveCandidate'])->name('candidates.approve');
    Route::post('/candidates/{candidate}/reject', [AdminController::class, 'rejectCandidate'])->name('candidates.reject');
    Route::post('/candidates/{candidate}/disqualify', [AdminController::class, 'disqualifyCandidate'])->name('candidates.disqualify');

    Route::get('/elections/{election}/positions', [AdminController::class, 'managePositions'])->name('elections.positions');
    Route::get('/elections/{election}/positions/create', [AdminController::class, 'createPosition'])->name('positions.create');
    Route::post('/elections/{election}/positions', [AdminController::class, 'storePosition'])->name('positions.store');
    Route::get('/positions/{position}/edit', [AdminController::class, 'editPosition'])->name('positions.edit');
    Route::put('/positions/{position}', [AdminController::class, 'updatePosition'])->name('positions.update');
    Route::delete('/positions/{position}', [AdminController::class, 'destroyPosition'])->name('positions.destroy');

    Route::get('/elections/{election}/voters', [AdminController::class, 'manageVoters'])->name('elections.voters');
    Route::post('/elections/{election}/voters', [AdminController::class, 'addVoters'])->name('elections.voters.add');
    Route::delete('/elections/{election}/voters/{user}', [AdminController::class, 'removeVoter'])->name('elections.voters.remove');

    Route::get('/voters', [AdminController::class, 'voters'])->name('voters.index');
    Route::get('/voters/{user}', [AdminController::class, 'showVoter'])->name('voters.show');

    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs.index');

    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});
```

## Audit Logging

Every admin action must be logged:
- Election created/updated/deleted/started/ended/published
- Candidate approved/rejected/disqualified
- Position created/updated/deleted
- Voters added/removed
- Settings updated

Use `AuditLog::log($action, $description, $data)` in each controller method.

## Tests

### AdminDashboardTest

- `it shows admin dashboard for admin`
- `it prevents non-admin from accessing admin dashboard`
- `it can list elections`
- `it can create an election`
- `it validates election creation fields`
- `it can show election detail`
- `it can edit an election`
- `it can delete a draft election`
- `it cannot delete a non-draft election`
- `it can start an election`
- `it can end an election`
- `it can publish results`
- `it can approve a candidate`
- `it can reject a candidate with reason`
- `it can disqualify a candidate with reason`
- `it can create a position`
- `it can add voters to election`
- `it can remove voter from election`
- `it can view audit logs`
- `it logs all admin actions`
- `it can view voters list`
- `it can view voter detail`
- `it can update settings`

## Do NOT proceed until:
- [ ] AdminController with all methods
- [ ] All 8 Form Requests
- [ ] Dashboard layout created
- [ ] All 14 admin views created
- [ ] All routes registered
- [ ] Audit logging on all actions
- [ ] All 22 Pest tests pass
- [ ] Pint formatting passes
