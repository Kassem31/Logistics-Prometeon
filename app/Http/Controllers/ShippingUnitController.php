<?php

namespace App\Http\Controllers;

use App\Models\ShippingUnit;
use Illuminate\Http\Request;

class ShippingUnitController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,ShippingUnit::class);
        $items = ShippingUnit::paginate(30);
        return view('shipping-unit.index',[
            'items'=>$items
        ]);
    }

    public function create(){
        $this->authorize(__FUNCTION__,ShippingUnit::class);
        return view('shipping-unit.create');
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,ShippingUnit::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token');
        ShippingUnit::create($data);
        return redirect()->route('shipping-unit.index')->with('success','Shipping Unit Created Successfully');

    }

    public function edit(ShippingUnit $shipping_unit){
        $this->authorize(__FUNCTION__,ShippingUnit::class);
        return view('shipping-unit.edit',[
            'model'=>$shipping_unit
        ]);
    }

    public function update(Request $request,ShippingUnit $shipping_unit){
        $this->authorize(__FUNCTION__,ShippingUnit::class);
        $this->validate($request,$this->rules());
        $data = $request->except('_token');
        $shipping_unit->update($data);
        return redirect()->route('shipping-unit.index')->with('success','Shipping Unit Updated Successfully');

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
