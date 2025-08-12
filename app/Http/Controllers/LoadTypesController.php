<?php

namespace App\Http\Controllers;

use App\Models\ContainerLoadType;
use Illuminate\Http\Request;

class LoadTypesController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,ContainerLoadType::class);
        $items = ContainerLoadType::paginate(30);
        return view('load-type.index',[
            'items'=>$items
        ]);
    }

    public function create(){
        $this->authorize(__FUNCTION__,ContainerLoadType::class);
        return view('load-type.create');
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,ContainerLoadType::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token');
        ContainerLoadType::create($data);
        return redirect()->route('load-types.index')->with('success','Container Load Type Created Successfully');

    }

    public function edit(ContainerLoadType $load_type){
        $this->authorize(__FUNCTION__,ContainerLoadType::class);
        return view('load-type.edit',[
            'model'=>$load_type
        ]);
    }

    public function update(Request $request,ContainerLoadType $load_type){
        $this->authorize(__FUNCTION__,ContainerLoadType::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token');
        $load_type->update($data);
        return redirect()->route('load-types.index')->with('success','Container Load Type Updated Successfully');

    }

    protected function rules($is_update = false,$model = null){
        $rules =  [
            'name'=>'required',
            'prefix'=>'required'
        ];
        if($is_update){
            $rules = array_merge($rules, [

            ]);
        }
        return $rules;
    }
}
