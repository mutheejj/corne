# 11 — Notifications

## Overview

Email, in-app, and optional SMS notifications for election events, candidate status changes, and system alerts.

## Execution Instructions

1. Create all notification classes
2. Create in-app notification system
3. Register notifications in events/listeners
4. Run tests
5. Run `vendor/bin/pint --dirty --format agent`
6. Update `docs/PROGRESS.md`

## Notifications

Create using: `php artisan make:notification {Name} --no-interaction`

### Email Notifications

#### ElectionStartedNotification
- **To:** All eligible voters in election_voters
- **Subject:** `Voting is now open: {election_title}`
- **Content:** Election title, positions, deadline, link to ballot
- **Trigger:** Election status changes to 'active'

#### ElectionEndingSoonNotification
- **To:** Eligible voters who haven't voted
- **Subject:** `Reminder: {election_title} ends in {hours} hours`
- **Content:** Election title, time remaining, link to ballot
- **Trigger:** Scheduled command 24h and 1h before election ends

#### ElectionResultsPublishedNotification
- **To:** All voters in election + all candidates
- **Subject:** `Results published: {election_title}`
- **Content:** Link to results page
- **Trigger:** Admin publishes results

#### CandidateApprovedNotification
- **To:** The candidate user
- **Subject:** `Your candidacy has been approved`
- **Content:** Position title, election title, next steps
- **Trigger:** Admin approves candidate

#### CandidateRejectedNotification
- **To:** The candidate user
- **Subject:** `Your candidacy application status`
- **Content:** Rejection reason, appeal instructions
- **Trigger:** Admin rejects candidate

#### CandidateDisqualifiedNotification
- **To:** The candidate user
- **Subject:** `Your candidacy has been disqualified`
- **Content:** Disqualification reason
- **Trigger:** Admin disqualifies candidate

#### VoteConfirmationNotification
- **To:** The voter
- **Subject:** `Vote confirmation — {election_title}`
- **Content:** Verification code, receipt hash, position title, instructions to save
- **Trigger:** Vote successfully cast

#### PasswordResetNotification
- **To:** User requesting reset
- **Subject:** `Reset your Cornelect password`
- **Content:** Reset link with token
- **Trigger:** Forgot password form submitted

#### EmailVerificationNotification
- **To:** Newly registered user
- **Subject:** `Verify your email — Cornelect`
- **Content:** Signed verification URL
- **Trigger:** User registers

#### AccountDeactivatedNotification
- **To:** User being deactivated
- **Subject:** `Your account has been deactivated`
- **Content:** Reason, contact info
- **Trigger:** Admin deactivates user

### In-App Notifications

**File:** `app/Models/Notification.php` (custom, not Laravel's default)

Store in `notifications` table with:
- user_id, type, title, message, data (JSON), read_at

#### Notification Types

| Type | Title | Trigger |
|------|-------|---------|
| `election_started` | Voting is now open | Election starts |
| `election_ending` | Election ends soon | 24h/1h before end |
| `results_published` | Results are available | Results published |
| `candidate_approved` | Candidacy approved | Admin approves |
| `candidate_rejected` | Candidacy rejected | Admin rejects |
| `vote_cast` | Vote confirmed | Vote cast |
| `welcome` | Welcome to Cornelect | User registers |
| `system` | System message | Admin sends |

## NotificationService

**File:** `app/Services/NotificationService.php`

```php
class NotificationService
{
    public function notify(User $user, string $type, string $title, string $message, array $data = []): void
    public function notifyMany(Collection $users, string $type, string $title, string $message, array $data = []): void
    public function notifyEligibleVoters(Election $election, string $type, string $title, string $message): void
    public function markAsRead(int $notificationId): void
    public function markAllAsRead(User $user): void
    public function getUnreadCount(User $user): int
    public function getRecent(User $user, int $limit = 10): Collection
}
```

## Event-Driven Notifications

### Events

Create using: `php artisan make:event {Name} --no-interaction`

- `ElectionStarted` — broadcast when election starts
- `ElectionEnded` — broadcast when election ends
- `ResultsPublished` — broadcast when results published
- `CandidateApproved` — broadcast when candidate approved
- `CandidateRejected` — broadcast when candidate rejected
- `VoteCast` — broadcast when vote cast

### Listeners

Create using: `php artisan make:listener {Name} --no-interaction`

- `SendElectionStartedNotifications` — listens to ElectionStarted
- `SendElectionEndedNotifications` — listens to ElectionEnded
- `SendResultsPublishedNotifications` — listens to ResultsPublished
- `SendCandidateApprovedNotification` — listens to CandidateApproved
- `SendCandidateRejectedNotification` — listens to CandidateRejected
- `SendVoteConfirmationNotification` — listens to VoteCast

### Register in `app/Providers/EventServiceProvider.php` (or use attribute-based discovery in Laravel 13)

## Scheduled Commands for Reminders

### SendElectionReminders

**File:** `app/Console/Commands/SendElectionReminders.php`

```php
// Runs every hour
// Finds active elections ending in 24h or 1h
// Sends reminders to eligible voters who haven't voted
```

### Schedule

```php
Schedule::command('notifications:election-reminders')->hourly();
```

## Notification Dropdown UI

In dashboard layout top bar:
- Bell icon with unread count badge
- Dropdown showing recent notifications
- "Mark all as read" button
- "View all" link to full notifications page
- Auto-fetch via JS every 60 seconds

## Email Templates

Create Mail views in `resources/views/emails/`:

1. `election-started.blade.php`
2. `election-ending.blade.php`
3. `results-published.blade.php`
4. `candidate-approved.blade.php`
5. `candidate-rejected.blade.php`
6. `vote-confirmation.blade.php`
7. `password-reset.blade.php`
8. `email-verification.blade.php`

Each email template should:
- Use a clean, branded email layout (`resources/views/emails/layout.blade.php`)
- Include Cornelect logo (text-based)
- Use navy and orange colors
- Have clear CTA buttons
- Include unsubscribe/settings link

## Tests

### NotificationTest

- `it sends election started notification to eligible voters`
- `it sends election ending reminder to non-voters`
- `it sends results published notification`
- `it sends candidate approved notification`
- `it sends candidate rejected notification with reason`
- `it sends vote confirmation with verification code`
- `it creates in-app notification`
- `it can mark notification as read`
- `it can mark all as read`
- `it gets unread count`
- `it sends to multiple users`
- `it sends to eligible voters only`
- `reminder command sends to non-voters only`

## Do NOT proceed until:
- [ ] All 10 notification classes created
- [ ] NotificationService created
- [ ] 6 events + 6 listeners created and registered
- [ ] SendElectionReminders command created and scheduled
- [ ] Notification dropdown UI in dashboard layout
- [ ] 8 email templates created
- [ ] Email layout created
- [ ] All 13 Pest tests pass
- [ ] Pint formatting passes
