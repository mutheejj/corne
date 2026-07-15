# 01 — Database Schema & Migrations

## Overview

This file defines the complete database schema for Cornelect. All migrations must be created using `php artisan make:migration`.

## Execution Instructions

1. Create every migration listed below using `php artisan make:migration {name} --no-interaction`
2. Fill in the schema exactly as specified
3. Run `php artisan migrate --no-interaction` to apply
4. Run `vendor/bin/pint --dirty --format agent`
5. Update `docs/PROGRESS.md`

## Tables to Create

### 1. Modify `users` table (new migration to add columns)

**Migration:** `add_cornelect_fields_to_users_table`

Add the following columns to the existing `users` table:

```php
Schema::table('users', function (Blueprint $table) {
    $table->enum('role', ['admin', 'voter', 'candidate'])->default('voter')->after('email');
    $table->string('student_id')->unique()->nullable()->after('role');
    $table->string('phone')->nullable()->after('student_id');
    $table->string('faculty')->nullable()->after('phone');
    $table->string('department')->nullable()->after('faculty');
    $table->string('course')->nullable()->after('department');
    $table->integer('year_of_study')->nullable()->after('course');
    $table->string('avatar')->nullable()->after('year_of_study');
    $table->boolean('is_active')->default(true)->after('avatar');
    $table->timestamp('last_login_at')->nullable()->after('is_active');
    $table->string('two_factor_secret')->nullable()->after('last_login_at');
    $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_secret');
});
```

### 2. `faculties`

```php
Schema::create('faculties', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('code')->unique();
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 3. `departments`

```php
Schema::create('departments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('faculty_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('code')->unique();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4. `elections`

```php
Schema::create('elections', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('description');
    $table->enum('status', ['draft', 'scheduled', 'active', 'paused', 'completed', 'cancelled'])->default('draft');
    $table->enum('type', ['general', 'faculty', 'department', 'student_union', 'special'])->default('general');
    $table->foreignId('faculty_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
    $table->timestamp('starts_at');
    $table->timestamp('ends_at');
    $table->timestamp('results_published_at')->nullable();
    $table->boolean('is_anonymous')->default(true);
    $table->boolean('require_2fa')->default(false);
    $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
    $table->json('settings')->nullable();
    $table->timestamps();
});
```

### 5. `positions`

```php
Schema::create('positions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('election_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->text('description')->nullable();
    $table->integer('max_votes')->default(1);
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});
```

### 6. `candidates`

```php
Schema::create('candidates', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('election_id')->constrained()->cascadeOnDelete();
    $table->foreignId('position_id')->constrained()->cascadeOnDelete();
    $table->string('manifesto_title')->nullable();
    $table->longText('manifesto')->nullable();
    $table->string('photo')->nullable();
    $table->string('slogan')->nullable();
    $table->enum('status', ['pending', 'approved', 'rejected', 'disqualified'])->default('pending');
    $table->text('rejection_reason')->nullable();
    $table->timestamp('approved_at')->nullable();
    $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamps();

    $table->unique(['user_id', 'position_id']);
});
```

### 7. `votes`

**CRITICAL:** This table must NOT link to user identity directly. Vote anonymity is enforced by separating voter records from ballot choices.

```php
Schema::create('votes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('election_id')->constrained()->cascadeOnDelete();
    $table->foreignId('position_id')->constrained()->cascadeOnDelete();
    $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
    $table->string('verification_code')->unique();
    $table->string('receipt_hash')->unique();
    $table->string('encrypted_choice')->nullable();
    $table->timestamp('cast_at');
    $table->timestamps();
});
```

### 8. `vote_records`

Tracks WHO voted (to prevent double voting) without linking to HOW they voted.

```php
Schema::create('vote_records', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('election_id')->constrained()->cascadeOnDelete();
    $table->foreignId('position_id')->constrained()->cascadeOnDelete();
    $table->string('verification_code');
    $table->string('receipt_hash');
    $table->timestamp('voted_at');
    $table->timestamps();

    $table->unique(['user_id', 'position_id']);
});
```

### 9. `audit_logs`

```php
Schema::create('audit_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
    $table->string('action');
    $table->string('model_type')->nullable();
    $table->unsignedBigInteger('model_id')->nullable();
    $table->text('description');
    $table->json('old_values')->nullable();
    $table->json('new_values')->nullable();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->timestamps();

    $table->index(['model_type', 'model_id']);
    $table->index('action');
});
```

### 10. `notifications`

```php
Schema::create('notifications', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->string('type');
    $table->string('title');
    $table->text('message');
    $table->json('data')->nullable();
    $table->timestamp('read_at')->nullable();
    $table->timestamps();

    $table->index(['user_id', 'read_at']);
});
```

### 11. `election_voters`

Pivot table for eligible voters per election.

```php
Schema::create('election_voters', function (Blueprint $table) {
    $table->id();
    $table->foreignId('election_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->boolean('notified')->default(false);
    $table->timestamps();

    $table->unique(['election_id', 'user_id']);
});
```

### 12. `election_settings`

```php
Schema::create('election_settings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('election_id')->constrained()->cascadeOnDelete();
    $table->boolean('allow_abstain')->default(true);
    $table->boolean('show_results_live')->default(false);
    $table->boolean('show_vote_count')->default(true);
    $table->boolean('require_student_id_verification')->default(true);
    $table->integer('max_votes_per_position')->default(1);
    $table->integer('voting_time_limit_minutes')->nullable();
    $table->timestamps();
});
```

### 13. `password_reset_tokens` (already exists — no change needed)

### 14. `personal_access_tokens` (for API if needed later)

```php
Schema::create('personal_access_tokens', function (Blueprint $table) {
    $table->id();
    $table->morphs('tokenable');
    $table->string('name');
    $table->string('token', 64)->unique();
    $table->text('abilities')->nullable();
    $table->timestamp('last_used_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->timestamps();
});
```

## Seeders

After all migrations, create seeders:

1. `DatabaseSeeder` — calls all individual seeders
2. `FacultySeeder` — 5 faculties
3. `DepartmentSeeder` — 15 departments across faculties
4. `AdminUserSeeder` — 1 admin user (email: admin@cornelect.ac.ke, password: password)
5. `VoterSeeder` — 50 voters with realistic data
6. `CandidateSeeder` — 10 candidates across positions
7. `ElectionSeeder` — 2 elections (1 active, 1 completed) with positions

## Verification

After creating all migrations:

```bash
php artisan migrate:fresh --seed --no-interaction
php artisan tinker --execute 'echo \App\Models\User::count() . " users, " . \App\Models\Election::count() . " elections";'
```

Expected output: `51 users, 2 elections` (1 admin + 50 voters, 2 elections)

## Do NOT proceed until:
- [ ] All 14 migrations created and migrated successfully
- [ ] All seeders created and seeding works
- [ ] `php artisan migrate:fresh --seed` runs without errors
- [ ] Pint formatting passes
