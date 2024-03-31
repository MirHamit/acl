<?php

namespace MirHamit\ACL\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $role
     * @param null $permission
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $roles = $roles ? str_replace(' ', '', $roles) : null;
        if (!$request->user()) {
            abort(403, 'acl::acl.role_denied');
        }
        if (!$request->user()->hasRole($roles)) {
            abort(403, 'acl::acl.role_denied');
        }

        return $next($request);
    }
}
