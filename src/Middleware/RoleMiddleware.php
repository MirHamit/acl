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
    public function handle($request, Closure $next, $role, $permission = null)
    {
        $role = $role ? str_replace(' ', '', $role) : null;
        $permission = $permission ? str_replace(' ', '', $permission) : null;
        if (!$request->user()->hasRole($role)) {
            abort(403, 'acl::acl.role_denied');
        }

        if ($permission !== null && !$request->user()->hasPermissionTo($permission)) {
            abort(403, trans('acl::acl.role_permission_denied'));
        }

        return $next($request);
    }
}
