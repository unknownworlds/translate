<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $requiredRole
     * @return mixed
     */
    public function handle($request, Closure $next, $requiredRole = null)
    {

        if (Auth::guest() || ($requiredRole != null && !Auth::user()->hasRole($requiredRole))) {
            if (Auth::guest())
                abort(401, 'Unauthorized action.');

            abort(403, 'Forbidden.');
        }

        return $next($request);
    }
}
