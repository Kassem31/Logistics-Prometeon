<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $display_name = "roles";
    protected $guarded = [];

    public function usersCount(){
        return User::whereHas('roles',function($q){
            $q->where('name',$this->name);
        })->count();
    }

    public function users(){
        return $this->belongsToMany(User::class,'role_user','role_id','user_id');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class,'permission_role','role_id','permission_id');
    }

    /**
     * Attach permissions to the role.
     *
     * @param  array  $permissionIds
     * @return void
     */
    public function attachPermissions(array $permissionIds)
    {
        $this->permissions()->attach($permissionIds);
    }

    /**
     * Sync role permissions.
     *
     * @param  array  $permissionIds
     * @return void
     */
    public function syncPermissions(array $permissionIds)
    {
        $this->permissions()->sync($permissionIds);
    }
}
