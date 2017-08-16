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
    public function handle($request, Closure $next, ...$permissions)
    {
        if (empty(array_filter($permissions))) {
            return $next($request);
        }

        if (!$request->user()->hasAnyPermission(...$permissions)) {
            abort(403);
        }

        return $next($request);
    }
}
