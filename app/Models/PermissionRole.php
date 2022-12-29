<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PermissionRole extends Model
{
    use HasFactory;

    protected $table = 'permission_role';

    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    public function perm()
    {
        return $this->belongsTo(Role::class);
    }

    public function rl()
    {
        return $this->belongsToMany(Permission::class, Role::class);
    }
}
