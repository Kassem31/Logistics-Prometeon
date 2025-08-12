<?php

namespace App\Http\Controllers;

use App\Models\ShippingLine;
use App\Models\Country;

use Illuminate\Http\Request;

class ShippingLineController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,ShippingLine::class);
        $items = ShippingLine::with('country')->paginate(30);
        return view('shipping-line.index',[
            'items'=>$items
        ]);
    }
    public function create(){
        $this->authorize(__FUNCTION__,ShippingLine::class);
        $countries = Country::orderBy('name')->get();
        return view('shipping-line.create',[
            'countries'=>$countries
        ]);
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,ShippingLine::class);
        $this->validate($request,$this->rules());
        $data = array_merge($request->except('_token'),[
            'is_active'=>$request->exists('is_active'),
        ]);
        ShippingLine::create($data);
        return redirect()->route('shipping-line.index')->with('success','Shipping Line Created Successfully');

    }

    public function edit(ShippingLine $shipping_line){
        $this->authorize(__FUNCTION__,ShippingLine::class);
        $countries = Country::orderBy('name')->get();
        return view('shipping-line.edit',[
            'model'=>$shipping_line,
            'countries'=>$countries
        ]);
    }

    public function update(Request $request,ShippingLine $shipping_line){
        $this->authorize(__FUNCTION__,ShippingLine::class);
        $this->validate($request,$this->rules());
        $data = array_merge($request->except('_token'),[
            'is_active'=>$request->exists('is_active'),
        ]);
        $shipping_line->update($data);
        return redirect()->route('shipping-line.index')->with('success','Shipping Line Updated Successfully');

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
