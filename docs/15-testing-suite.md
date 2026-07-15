# 15 — Testing Suite

## Overview

Complete test suite using Pest v4 — unit tests, feature tests, factories, and test helpers.

## Execution Instructions

1. Create all factories with states
2. Create test helpers and base test class
3. Write all unit tests
4. Write all feature tests
5. Run `php artisan test --compact`
6. Fix any failures
7. Run `vendor/bin/pint --dirty --format agent`
8. Update `docs/PROGRESS.md`

## Test Configuration

**File:** `tests/Pest.php` (modify if needed)

```php
<?php

uses(
    Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature', 'Unit');
```

## Factories

Create/modify using: `php artisan make:factory {Name}Factory --no-interaction`

### UserFactory

```php
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => 'voter',
            'student_id' => fake()->unique()->regexify('[A-Z]{3}/[0-9]{4}/[0-9]{3}'),
            'phone' => fake()->phoneNumber(),
            'faculty' => 'Faculty of Engineering',
            'department' => 'Computer Science',
            'course' => 'BSc Computer Science',
            'year_of_study' => fake()->numberBetween(1, 4),
            'is_active' => true,
        ];
    }

    public function admin(): static { return $this->state(['role' => 'admin', 'student_id' => null]); }
    public function voter(): static { return $this->state(['role' => 'voter']); }
    public function candidate(): static { return $this->state(['role' => 'candidate']); }
    public function unverified(): static { return $this->state(['email_verified_at' => null]); }
    public function inactive(): static { return $this->state(['is_active' => false]); }
}
```

### FacultyFactory, DepartmentFactory

Standard factories with appropriate fake data.

### ElectionFactory

```php
class ElectionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'slug' => fake()->slug(),
            'description' => fake()->paragraph(),
            'status' => 'draft',
            'type' => 'general',
            'starts_at' => now()->addDays(7),
            'ends_at' => now()->addDays(14),
            'is_anonymous' => true,
            'require_2fa' => false,
            'created_by' => User::factory()->admin(),
        ];
    }

    public function draft(): static { return $this->state(['status' => 'draft']); }
    public function scheduled(): static { return $this->state(['status' => 'scheduled', 'starts_at' => now()->addDays(1)]); }
    public function active(): static { return $this->state(['status' => 'active', 'starts_at' => now()->subHour(), 'ends_at' => now()->addDays(7)]); }
    public function completed(): static { return $this->state(['status' => 'completed', 'starts_at' => now()->subDays(14), 'ends_at' => now()->subDays(7), 'results_published_at' => now()->subDays(6)]); }
    public function cancelled(): static { return $this->state(['status' => 'cancelled']); }
}
```

### PositionFactory

```php
class PositionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'election_id' => Election::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraph(),
            'max_votes' => 1,
            'sort_order' => 0,
        ];
    }
}
```

### CandidateFactory

```php
class CandidateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->candidate(),
            'election_id' => Election::factory(),
            'position_id' => Position::factory(),
            'manifesto_title' => fake()->sentence(),
            'manifesto' => fake()->paragraphs(3, true),
            'slogan' => fake()->catchPhrase(),
            'status' => 'pending',
        ];
    }

    public function approved(): static { return $this->state(['status' => 'approved', 'approved_at' => now(), 'approved_by' => User::factory()->admin()]); }
    public function rejected(): static { return $this->state(['status' => 'rejected', 'rejection_reason' => 'Does not meet requirements']); }
    public function pending(): static { return $this->state(['status' => 'pending']); }
}
```

### VoteFactory

```php
class VoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'election_id' => Election::factory(),
            'position_id' => Position::factory(),
            'candidate_id' => Candidate::factory(),
            'verification_code' => strtoupper(fake()->unique()->regexify('[A-Z2-9]{16}')),
            'receipt_hash' => hash('sha256', fake()->uuid()),
            'cast_at' => now(),
        ];
    }
}
```

### VoteRecordFactory, AuditLogFactory, ElectionVoterFactory, ElectionSettingFactory, NotificationFactory

Create all with appropriate fake data.

## Test Helpers

**File:** `tests/Helpers.php` (autoloaded via composer.json or Pest.php)

```php
function adminUser(): User { return User::factory()->admin()->create(); }
function voterUser(): User { return User::factory()->voter()->create(); }
function candidateUser(): User { return User::factory()->candidate()->create(); }

function activeElection(): Election {
    return Election::factory()->active()->create();
}

function electionWithPositions(): array {
    $election = Election::factory()->active()->create();
    $positions = Position::factory()->count(3)->create(['election_id' => $election->id]);
    return [$election, $positions];
}

function electionWithCandidates(): array {
    [$election, $positions] = electionWithPositions();
    $candidates = Candidate::factory()->approved()->count(5)->create([
        'election_id' => $election->id,
        'position_id' => $positions[0]->id,
    ]);
    return [$election, $positions, $candidates];
}

function actingAsAdmin(): TestCase { return test()->actingAs(adminUser()); }
function actingAsVoter(): TestCase { return test()->actingAs(voterUser()); }
function actingAsCandidate(): TestCase { return test()->actingAs(candidateUser()); }
```

## Unit Tests

Create in `tests/Unit/`:

### UserModelTest
- `it has correct fillable attributes`
- `it hashes password on save`
- `it casts email_verified_at to datetime`
- `it can check if admin`
- `it can check if voter`
- `it can check if candidate`
- `it has candidates relationship`
- `it has elections relationship`
- `it has vote records relationship`
- `it can check if voted in election`
- `it can check if voted for position`

### ElectionModelTest
- `it has correct fillable attributes`
- `it casts dates correctly`
- `it has positions relationship`
- `it has candidates relationship`
- `it has votes relationship`
- `it has voters relationship`
- `it can check if ongoing`
- `it can check if completed`
- `it can check if upcoming`
- `it calculates turnout percentage`
- `it gets time remaining`

### CandidateModelTest
- `it has correct fillable attributes`
- `it has user relationship`
- `it has election relationship`
- `it has position relationship`
- `it can check if approved`
- `it can check if pending`
- `it calculates vote count`
- `it calculates vote percentage`

### VoteModelTest
- `it generates unique verification codes`
- `it generates receipt hashes`
- `it does not have user_id column`
- `it casts cast_at to datetime`

### VoteServiceTest
- `it can cast a vote`
- `it prevents double voting`
- `it encrypts vote choices`
- `it can verify vote by code`
- `it can tally position votes`
- `it can tally election votes`

### ElectionServiceTest
- `it can create election`
- `it can start election`
- `it prevents starting without positions`
- `it can end election`
- `it can publish results`
- `it can add voters`

### SecurityServiceTest
- `it encrypts and decrypts choices`
- `it cannot decrypt with wrong key`
- `it verifies receipt hashes`
- `it checks vote integrity`

## Feature Tests

Create in `tests/Feature/`:

### AuthFeatureTest (20 tests — see doc 03)
### MiddlewarePolicyTest (14 tests — see doc 04)
### AdminDashboardTest (22 tests — see doc 05)
### VoterDashboardTest (19 tests — see doc 06)
### CandidateDashboardTest (15 tests — see doc 07)
### ElectionManagementTest (22 tests — see doc 08)
### VotingEngineTest (20 tests — see doc 09)
### ResultsAnalyticsTest (15 tests — see doc 10)
### NotificationTest (13 tests — see doc 11)
### SecurityAuditTest (16 tests — see doc 12)
### RouteTest (11 tests — see doc 13)
### FrontendViewTest (8 tests — see doc 14)

## Running Tests

```bash
# Run all tests
php artisan test --compact

# Run specific test file
php artisan test --compact --filter=AuthFeatureTest

# Run with coverage
php artisan test --compact --coverage

# Run only unit tests
php artisan test --compact tests/Unit

# Run only feature tests
php artisan test --compact tests/Feature
```

## Test Count Summary

| Suite | Tests |
|-------|-------|
| Unit | ~35 |
| Auth | ~20 |
| Middleware/Policy | ~14 |
| Admin Dashboard | ~22 |
| Voter Dashboard | ~19 |
| Candidate Dashboard | ~15 |
| Election Management | ~22 |
| Voting Engine | ~20 |
| Results & Analytics | ~15 |
| Notifications | ~13 |
| Security & Audit | ~16 |
| Routes | ~11 |
| Frontend Views | ~8 |
| **Total** | **~230** |

## Do NOT proceed until:
- [ ] All 12 factories created with states
- [ ] Test helpers created
- [ ] All unit tests (~35) pass
- [ ] All feature tests (~195) pass
- [ ] `php artisan test --compact` shows all green
- [ ] No skipped tests
- [ ] Pint formatting passes
