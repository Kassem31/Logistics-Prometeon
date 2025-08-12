<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Broker;
use  App\Models\Country;

class BrokerController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,Broker::class);
        $items = Broker::with('country')->paginate(30);
        return view('broker.index',[
            'items'=>$items
        ]);
    }
    public function create(){
        $this->authorize(__FUNCTION__,Broker::class);
        $countries = Country::orderBy('name')->get();
        return view('broker.create',[
            'countries'=>$countries
        ]);
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,Broker::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token');
        Broker::create($data);
        return redirect()->route('brokers.index')->with('success','Broker Created Successfully');

    }

    public function edit(Broker $broker){
        $this->authorize(__FUNCTION__,Broker::class);
        $countries = Country::orderBy('name')->get();
        return view('broker.edit',[
            'model'=>$broker,
            'countries'=>$countries
        ]);
    }

    public function update(Request $request,Broker $broker){
        $this->authorize(__FUNCTION__,Broker::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token');

        $broker->update($data);
        return redirect()->route('brokers.index')->with('success','Broker Updated Successfully');

    }

    protected function rules($is_update = false,$model = null){
        $rules =  [
            'name'=>'required',
            'email'=>'nullable|email',
            'phone'=>'required'
        ];
        if($is_update){
            $rules = array_merge($rules, [
            ]);
        }
        return $rules;
    }
}
