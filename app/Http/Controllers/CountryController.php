<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,Country::class);
        $items = Country::orderBy('name')->paginate(30);
        return view('country.index',['items'=>$items]);
    }
    public function create(){
        $this->authorize(__FUNCTION__,Country::class);
        return view('country.create');
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,Country::class);
        $this->validate($request,$this->rules());
        $data = array_merge($request->except('_token'),[
            'currency_is_active'=>$request->exists('currency_is_active'),
            'country_flag'=>$this->storeFlag($request)
        ]);
        Country::create($data);
        return redirect()->route('countries.index')->with('success','Country Created Successfully');

    }

    public function edit(Country $country){
        $this->authorize(__FUNCTION__,Country::class);
        return view('country.edit',[
            'model'=>$country
        ]);
    }

    public function update(Request $request,Country $country){
        $this->authorize(__FUNCTION__,Country::class);
        $this->validate($request,$this->rules(true,$country));
        $data = array_merge($request->except('_token'),[
            'currency_is_active'=>$request->exists('currency_is_active'),
            'country_flag'=>$this->storeFlag($request)
        ]);
        $country->update($data);
        return redirect()->route('countries.index')->with('success','Country Updated Successfully');

    }

    protected function rules($is_update = false,$model = null){
        $rules =  [
            'name'=>'required|alpha|unique:countries,name',
            'prefix'=>'required|alpha|unique:countries,prefix',
            'currency'=>'required|size:3|alpha'
        ];
        if($is_update){
            $rules = array_merge($rules, [
                'name'=>'required|alpha|unique:countries,name,'.$model->id,
                'prefix'=>'required|alpha|unique:countries,prefix,'.$model->id,
            ]);
        }
        return $rules;
    }

    protected function storeFlag(Request $request,$url = null){
        return $request->hasFile('country_flag') ? $request->file('country_flag')->store('flags',['disk' => 'public']) : $url;
    }
}
