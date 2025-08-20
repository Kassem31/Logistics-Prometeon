<?php

namespace App\Models;

use Laratrust\Models\Role as LaratrustRole;

class Role extends LaratrustRole
{
    public $display_name = "roles";
    protected $guarded = [];

    public function usersCount()
    {
        return User::whereHas('roles', function ($q) {
            $q->where('name', $this->name);
        })->count();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }
}
