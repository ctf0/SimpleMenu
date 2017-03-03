<?php

namespace App\Http\Middleware;

use Closure;

class PermissionMiddleware
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
    public function handle($request, Closure $next, ...$permission)
    {
        if (in_array('guest', $permission)) {
            return $next($request);
        }

        if (!$request->user()->hasAnyPermission(...$permission)) {
            abort(403);
        }

        return $next($request);
    }
}
