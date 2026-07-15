# 10 — Results & Analytics

## Overview

Results tallying, display, charts, export functionality, and audit reporting.

## Execution Instructions

1. Create ResultsService
2. Implement results views with charts
3. Create export functionality (PDF, CSV)
4. Run tests
5. Run `vendor/bin/pint --dirty --format agent`
6. Update `docs/PROGRESS.md`

## ResultsService

**File:** `app/Services/ResultsService.php`

```php
class ResultsService
{
    public function getElectionResults(Election $election): array
    public function getPositionResults(Position $position): array
    public function getCandidateResult(Candidate $candidate): array
    public function getElectionTurnout(Election $election): array
    public function getVoteTimeline(Election $election): array
    public function exportResultsCsv(Election $election): string
    public function exportResultsPdf(Election $election): string
    public function generateAuditReport(Election $election): array
    public function getLiveResults(Election $election): array
}
```

### Return Structures

**getElectionResults():**
```php
[
    'election' => Election,
    'total_voters' => int,
    'total_votes_cast' => int,
    'turnout_percentage' => float,
    'positions' => [
        [
            'position' => Position,
            'total_votes' => int,
            'abstentions' => int,
            'candidates' => [
                [
                    'candidate' => Candidate,
                    'vote_count' => int,
                    'vote_percentage' => float,
                    'rank' => int,
                    'is_winner' => bool,
                ],
            ],
        ],
    ],
]
```

**getVoteTimeline():**
```php
[
    ['timestamp' => '2025-01-01 10:00', 'votes' => 15],
    ['timestamp' => '2025-01-01 11:00', 'votes' => 42],
    ...
]
```

**generateAuditReport():**
```php
[
    'election' => Election,
    'total_votes' => int,
    'total_vote_records' => int,
    'discrepancies' => bool, // vote count != vote_record count
    'audit_logs' => Collection,
    'timeline' => array,
    'integrity_check' => bool, // all receipt hashes valid
]
```

## Views

### Admin Results View

**File:** `resources/views/dashboard/admin/elections/results.blade.php`

- Election header with status, dates, turnout
- For each position:
  - Position title
  - Bar chart showing vote counts per candidate (Chart.js via CDN)
  - Table: rank, candidate name, vote count, percentage, winner badge
  - Abstention count
- Export buttons: Download CSV, Download PDF, Print
- Audit report section (collapsible)
- Live results toggle (if show_results_live = true)

### Voter Results View

**File:** `resources/views/dashboard/voter/results.blade.php`

- Simplified results view
- Only shows published results
- Bar charts per position
- No audit data
- No export functionality

### Candidate Results View

**File:** `resources/views/dashboard/candidate/results.blade.php`

- Shows results for own position only
- Own rank, vote count, percentage
- Competitor results (if published)
- Win/loss banner

## Charts (Chart.js via CDN)

Add Chart.js CDN to dashboard layout:
```html
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
```

### Chart Types

1. **Bar Chart** — vote counts per candidate per position
2. **Doughnut Chart** — voted vs not voted (turnout)
3. **Line Chart** — votes cast over time (timeline)
4. **Stacked Bar** — votes by faculty/department breakdown (admin only)

## Export

### CSV Export

Generate CSV string with:
- Election title, dates, status
- Per position: candidate name, vote count, percentage
- Total turnout

### PDF Export

Use `barryvdh/laravel-dompdf` or generate printable HTML page:
- Create `resources/views/dashboard/admin/elections/results-pdf.blade.php`
- Clean print-friendly layout
- Trigger via `window.print()` JS or server-side PDF generation

## Live Results

If `election_settings.show_results_live = true`:
- Results page auto-refreshes every 30 seconds
- Shows live vote counts (without revealing individual votes)
- Shows live turnout percentage
- JS polling via `setInterval` + fetch

## Tests

### ResultsAnalyticsTest

- `it can get election results`
- `it can get position results`
- `it calculates vote percentages correctly`
- `it determines winner correctly`
- `it handles ties in results`
- `it calculates turnout percentage`
- `it generates vote timeline`
- `it can export results as csv`
- `it can generate audit report`
- `it detects vote count discrepancies in audit`
- `it shows published results to voters`
- `it hides unpublished results from voters`
- `it shows live results when enabled`
- `it shows results to candidate for own position only`
- `it ranks candidates correctly`

## Do NOT proceed until:
- [ ] ResultsService with all methods
- [ ] Results views created (admin, voter, candidate)
- [ ] Chart.js integrated with charts rendering
- [ ] CSV export working
- [ ] Print/PDF export working
- [ ] Live results polling working
- [ ] Audit report generation working
- [ ] All 15 Pest tests pass
- [ ] Pint formatting passes
