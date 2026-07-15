<?php

namespace App\Http\Middleware;

use App\Models\Election;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ElectionActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $election = $request->route('election');

        if ($election instanceof Election && $election->status !== 'active') {
            abort(403, 'This election is not currently active.');
        }

        return $next($request);
    }
}
