<?php

namespace App\Http\Controllers;

use App\Models\IncoForwarder;
use App\Models\Country;
use Illuminate\Http\Request;

class IncoForwardersController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,IncoForwarder::class);
        $items = IncoForwarder::with('country')->paginate(30);
        return view('incoforwarders.index',[
            'items'=>$items
        ]);
    }

    public function create(){
        $this->authorize(__FUNCTION__,IncoForwarder::class);
        $countries = Country::orderBy('name')->get();
        return view('incoforwarders.create',[
            'countries'=>$countries
        ]);
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,IncoForwarder::class);
        $this->validate($request,$this->rules());
        $data = array_merge($request->except('_token'),[
            'is_active'=>$request->exists('is_active'),
        ]);
        IncoForwarder::create($data);
        return redirect()->route('inco-forwarders.index')->with('success','Inco Forwarder Created Successfully');

    }

    public function edit(IncoForwarder $inco_forwarder){
        $this->authorize(__FUNCTION__,IncoForwarder::class);
        $countries = Country::orderBy('name')->get();
        return view('incoforwarders.edit',[
            'countries'=>$countries,
            'model'=>$inco_forwarder
        ]);
    }

    public function update(Request $request,IncoForwarder $inco_forwarder){
        $this->authorize(__FUNCTION__,IncoForwarder::class);
        $this->validate($request,$this->rules());
        $data = array_merge($request->except('_token'),[
            'is_active'=>$request->exists('is_active'),
        ]);
        $inco_forwarder->update($data);
        return redirect()->route('inco-forwarders.index')->with('success','Inco Forwarder Updated Successfully');

    }

    protected function rules($is_update = false,$model = null){
        $rules =  [
            'name'=>'required',
            'email'=>'nullable|email'
        ];
        if($is_update){
            $rules = array_merge($rules, [

            ]);
        }
        return $rules;
    }

}
