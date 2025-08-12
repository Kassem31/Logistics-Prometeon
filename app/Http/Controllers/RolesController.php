<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,Role::class);
        $roles = Role::with('permissions')->paginate(30);
        return view('roles.index',[
            'items'=>$roles
        ]);

    }

    public function create(){
        $this->authorize(__FUNCTION__,Role::class);
        $permissions = Permission::get()->groupBy('description');
        return view('roles.create',[
            'permissions'=>$permissions
        ]);
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,Role::class);
        $this->validate($request,$this->rules());
        $role = Role::create(['name'=>$request->input('name')]);
        $role->attachPermissions($request->input('permissions'));
        return redirect()->route('roles.index',['role'=>$role->id])->with('success','Roles Created Successfully');
    }

    public function edit(Role $role){
        $this->authorize(__FUNCTION__,Role::class);
        $permissions = Permission::get()->groupBy('description');
        return view('roles.edit',[
            'role'=>$role,
            'rolePermissions'=>$role->permissions->pluck('id')->all(),
            'permissions'=>$permissions
        ]);
    }

    public function update(Request $request,Role $role){
        $this->authorize(__FUNCTION__,Role::class);
        $this->validate($request,$this->rules(true,$role));
        $role->update(['name'=>$request->input('name')]);
        $role->syncPermissions($request->input('permissions'));
        return redirect()->route('roles.index',['role'=>$role->id])->with('success','Roles Updated Successfully');
    }

    protected function rules($is_update = false,$role = null){
        $rules =  [
            'name'=>'required|unique:roles,name'
        ];
        if($is_update){
            $rules = array_merge($rules, [
                'name'=>'required|unique:roles,name,'.$role->id,
            ]);
        }
        return $rules;
    }
}
