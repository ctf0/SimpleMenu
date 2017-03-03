<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param mixed                    $role
     * @param mixed                    $permission
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$role)
    {
        if (in_array('guest', $role)) {
            return $next($request);
        }

        if (!$request->user()->hasAnyRole(...$role)) {
            abort(403);
        }

        return $next($request);
    }
}
