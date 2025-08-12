<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,Supplier::class);
        $items = Supplier::with('country')->paginate(30);
        return view('supplier.index',[
            'items'=>$items
        ]);
    }
    public function create(){
        $this->authorize(__FUNCTION__,Supplier::class);
        $countries = Country::orderBy('name')->get();
        return view('supplier.create',[
            'countries'=>$countries
        ]);
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,Supplier::class);
        $this->validate($request,$this->rules());
        $data = array_merge($request->except('_token'),[
            'is_active'=>$request->exists('is_active'),
            'is_group'=>$request->exists('is_group')
        ]);
        Supplier::create($data);
        return redirect()->route('suppliers.index')->with('success','Supplier Created Successfully');

    }

    public function edit(Supplier $supplier){
        $this->authorize(__FUNCTION__,Supplier::class);
        $countries = Country::orderBy('name')->get();
        return view('supplier.edit',[
            'item'=>$supplier,
            'countries'=>$countries
        ]);
    }

    public function update(Request $request,Supplier $supplier){
        $this->authorize(__FUNCTION__,Supplier::class);
        $this->validate($request,$this->rules(true,$supplier));
        $data = array_merge($request->except('_token'),[
            'is_active'=>$request->exists('is_active'),
            'is_group'=>$request->exists('is_group')
        ]);
        $supplier->update($data);
        return redirect()->route('suppliers.index')->with('success','Supplier Updated Successfully');

    }

    protected function rules($is_update = false,$model = null){
        $rules =  [
            'name'=>'required',
            'sap_code'=>'required|unique:suppliers,sap_code'
        ];
        if($is_update){
            $rules = array_merge($rules, [
                'sap_code'=>'required|unique:suppliers,sap_code,'.$model->id,
            ]);
        }
        return $rules;
    }
}
