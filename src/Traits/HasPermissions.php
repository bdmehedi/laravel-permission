<?php


namespace BdMehedi\LaravelPermission\Traits;

use BdMehedi\LaravelPermission\Models\Permission;
use BdMehedi\LaravelPermission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

trait HasPermissions
{
    public function givePermissionTo(...$permissions)
    {
        $permissions = $this->getAllPermissions(Arr::flatten($permissions));

        if (!count($permissions)) {
            return $this;
        }

        $permissions = array_merge($this->permissions()->pluck('id')->toArray(), $permissions->pluck('id')->all());

        $this->permissions()->sync($permissions);

        $this->clearCache();

        return $this;
    }

    public function assignRole(...$roles)
    {
        $roles = $this->getAllRoles(Arr::flatten($roles));

        if (!count($roles)) {
            return $this;
        }
        $roles = array_merge($this->roles()->pluck('id')->toArray(), $roles->pluck('id')->all());

        $this->roles()->sync($roles);

        $this->clearCache();
    }

    public function withdrawPermissionTo(...$permissions)
    {
        $permissions = $this->getAllPermissions(Arr::flatten($permissions));
        $this->permissions()->detach($permissions);

        $this->clearCache();

        return $this;
    }

    public function revokeRole(...$roles)
    {
        $roles = $this->getAllRoles(Arr::flatten($roles));

        if (!count($roles)) {
            return $this;
        }

        $this->roles()->detach($roles);

        $this->clearCache();
    }

    public function updatePermissionTo(...$permissions)
    {
        $permissions = $this->getAllPermissions(Arr::flatten($permissions));
        $this->permissions()->sync($permissions);

        $this->clearCache();

        return $this;
    }

    public function hasRole(...$roles)
    {
        foreach ($roles as $role) {
            $roles = $this->roles;
            if ($roles->contains('name', $role) || $roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    public function hasPermissionTo($permission)
    {
        $permission = $permission instanceof Permission ? $permission : Permission::where('name', $permission)->first();
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    protected function hasPermissionThroughRole($permission)
    {
        $userRoles = Cache::remember($this->id . '-user-roles', now()->addDay(), function () {
            return $this->roles;
        });
        $roles = $permission->roles;
        foreach ($roles as $role) {
            foreach ($userRoles as $userRole) {
                if ($role->name == $userRole->name) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function hasPermission($permission)
    {
        $userPermissions = Cache::remember($this->id . '-user-permissions', now()->addDay(), function () {
            return $this->permissions;
        });
        return (bool)$userPermissions->where('name', $permission->name)->count();
    }

    protected function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('name', $permissions)->get();
    }

    protected function getAllRoles(array $roles)
    {
        return Role::whereIn('name', $roles)->get();
    }

    public function roles()
    {
        return $this->morphToMany(Role::class, 'roleable');
    }

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'permissionable');
    }

    public function clearCache()
    {
        Cache::forget($this->id . '-user-roles');
        Cache::forget($this->id . '-user-permissions');
        Cache::forget('permissions');
    }

}
