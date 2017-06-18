<?php

namespace ctf0\SimpleMenu\Middleware;

use Closure;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param [type]  $request     [description]
     * @param Closure $next        [description]
     * @param [type]  $permissions [description]
     *
     * @return [type] [description]
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
