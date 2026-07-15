# 14 — Frontend Views

## Overview

All Blade views for dashboards, shared components, and frontend interactions. Public-facing pages already exist — this covers dashboard views and enhancements.

## Execution Instructions

1. Create dashboard layout
2. Create all dashboard partials and views
3. Add JS interactions for dashboards
4. Ensure responsive design
5. Run `vendor/bin/pint --dirty --format agent`
6. Update `docs/PROGRESS.md`

## Dashboard Layout

**File:** `resources/views/layouts/dashboard.blade.php`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.head')
    {{-- Tailwind CDN with same config as app.blade.php --}}
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('partials.dashboard.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 lg:ml-64">
            {{-- Top Bar --}}
            @include('partials.dashboard.topbar')

            {{-- Page Content --}}
            <main class="p-6 lg:p-8">
                @yield('dashboard-content')
            </main>
        </div>
    </div>

    {{-- Mobile Sidebar Overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden"></div>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
```

## Dashboard Partials

### Sidebar

**File:** `resources/views/partials/dashboard/sidebar.blade.php`

- Fixed left sidebar, 256px wide
- Navy gradient background (`gradient-navy`)
- Cornelect logo at top (text only, no icon)
- Navigation items based on role:
  - Admin: Dashboard, Elections, Voters, Audit Logs, Security Report, Settings
  - Voter: Dashboard, My Elections, Vote History, Verify Vote, Profile
  - Candidate: Dashboard, My Profile, My Election, My Position, Results
- Active link highlighted with orange left border
- User info at bottom (name, role badge, logout button)
- Responsive: hidden on mobile, toggled by hamburger in topbar

### Topbar

**File:** `resources/views/partials/dashboard/topbar.php`

- White background with subtle shadow
- Hamburger menu (mobile only)
- Page title (dynamic from `@yield('page-title')`)
- Notification bell with unread badge
- Notification dropdown (fetches via JS)
- User avatar/name with dropdown menu (profile, logout)

### Stat Card

**File:** `resources/views/partials/dashboard/stat-card.blade.php`

Reusable component:
```blade
@props(['label', 'value', 'icon', 'color' => 'orange', 'trend' => null])

<div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100">
    <div class="flex items-center justify-between mb-4">
        <span class="text-slate-500 text-sm font-medium">{{ $label }}</span>
        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-{{ $color }}-50">
            {!! $icon !!}
        </div>
    </div>
    <p class="text-3xl font-extrabold text-navy-950">{{ $value }}</p>
    @if($trend)
        <p class="text-{{ $color }}-600 text-sm mt-2">{{ $trend }}</p>
    @endif
</div>
```

### Data Table

**File:** `resources/views/partials/dashboard/data-table.blade.php`

Reusable table with:
- Column headers
- Sortable columns
- Pagination
- Empty state
- Loading state

### Status Badge

**File:** `resources/views/partials/dashboard/status-badge.blade.php`

```blade
@props(['status'])

@php
    $colors = [
        'draft' => 'slate',
        'scheduled' => 'blue',
        'active' => 'green',
        'paused' => 'yellow',
        'completed' => 'navy',
        'cancelled' => 'red',
        'pending' => 'yellow',
        'approved' => 'green',
        'rejected' => 'red',
        'disqualified' => 'red',
    ];
    $color = $colors[$status] ?? 'slate';
@endphp

<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-700">
    <span class="w-1.5 h-1.5 rounded-full bg-{{ $color }}-500"></span>
    {{ ucfirst($status) }}
</span>
```

## Admin Views

Create in `resources/views/dashboard/admin/`:

### `dashboard.blade.php`
- 6 stat cards in grid (active elections, pending candidates, total voters, votes today, upcoming, completed)
- Voter turnout doughnut chart
- Votes over time line chart
- Election status bar chart
- Recent activity feed (audit logs)

### `elections/index.blade.php`
- Filter bar (status dropdown, type dropdown, search input)
- Data table: title, type, status badge, dates, turnout, actions
- "Create Election" button
- Pagination

### `elections/create.blade.php`
- Form: title, description, type, faculty (conditional), department (conditional), starts_at, ends_at, is_anonymous, require_2fa
- Settings section: allow_abstain, show_results_live, show_vote_count, require_student_id_verification
- "Create Election" submit button

### `elections/show.blade.php`
- Election header with status badge, dates, turnout
- Tab navigation: Overview, Positions, Candidates, Voters, Results, Audit
- Overview tab: stats, timeline chart
- Positions tab: list with add/edit/delete
- Candidates tab: list with approve/reject/disqualify actions
- Voters tab: list with add/remove
- Results tab: charts and tables (if published)
- Audit tab: audit log table

### `elections/edit.blade.php`
- Same as create but pre-filled

### `candidates/show.blade.php`
- Candidate photo, name, slogan
- Manifesto full text
- Position and election info
- Status badge
- Approve/Reject/Disqualify buttons (if pending)

### `voters/index.blade.php`
- Search bar
- Table: name, student_id, email, faculty, department, elections voted in
- Pagination

### `voters/show.blade.php`
- Voter info card
- Voting history table (election, position, voted_at, verification code)
- Elections eligible for

### `audit-logs/index.blade.php`
- Filters: action, user, date range
- Table: timestamp, user, action, description, model
- Pagination

### `settings/index.blade.php`
- System settings form (site name, registration open, default election settings)

### `security-report.blade.php`
- Security status cards
- Vote integrity check results
- Recent failed logins
- Rate limit activity

## Voter Views

Create in `resources/views/dashboard/voter/`:

### `dashboard.blade.php`
- 3 stat cards: available elections, votes cast, pending elections
- Active elections list with "Vote Now" buttons
- Recent vote history (last 5)

### `elections/index.blade.php`
- Filter by status (active, completed)
- Election cards with status, dates, positions count, vote progress
- "View" and "Vote" buttons

### `elections/show.blade.php`
- Election header with status, dates, description
- Positions list with vote status per position
- "Vote" button (if not voted, election active)
- "Voted" badge (if voted)
- "View Results" button (if published)

### `ballot.blade.php`
- Position title and description
- Candidate cards: photo, name, slogan, "View Manifesto" expandable
- Radio selection per candidate
- "Abstain" option (if allowed)
- "Cast Vote" button with confirmation modal
- Timer (if time limit set)

### `vote-confirmation.blade.php`
- Success icon
- "Your vote has been cast"
- Verification code (large, monospace, copyable)
- Receipt hash (monospace, copyable)
- "Save these for your records" warning
- "Download Receipt" button (print-friendly)
- "Back to Election" link

### `verify-vote.blade.php`
- Form with verification code input
- "Verify" button

### `verify-result.blade.php`
- If valid: green checkmark, election title, position title, cast_at, "Your vote was counted"
- If invalid: red X, "Verification code not found"

### `vote-history.blade.php`
- Table: election, position, voted_at, verification code, receipt hash
- "Verify" link per row

### `results.blade.php`
- Election title and status
- Per position: bar chart, results table (rank, candidate, votes, percentage)
- Winner highlighted

### `profile.blade.php`
- Profile form: name, email (readonly), phone, faculty, department, course, year_of_study
- Avatar upload
- "Save Changes" button

## Candidate Views

Create in `resources/views/dashboard/candidate/`:

### `dashboard.blade.php`
- Status banner (pending/approved/rejected/disqualified)
- Election info card
- Position info card
- Competitor count
- Quick actions: Edit Profile, View Election

### `profile.blade.php`
- Form: manifesto_title, manifesto (textarea with character count), slogan, photo upload
- Live preview of candidate card
- "Save Changes" button

### `election.blade.php`
- Election details, timeline, status
- Position info
- List of approved competitors

### `position.blade.php`
- Position title and description
- All approved candidates with photos and slogans
- Vote results (if published)

### `results.blade.php`
- Own rank, vote count, percentage
- Win/loss banner
- Full results table for position
- Bar chart

### `withdraw.blade.php`
- Warning message
- Confirmation checkbox
- "Withdraw Candidacy" button

## JS Interactions to Add

Add to `public/js/app.js`:

```javascript
// ===== Dashboard Sidebar Toggle (Mobile) =====
// Toggle sidebar open/close on mobile

// ===== Notification Dropdown =====
// Fetch unread count every 60s
// Show dropdown on click
// Mark as read via AJAX

// ===== Confirmation Modal =====
// Reusable confirmation modal for destructive actions

// ===== Copy to Clipboard =====
// For verification codes and receipt hashes

// ===== Chart Rendering =====
// Helper functions for Chart.js charts

// ===== Form Submit Loading State =====
// Disable button + show spinner on form submit

// ===== Auto-refresh Live Results =====
// Poll results page every 30s if live results enabled

// ===== Ballot Timer =====
// Countdown timer for voting time limit

// ===== Manifesto Character Counter =====
// Live character count for manifesto textarea

// ===== File Upload Preview =====
// Preview image before upload (avatar, candidate photo)
```

## Tests

### FrontendViewTest

- `it renders admin dashboard view`
- `it renders voter dashboard view`
- `it renders candidate dashboard view`
- `it renders ballot view with candidates`
- `it renders vote confirmation with code`
- `it renders results view with chart data`
- `it shows correct sidebar per role`
- `it shows notification bell in topbar`

## Do NOT proceed until:
- [ ] Dashboard layout created
- [ ] Sidebar and topbar partials created
- [ ] All reusable components created (stat-card, data-table, status-badge)
- [ ] All 14 admin views created
- [ ] All 10 voter views created
- [ ] All 6 candidate views created
- [ ] All JS interactions added
- [ ] Responsive on mobile
- [ ] All 8 Pest tests pass
- [ ] Pint formatting passes
