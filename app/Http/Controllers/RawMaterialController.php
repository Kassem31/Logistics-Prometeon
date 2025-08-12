<?php

namespace App\Http\Controllers;

use App\Models\MaterialGroup;
use App\Models\RawMaterial;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,RawMaterial::class);
        $items = RawMaterial::with('materialGroup')->paginate(30);
        return view('raw-material.index',[
            'items'=>$items
        ]);
    }
    public function create(){
        $this->authorize(__FUNCTION__,RawMaterial::class);
        $groups = MaterialGroup::orderBy('name')->get();
        return view('raw-material.create',[
            'groups'=>$groups
        ]);
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,RawMaterial::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token');
        RawMaterial::create($data);
        return redirect()->route('raw-materials.index')->with('success','Raw Material Created Successfully');

    }

    public function edit(RawMaterial $raw_material){
        $this->authorize(__FUNCTION__,RawMaterial::class);
        $groups = MaterialGroup::orderBy('name')->get();
        return view('raw-material.edit',[
            'item'=>$raw_material,
            'groups'=>$groups
        ]);
    }

    public function update(Request $request,RawMaterial $raw_material){
        $this->authorize(__FUNCTION__,RawMaterial::class);
        $this->validate($request,$this->rules(true,$raw_material));
        $data = $request->except('_token');
        $raw_material->update($data);
        return redirect()->route('raw-materials.index')->with('success','Raw Material Updated Successfully');

    }

    protected function rules($is_update = false,$model = null){
        $rules =  [
            'name'=>'required',
            'sap_code'=>'required|unique:raw_materials,sap_code',
            'material_group_id'=>'required'
        ];
        if($is_update){
            $rules = array_merge($rules, [
                'sap_code'=>'required|unique:raw_materials,sap_code,'.$model->id,
            ]);
        }
        return $rules;
    }
}
