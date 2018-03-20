<?php

namespace ctf0\SimpleMenu\Middleware;

use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

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
        if (!array_filter($roles)) {
            return $next($request);
        }

        if (auth()->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (!$request->user()->load('roles')->hasAnyRole($roles)) {
            throw UnauthorizedException::forRoles($roles);
        }

        return $next($request);
    }
}
