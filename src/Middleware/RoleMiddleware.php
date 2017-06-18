<?php

namespace ctf0\SimpleMenu\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param [type]  $request [description]
     * @param Closure $next    [description]
     * @param [type]  $roles   [description]
     *
     * @return [type] [description]
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
