<?php

namespace App\Http\Controllers;

use App\Models\MaterialGroup;
use Illuminate\Http\Request;
use App\Models\User;

class MaterialGroupController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,MaterialGroup::class);
        $items = MaterialGroup::with('users')->paginate(30);
        return view('material-group.index',[
            'items'=>$items,
        ]);
    }

    public function create(){
        $this->authorize(__FUNCTION__,MaterialGroup::class);
        $users = User::whereNull('is_super_admin')->where('is_active',1)->orderBy('name')->get();
        return view('material-group.create',[
            'users'=>$users
        ]);
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,MaterialGroup::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token','users');
        $group = MaterialGroup::create($data);
        $group->users()->sync($request->input('users',[]));
        return redirect()->route('material-groups.index')->with('success','Material Group Created Successfully');

    }

    public function edit(MaterialGroup $material_group){
        $this->authorize(__FUNCTION__,MaterialGroup::class);
        $users = User::whereNull('is_super_admin')->where('is_active',1)->orderBy('name')->get();
        $modelUsers = $material_group->users()->pluck('id')->all();
        return view('material-group.edit',[
            'model'=>$material_group,
            'users'=>$users,
            'modelUsers'=>$modelUsers
        ]);
    }

    public function update(Request $request,MaterialGroup $material_group){
        $this->authorize(__FUNCTION__,MaterialGroup::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token','users');
        $material_group->update($data);
        $material_group->users()->sync($request->input('users',[]));
        return redirect()->route('material-groups.index')->with('success','Material Group Updated Successfully');

    }

    protected function rules($is_update = false,$model = null){
        $rules =  [
            'name'=>'required',
        ];
        if($is_update){
            $rules = array_merge($rules, [

            ]);
        }
        return $rules;
    }
}
