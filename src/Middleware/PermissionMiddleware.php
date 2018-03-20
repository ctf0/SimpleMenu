<?php

namespace ctf0\SimpleMenu\Middleware;

use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

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
        if (!array_filter($permissions)) {
            return $next($request);
        }

        if (auth()->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (!$request->user()->load('permissions')->hasAnyPermission($permissions)) {
            throw UnauthorizedException::forPermissions($permissions);
        }

        return $next($request);
    }
}
