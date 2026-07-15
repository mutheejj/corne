# 08 — Election Management

## Overview

Election lifecycle management — creation, scheduling, positions, ballot setup, voter eligibility, and status transitions.

## Execution Instructions

1. Create ElectionService for business logic
2. Implement election lifecycle commands
3. Create scheduled commands for auto-start/end
4. Run tests
5. Run `vendor/bin/pint --dirty --format agent`
6. Update `docs/PROGRESS.md`

## ElectionService

**File:** `app/Services/ElectionService.php`

```php
class ElectionService
{
    public function createElection(array $data): Election
    public function updateElection(Election $election, array $data): Election
    public function deleteElection(Election $election): bool
    public function startElection(Election $election): Election
    public function endElection(Election $election): Election
    public function pauseElection(Election $election): Election
    public function resumeElection(Election $election): Election
    public function cancelElection(Election $election): Election
    public function publishResults(Election $election): Election
    public function addVoters(Election $election, array $criteria): int
    public function removeVoter(Election $election, User $user): bool
    public function getEligibleVoters(Election $election): Collection
    public function getElectionResults(Election $election): array
    public function canStart(Election $election): bool
    public function canEnd(Election $election): bool
    public function canPublishResults(Election $election): bool
}
```

### Business Rules

- **Create**: Only admin. Must have at least 1 position before starting.
- **Start**: Election must be in 'scheduled' status, starts_at must be <= now, must have at least 1 approved candidate.
- **End**: Election must be in 'active' status, ends_at must be >= now (or admin override).
- **Publish Results**: Election must be 'completed'. Sets results_published_at.
- **Delete**: Only 'draft' elections can be deleted.
- **Add Voters**: Can add by faculty, department, year_of_study, or specific user_ids. Creates election_voters records.
- **Pause/Resume**: Only 'active' elections can be paused. Only 'paused' can be resumed.

## Election Lifecycle

```
draft → scheduled → active → completed → results_published
                ↓           ↓
            cancelled    paused → active
```

### Status Transitions

| From | To | Trigger |
|------|----|---------|
| draft | scheduled | Admin sets starts_at and schedules |
| scheduled | active | Admin starts OR auto-start command when starts_at reached |
| active | paused | Admin pauses |
| paused | active | Admin resumes |
| active | completed | Admin ends OR auto-end command when ends_at reached |
| completed | (results published) | Admin publishes results |
| any | cancelled | Admin cancels |

## Artisan Commands

Create using: `php artisan make:command {Name} --no-interaction`

### StartScheduledElections

**File:** `app/Console/Commands/StartScheduledElections.php`

```php
// Runs every minute via scheduler
// Finds elections with status=scheduled and starts_at <= now
// Starts them (sets status=active)
// Sends notifications to all eligible voters
```

### EndActiveElections

**File:** `app/Console/Commands/EndActiveElections.php`

```php
// Runs every minute via scheduler
// Finds elections with status=active and ends_at <= now
// Ends them (sets status=completed)
// Sends notifications to admin
```

### Schedule in `routes/console.php` or `app/Console/Kernel.php`:

```php
Schedule::command('elections:start-scheduled')->everyMinute();
Schedule::command('elections:end-active')->everyMinute();
```

## Position Management

### PositionService

**File:** `app/Services/PositionService.php`

```php
class PositionService
{
    public function createPosition(Election $election, array $data): Position
    public function updatePosition(Position $position, array $data): Position
    public function deletePosition(Position $position): bool
    public function reorderPositions(Election $election, array $order): void
}
```

### Position Rules

- Each election must have at least 1 position
- Positions can only be added/edited when election is in 'draft' or 'scheduled' status
- Cannot delete a position if it has votes
- max_votes determines how many candidates a voter can select (default: 1)
- sort_order determines display order on ballot

## Voter Eligibility

### EligibilityService

**File:** `app/Services/EligibilityService.php`

```php
class EligibilityService
{
    public function isEligible(User $user, Election $election): bool
    public function addVoters(Election $election, array $criteria): int
    public function addAllVoters(Election $election): int
    public function removeVoter(Election $election, User $user): bool
    public function notifyEligibleVoters(Election $election): void
    public function getEligibleCount(Election $election): int
}
```

### Eligibility Criteria

- User must have role = voter
- User must be active (is_active = true)
- User must be in election_voters table for the election
- If election has faculty_id set, voter's faculty must match
- If election has department_id set, voter's department must match
- User must have verified email

## Tests

### ElectionManagementTest

- `it can create an election`
- `it can schedule an election`
- `it can start a scheduled election`
- `it prevents starting election without positions`
- `it prevents starting election without approved candidates`
- `it can end an active election`
- `it can pause and resume an election`
- `it can cancel an election`
- `it can publish results`
- `it can delete draft election`
- `it cannot delete active election`
- `it can add voters by faculty`
- `it can add voters by department`
- `it can add specific voters`
- `it can remove voter from election`
- `it can create position`
- `it can update position`
- `it cannot delete position with votes`
- `it can reorder positions`
- `auto-start command starts scheduled elections`
- `auto-end command ends active elections`
- `it notifies eligible voters when election starts`

## Do NOT proceed until:
- [ ] ElectionService, PositionService, EligibilityService created
- [ ] 2 Artisan commands created and scheduled
- [ ] All business rules enforced
- [ ] All 22 Pest tests pass
- [ ] Pint formatting passes
