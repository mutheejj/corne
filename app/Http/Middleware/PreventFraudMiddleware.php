<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventFraudMiddleware
{
    private const MAX_REQUESTS_PER_MINUTE = 30;

    private const FLAGGED_IPS_KEY = 'fraud:flagged_ips';

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        $flaggedIps = cache()->get(self::FLAGGED_IPS_KEY, []);

        if (in_array($ip, $flaggedIps)) {
            AuditLog::log('fraud.blocked', "Blocked request from flagged IP: {$ip}");

            return response()->json(['error' => 'Access denied.'], 403);
        }

        $key = "fraud:requests:{$ip}";
        $count = cache()->get($key, 0);

        if ($count > self::MAX_REQUESTS_PER_MINUTE) {
            $flaggedIps[] = $ip;
            cache()->put(self::FLAGGED_IPS_KEY, $flaggedIps, now()->addHours(24));

            AuditLog::log('fraud.flagged', "IP flagged for suspicious activity: {$ip}");

            return response()->json(['error' => 'Too many requests. Access temporarily restricted.'], 429);
        }

        cache()->put($key, $count + 1, now()->addMinute());

        return $next($request);
    }
}
