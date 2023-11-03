<?php
/**
 * @author Həmid Musəvi <w1w@yahoo.com>
 * @date   8/28/21
 */

namespace MirHamit\ACL;

use Illuminate\Support\Facades\Cache;
use MirHamit\ACL\Models\Permission;
use MirHamit\ACL\Models\Role;

trait HasPermissions
{

    public function syncPermission(array $permissions)
    {

        $this->permissions()->sync($permissions);
    }
    public function syncRole(array $roles)
    {

        $this->roles()->sync($roles);
    }

    public function givePermissionsTo(...$permissions)
    {

        $permissions = $this->getAllPermissions($permissions);

        if ($permissions === null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);

        return $this;
    }

    public function withdrawPermissionsTo(...$permissions)
    {

        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);

        return $this;
    }

    public function refreshPermissions(...$permissions)
    {

        $this->permissions()->detach();

        return $this->givePermissionsTo($permissions);
    }

    public function hasPermissionTo($permission)
    {

        return $this->hasPermissionThroughRole($permission);
    }

    public function hasPermissionThroughRole($permission)
    {
        $allPermissions = Cache::rememberForever('permission', function () {
            return Permission::all();
        });
        $permission = $allPermissions->where('slug', $permission)->first();
        //        $permission = Permission::where('slug', $permission)->get()->first();
        if (!$permission) {
            return false;
        }
//        $allPermissionRoles = Cache::rememberForever('allPermissionRoles', function () use ($permission) {
//            return $permission->roles;
//        });
//        dd($permission, $this->roles[0]->permissions->contains($permission));
        foreach ($this->roles as $role) {
            if ($role->permissions->contains($permission)) {
                return true;
            }
        }

        return false;
    }

    public function hasRole($requestedRoles)
    {
        if (is_array($requestedRoles)) {
            foreach ($requestedRoles as $requestedRole) {
                if (!$this->roles->contains('slug', $requestedRole)) {
                    return false;
                }
            }

            return true;
        } else {
            return $this->roles->contains('slug', $requestedRoles);
        }
    }

    public function roles()
    {

        return $this->belongsToMany(Role::class, 'role_user', 'user_id');
    }

    public function permissions()
    {

        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id');
    }

    public function hasPermission($permissions): bool
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if ((bool) !$this->permissions->where('slug',
                        $permission)->count() && !$this->hasPermissionTo($permission)) {
                    return false;
                }
            }
            return true;
        } else {
            return $this->permissions->where('slug', $permissions)->count() || $this->hasPermissionTo($permissions);
        }
    }

    protected function getAllPermissions(array $permissions)
    {

        return Permission::whereIn('slug', $permissions)->get();
    }
}
