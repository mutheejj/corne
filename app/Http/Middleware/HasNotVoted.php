<?php

namespace App\Http\Middleware;

use App\Models\Position;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasNotVoted
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $position = $request->route('position');

        if ($position instanceof Position && $request->user()->hasVotedForPosition($position)) {
            abort(403, 'You have already voted for this position.');
        }

        return $next($request);
    }
}
