<?php


namespace BdMehedi\LaravelPermission\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Role extends Model
{
    protected $fillable = ['role'];

    public function users()
    {
        return $this->hasMany(config('roles_and_permissions.user_model'));
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions', 'role_id', 'permission_id');
    }

    public function givePermissionTo(...$permissions)
    {
        $permissions = $this->getAllPermissions(Arr::flatten($permissions));

        if (!count($permissions)) {
            return $this;
        }

        $permissions = array_merge($this->permissions()->pluck('id')->toArray(), $permissions->pluck('id')->all());

        $this->permissions()->sync($permissions);

        return $this;
    }

    public function withdrawPermissionTo(...$permissions)
    {
        $permissions = $this->getAllPermissions(Arr::flatten($permissions));
        $this->permissions()->detach($permissions);
        return $this;
    }

    protected function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('name', $permissions)->get();
    }
}
