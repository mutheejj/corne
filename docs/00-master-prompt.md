# Cornelect — Master Implementation Prompt

## Project Overview

Cornelect is a production-grade online voting platform for universities. It enables secure, anonymous, and verifiable student elections with role-based dashboards for voters, candidates, and administrators.

**Stack:** Laravel 13, PHP 8.5, SQLite (dev) / PostgreSQL/MySQL (prod), Tailwind CSS (CDN), Vanilla JS, Blade templates, Pest v4

## Architecture

```
corne/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Web controllers
│   │   └── Middleware/         # Custom middleware
│   ├── Models/                 # Eloquent models
│   ├── Services/               # Business logic services
│   ├── Notifications/          # Email/in-app notifications
│   └── Policies/               # Authorization policies
├── database/
│   ├── migrations/             # All schema migrations
│   ├── factories/              # Model factories
│   └── seeders/                # Database seeders
├── resources/views/
│   ├── layouts/                # app.blade.php, auth.blade.php, dashboard.blade.php
│   ├── partials/               # Reusable components
│   ├── pages/                  # Public pages (home, about, etc.)
│   ├── auth/                   # Auth views
│   └── dashboard/              # Dashboard views (admin, voter, candidate)
├── routes/
│   └── web.php                 # All web routes
├── public/
│   ├── css/app.css             # Custom styles
│   ├── js/app.js               # Custom JS
│   └── images/                 # Static images
└── tests/
    ├── Feature/                # Feature tests (Pest)
    └── Unit/                   # Unit tests (Pest)
```

## User Roles

| Role | Description |
|------|-------------|
| `admin` | University staff managing elections, approving candidates, viewing audit logs |
| `voter` | Students who cast votes in elections |
| `candidate` | Students running for positions, managing campaign profiles |

## Agent Instructions

### CRITICAL RULES — READ BEFORE STARTING

1. **You are implementing a PRODUCTION system.** Every file must be complete, tested, and production-ready. No stubs, no TODOs, no placeholders.

2. **Do NOT stop until your task is fully implemented.** Write all code, all tests, all migrations. Run tests. Fix failures. Only stop when everything passes.

3. **Follow Laravel conventions.** Use Artisan commands to generate files. Use Eloquent ORM. Use Blade templating. Follow PSR-12 + Laravel Pint formatting.

4. **Use PHP 8.5 features.** Constructor property promotion, typed properties, enum-backed casts, readonly properties where appropriate.

5. **Run `vendor/bin/pint --dirty --format agent` after every PHP file change.**

6. **Run `php artisan test --compact` after implementing to verify tests pass.**

7. **Update `docs/PROGRESS.md`** with what you completed, what's left, and any blockers.

8. **Check `docs/PROGRESS.md` before starting** to see what other agents have completed — don't duplicate work.

9. **Use Pest v4 syntax** for all tests: `test()`, `it()`, `expect()`.

10. **Every controller method must have authorization** (policies or middleware).

11. **Every form must have validation** (Form Requests).

12. **Every model must have a factory** with appropriate states.

13. **Use route names** everywhere — never hardcode URLs.

14. **Tailwind CSS via CDN** — no build step. Custom CSS in `public/css/app.css`. Custom JS in `public/js/app.js`.

15. **Color scheme:** Navy (`#0a1628`, `#0f2942`) and Orange (`#f97316`, `#ea580c`).

### Execution Order

Agents should work in this order. Each agent picks the next uncompleted task from PROGRESS.md:

1. `01-database-schema-migrations.md` — Foundation (must be first)
2. `02-models-relationships.md` — Depends on 01
3. `03-authentication-system.md` — Depends on 01, 02
4. `04-middleware-policies.md` — Depends on 02
5. `05-admin-dashboard.md` — Depends on 02, 03, 04
6. `06-voter-dashboard.md` — Depends on 02, 03, 04
7. `07-candidate-dashboard.md` — Depends on 02, 03, 04
8. `08-election-management.md` — Depends on 02, 05
9. `09-voting-engine.md` — Depends on 02, 08
10. `10-results-analytics.md` — Depends on 09
11. `11-notifications.md` — Depends on 02, 03
12. `12-security-audit.md` — Depends on all
13. `13-api-routes.md` — Depends on all controllers
14. `14-frontend-views.md` — Depends on all
15. `15-testing-suite.md` — Depends on all
16. `16-deployment-production.md` — Final step

### How to Use This Documentation

1. Read your assigned numbered file completely.
2. Check `PROGRESS.md` for what's already done.
3. Implement everything in your file — don't skip anything.
4. Run tests after each major change.
5. Update `PROGRESS.md` when done.
6. Run Pint formatter before finishing.

### Existing Frontend

The public-facing frontend is already built:
- Home page with hero slider, stats, features, how-it-works, testimonials, FAQ, CTA
- Auth pages (login, register voter, register candidate, forgot/reset password, verify email)
- About, features, contact, privacy, terms pages
- Header, footer, layout partials
- Custom CSS (`public/css/app.css`) and JS (`public/js/app.js`)

All routes in `routes/web.php` currently use closures returning views. These need to be replaced with proper controllers.

### Database

Currently using SQLite (`database/database.sqlite`). The default Laravel migrations (users, cache, jobs) exist. The users table needs to be extended with role, student_id, faculty, department, etc.

### What Needs to Be Built

Everything backend:
- Database schema (15+ tables)
- Models with relationships
- Authentication system (multi-role)
- Middleware and policies
- Admin dashboard (election CRUD, candidate approval, results, audit)
- Voter dashboard (view elections, cast votes, verify votes)
- Candidate dashboard (campaign profile, view results)
- Election management (lifecycle, positions, ballots)
- Voting engine (encryption, anonymity, verification)
- Results and analytics (tallying, charts, export)
- Notifications (email, in-app)
- Security (audit logs, rate limiting)
- Tests (unit + feature)
- Production deployment config
