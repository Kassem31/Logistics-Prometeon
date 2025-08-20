<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\MaterialGroup;
use App\Traits\HasFilter;
use Laratrust\Traits\HasRolesAndPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRolesAndPermissions;
    use Notifiable;
    use HasFilter;

    public $display_name = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'is_super_admin' => 'boolean'
    ];

    public function materialGroups()
    {
        return $this->belongsToMany(MaterialGroup::class, 'material_group_user', 'user_id', 'material_group_id');
    }

    public function getAvatarAttribute($value)
    {
        return is_null($value) ? asset('assets/media/users/default.jpg') : asset('storage/' . $value);
    }

    public function getAvatarStorageUrl()
    {
        return $this->attributes['avatar'];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    // public function permissions()
    // {
    //     return $this->hasManyThrough(Permission::class, Role::class);
    // }

    /**
     * Attach a role to the user.
     *
     * @param  int  $roleId
     * @return void
     */
    public function attachRole($roleId)
    {
        $this->roles()->attach($roleId, ['user_type' => 'App\User']);
    }

    /**
     * Sync user roles.
     *
     * @param  array  $roleIds
     * @return void
     */
    public function syncRole(array $roleIds)
    {
        $syncData = [];
        foreach ($roleIds as $roleId) {
            $syncData[$roleId] = ['user_type' => 'App\Models\User'];
        }
        $this->roles()->sync($syncData);
    }

    /**
     * Check if user has a specific permission by ID.
     *
     * @param  int  $permissionId
     * @return bool
     */
    public function hasPermissionID($permissionId)
    {
        if (empty($permissionId)) {
            return true;
        }

        return $this->roles()->whereHas('permissions', function ($query) use ($permissionId) {
            $query->where('permissions.id', $permissionId);
        })->exists();
    }
}
