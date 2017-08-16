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
    public function handle($request, Closure $next, ...$roles)
    {
        if (empty(array_filter($roles))) {
            return $next($request);
        }

        if (!$request->user()->hasAnyRole(...$roles)) {
            abort(403);
        }

        return $next($request);
    }
}
