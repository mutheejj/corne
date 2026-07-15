# 16 — Deployment & Production

## Overview

Production deployment configuration, optimization, environment setup, and deployment checklist.

## Execution Instructions

1. Configure production environment
2. Set up optimization commands
3. Create deployment checklist
4. Configure error handling
5. Run `vendor/bin/pint --dirty --format agent`
6. Update `docs/PROGRESS.md`

## Environment Configuration

### `.env` Production Settings

```env
APP_NAME=Cornelect
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cornelect.ac.ke

APP_KEY=base64:GENERATED_KEY

# Database (use PostgreSQL or MySQL in production)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cornelect
DB_USERNAME=cornelect_user
DB_PASSWORD=SECURE_PASSWORD

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Cache
CACHE_STORE=redis
QUEUE_CONNECTION=redis

# Redis (for cache, queue, sessions)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=REDIS_PASSWORD
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=notifications@cornelect.ac.ke
MAIL_PASSWORD=APP_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=notifications@cornelect.ac.ke
MAIL_FROM_NAME=Cornelect

# Filesystem
FILESYSTEM_DISK=public

# Logging
LOG_CHANNEL=stack
LOG_STACK=single,daily
LOG_LEVEL=error
```

## Optimization Commands

Run during deployment:

```bash
# Clear all caches
php artisan optimize:clear

# Cache config, routes, events, views
php artisan config:cache
php artisan route:cache
php artisan event:cache
php artisan view:cache

# Optimize autoloader
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Cache settings
php artisan settings:cache
```

## Production Middleware

Ensure in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    // Trust proxies (for reverse proxy / load balancer)
    $middleware->trustProxies(at: '*');

    // Security headers
    $middleware->appendToGroup('web', [
        \App\Http\Middleware\SecurityHeaders::class,
    ]);

    // Prevent requests during maintenance (except from specific IPs)
    $middleware->preventRequestsDuringMaintenance(except: [
        'admin-ip-range/*',
    ]);
})
```

## Scheduler Setup

Add to crontab:
```bash
* * * * * cd /path/to/cornelect && php artisan schedule:run >> /dev/null 2>&1
```

Or use Laravel Cloud's managed scheduler.

## Queue Worker Setup

Using Supervisor:
```ini
[program:cornelect-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/cornelect/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/cornelect/storage/logs/worker.log
stopwaitsecs=3600
```

## File Permissions

```bash
chmod -R 755 /path/to/cornelect
chmod -R 775 /path/to/cornelect/storage
chmod -R 775 /path/to/cornelect/bootstrap/cache
chmod -R 775 /path/to/cornelect/public/images
chown -R www-data:www-data /path/to/cornelect/storage
chown -R www-data:www-data /path/to/cornelect/bootstrap/cache
```

## Web Server Configuration

### Nginx

```nginx
server {
    listen 80;
    server_name cornelect.ac.ke;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name cornelect.ac.ke;

    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/key.pem;
    ssl_protocols TLSv1.2 TLSv1.3;

    root /path/to/cornelect/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.5-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

## SSL Configuration

- Use Let's Encrypt (certbot) for free SSL
- Force HTTPS redirect
- HSTS header: `Strict-Transport-Security: max-age=31536000; includeSubDomains; preload`

## Backup Strategy

### Database Backup
```bash
# Daily backup via cron
0 2 * * * pg_dump cornelect | gzip > /backups/cornelect_$(date +\%Y\%m\%d).sql.gz

# Keep 30 days of backups
0 3 * * * find /backups -name "cornelect_*.sql.gz" -mtime +30 -delete
```

### Application Backup
- Git repository (source code)
- `.env` file (encrypted backup)
- `storage/app/public` (uploaded files)
- `public/images` (static images)

## Monitoring

### Health Checks

**File:** `app/Http/Controllers/HealthController.php`

```php
Route::get('/health', [HealthController::class, 'check']);
```

Checks:
- Database connection
- Redis connection
- Storage writable
- Queue worker running
- Disk space

### Error Tracking

- Configure `config/logging.php` with daily + error tracking service
- Set up Slack/email alerts for critical errors
- Monitor failed jobs: `php artisan queue:failed`

## Deployment Checklist

### Pre-Deployment
- [ ] All tests pass (`php artisan test --compact`)
- [ ] Pint formatting passes (`vendor/bin/pint --test`)
- [ ] No debug code (`dd()`, `dump()`, `var_dump()`)
- [ ] No hardcoded credentials
- [ ] `.env` file configured for production
- [ ] `APP_DEBUG=false`
- [ ] SSL certificate valid
- [ ] Database backup taken

### Deployment
- [ ] `git pull origin main`
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] `php artisan migrate --force`
- [ ] `php artisan optimize:clear`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan event:cache`
- [ ] `php artisan view:cache`
- [ ] Restart queue workers
- [ ] Restart scheduler
- [ ] Clear OPcache

### Post-Deployment
- [ ] Verify home page loads
- [ ] Verify login works
- [ ] Verify registration works
- [ ] Verify dashboards load for each role
- [ ] Verify voting flow end-to-end
- [ ] Verify email sending works
- [ ] Check error logs for new errors
- [ ] Monitor for 30 minutes

## Production Security Checklist

- [ ] `APP_DEBUG=false`
- [ ] Strong `APP_KEY` (32+ characters)
- [ ] Database uses strong password
- [ ] Redis uses password
- [ ] SMTP uses app password (not user password)
- [ ] HTTPS enforced
- [ ] HSTS header set
- [ ] Security headers middleware active
- [ ] Rate limiting configured
- [ ] CSRF protection on all forms
- [ ] File permissions correct
- [ ] `.env` file not publicly accessible
- [ ] `storage/` and `bootstrap/cache/` not publicly accessible
- [ ] Error messages don't expose sensitive info
- [ ] Audit logging active
- [ ] Backups encrypted

## Laravel Cloud Deployment

For fastest deployment:
```bash
# Deploy to Laravel Cloud
git push origin main
# Cloud auto-deploys with zero-downtime
```

Configure in Laravel Cloud dashboard:
- Set environment variables
- Enable scheduler
- Enable queue workers
- Set up SSL (automatic)
- Configure custom domain

## Do NOT proceed until:
- [ ] Production `.env` template documented
- [ ] Optimization commands documented
- [ ] Nginx config documented
- [ ] Supervisor config documented
- [ ] Backup strategy documented
- [ ] Health check endpoint created
- [ ] Deployment checklist complete
- [ ] Security checklist complete
- [ ] Pint formatting passes
