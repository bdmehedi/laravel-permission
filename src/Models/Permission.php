<?php


namespace BdMehedi\LaravelPermission\Models;


use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'group'];

    public function roles()
    {
        return $this->morphedByMany(Role::class, 'permissionable');
    }
}