<?php

namespace App\Http\Controllers;

use App\Models\ContainerSize;
use Illuminate\Http\Request;

class ContainerSizeController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,ContainerSize::class);
        $items = ContainerSize::paginate(30);
        return view('container-size.index',[
            'items'=>$items
        ]);
    }

    public function create(){
        $this->authorize(__FUNCTION__,ContainerSize::class);
        return view('container-size.create');
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,ContainerSize::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token');
        ContainerSize::create($data);
        return redirect()->route('container-sizes.index')->with('success','Container Size Created Successfully');

    }

    public function edit(ContainerSize $container_size){
        $this->authorize(__FUNCTION__,ContainerSize::class);
        return view('container-size.edit',[
            'model'=>$container_size
        ]);
    }

    public function update(Request $request,ContainerSize $container_size){
        $this->authorize(__FUNCTION__,ContainerSize::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token');
        $container_size->update($data);
        return redirect()->route('container-sizes.index')->with('success','Container Size Updated Successfully');

    }

    protected function rules($is_update = false,$model = null){
        $rules =  [
            'size'=>'required',
        ];
        if($is_update){
            $rules = array_merge($rules, [

            ]);
        }
        return $rules;
    }
}
