<?php
/*
 * *
 *  * Created By: Hamid Musavi
 *  * w1w@yahoo.com
 *
 */


namespace MirHamit\ACL\Middleware;

use Closure;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $permission
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        $permission = $permissions ? str_replace(' ', '', $permissions) : null;
        if (!$request->user()) {
            abort(403, trans('acl::acl.permission_denied'));
        }
        if (!$request->user()->hasPermission($permission)) {
            abort(403, trans('acl::acl.permission_denied'));
        }

        return $next($request);
    }
}
