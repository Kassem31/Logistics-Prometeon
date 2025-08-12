<?php

namespace App\Http\Controllers;

use App\Models\CustomSystem;
use Illuminate\Http\Request;

class CustomSystemController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,CustomSystem::class);
        $items = CustomSystem::paginate(30);
        return view('custom-system.index',[
            'items'=>$items
        ]);
    }

    public function create(){
        $this->authorize(__FUNCTION__,CustomSystem::class);
        return view('custom-system.create');
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,CustomSystem::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token');
        CustomSystem::create($data);
        return redirect()->route('custom-systems.index')->with('success','Custom System Created Successfully');

    }

    public function edit(CustomSystem $custom_system){
        $this->authorize(__FUNCTION__,CustomSystem::class);
        return view('custom-system.edit',[
            'model'=>$custom_system
        ]);
    }

    public function update(Request $request,CustomSystem $custom_system){
        $this->authorize(__FUNCTION__,CustomSystem::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token');
        $custom_system->update($data);
        return redirect()->route('custom-systems.index')->with('success','Custom System Updated Successfully');

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
