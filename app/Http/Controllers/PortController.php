<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Country;

use Illuminate\Http\Request;

class PortController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,Port::class);
        $items = Port::with('country')->orderBy('name')->paginate(30);
        return view('ports.index',[
            'items'=>$items
        ]);
    }
    public function create(){
        $this->authorize(__FUNCTION__,Port::class);
        $countries = Country::orderBy('name')->get();
        return view('ports.create',[
            'countries'=>$countries
        ]);
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,Port::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token');
        Port::create($data);
        return redirect()->route('ports.index')->with('success','Port Created Successfully');

    }

    public function edit(Port $port){
        $this->authorize(__FUNCTION__,Port::class);
        $countries = Country::orderBy('name')->get();
        return view('ports.edit',[
            'model'=>$port,
            'countries'=>$countries
        ]);
    }

    public function update(Request $request,Port $port){
        $this->authorize(__FUNCTION__,Port::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token');

        $port->update($data);
        return redirect()->route('ports.index')->with('success','Port Updated Successfully');

    }

    protected function rules($is_update = false,$model = null){
        $rules =  [
            'name'=>'required',
            'country_id'=>'required'
        ];
        if($is_update){
            $rules = array_merge($rules, [
            ]);
        }
        return $rules;
    }
}
