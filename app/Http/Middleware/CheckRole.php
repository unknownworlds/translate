<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $requiredRoles
     * @return mixed
     */
    public function handle($request, Closure $next, $requiredRoles = null)
    {
        if (Auth::guest() || ($requiredRoles != null && !$this->checkRole($requiredRoles))) {
            if (Auth::guest()) {
                abort(401, 'Unauthorized action.');
            }

            abort(403, 'Forbidden.');
        }

        return $next($request);
    }

    private function checkRole(string $requiredRoles)
    {
        $requiredRoles = explode('|', $requiredRoles);

        foreach ($requiredRoles as $requiredRole) {
            if (Auth::user()->hasRole($requiredRole)) {
                return true;
            }
        }

        return false;
    }
}
