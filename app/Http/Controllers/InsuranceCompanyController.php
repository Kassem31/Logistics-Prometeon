<?php

namespace App\Http\Controllers;

use App\Filters\NameActiveFilter\NameActiveIndexFilter;
use App\Models\InsuranceCompany;
use Illuminate\Http\Request;

class InsuranceCompanyController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,InsuranceCompany::class);
        $items = InsuranceCompany::filter(new NameActiveIndexFilter(request()))
                    ->paginate(30);
        return view('insurance-company.index',[
            'items'=>$items
        ]);
    }

    public function create(){
        $this->authorize(__FUNCTION__,InsuranceCompany::class);
        return view('insurance-company.create');
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,InsuranceCompany::class);
        $this->validate($request,$this->rules());
        $data = array_merge($request->except('_token'),[
            'is_active'=>$request->exists('is_active'),
        ]);
        InsuranceCompany::create($data);
        return redirect()->route('insurance-companies.index')->with('success','Insurance Company Created Successfully');

    }

    public function edit(InsuranceCompany $insuranceCompany){
        $this->authorize(__FUNCTION__,InsuranceCompany::class);
        return view('insurance-company.edit',[
            'model'=>$insuranceCompany
        ]);
    }

    public function update(Request $request,InsuranceCompany $insuranceCompany){
        $this->authorize(__FUNCTION__,InsuranceCompany::class);
        $this->validate($request,$this->rules());
        $data = array_merge($request->except('_token'),[
            'is_active'=>$request->exists('is_active'),
        ]);
        $insuranceCompany->update($data);
        return redirect()->route('insurance-companies.index')->with('success','Insurance Company Updated Successfully');

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
