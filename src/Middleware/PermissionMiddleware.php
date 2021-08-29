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
    public function handle($request, Closure $next, ...$permission)
    {
        $permission = $permission ? str_replace(' ', '', $permission) : null;

        foreach ($permission as $item) {
            if (!$request->user()->can(trim($item, ' '))) {
                abort(403, trans('acl::acl.permission_denied'));
            }
        }

        return $next($request);
    }
}
