<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'role',
    ];

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, PermissionRole::class);
    }

    public function rolePermissions()
    {
        return $this->hasMany(PermissionRole::class);
    }

    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();
        static::deleting(function($role) {
             $role->rolePermissions()->each(function($permission) {
                $permission->delete();
             });
        });
    }

}