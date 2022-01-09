<?php
/**
 * @author Həmid Musəvi <w1w@yahoo.com>
 * @date   8/28/21
 */

namespace MirHamit\ACL;

use MirHamit\ACL\Models\Permission;
use MirHamit\ACL\Models\Role;

trait HasPermissions
{

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
        $permission = Permission::where('slug', $permission)->get()->first();
        if (!$permission) {
            return false;
        }
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }

        return false;
    }

    public function hasRole($requestedRoles)
    {
        if (is_array($requestedRoles)) {
            foreach ($requestedRoles as $requestedRole) {
                if (
                    !$this->roles->contains('slug', $requestedRole) &&
                    !$this->hasParentRole($requestedRoles)
                ) {
                    return false;
                }
            }

            return true;
        } else {
            return $this->roles->contains('slug', $requestedRoles) || $this->hasParentRole($requestedRoles);
        }
    }

    public function hasParentRole($requestedRole)
    {
        $result = false;
        foreach ($this->roles as $role) {
            $parentRole = $role->parentRole();
            if ($parentRole != null) {
                if ($parentRole->contains('slug', $requestedRole)) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    public function roles()
    {

        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {

        return $this->belongsToMany(Permission::class);
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
