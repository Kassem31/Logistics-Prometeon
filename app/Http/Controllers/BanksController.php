<?php

namespace App\Http\Controllers;

use App\Filters\NameActiveFilter\NameActiveIndexFilter;
use App\Models\Bank;
use Illuminate\Http\Request;

class BanksController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,Bank::class);
        $items = Bank::filter(new NameActiveIndexFilter(request()))
                    ->paginate(30);
        return view('banks.index',[
            'items'=>$items
        ]);
    }

    public function create(){
        $this->authorize(__FUNCTION__,Bank::class);
        return view('banks.create');
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,Bank::class);
        $this->validate($request,$this->rules());
        $data = array_merge($request->except('_token'),[
            'is_active'=>$request->exists('is_active'),
        ]);
        Bank::create($data);
        return redirect()->route('banks.index')->with('success','Bank Created Successfully');

    }

    public function edit(Bank $bank){
        $this->authorize(__FUNCTION__,Bank::class);
        return view('banks.edit',[
            'model'=>$bank
        ]);
    }

    public function update(Request $request,Bank $bank){
        $this->authorize(__FUNCTION__,Bank::class);
        $this->validate($request,$this->rules());
        $data = array_merge($request->except('_token'),[
            'is_active'=>$request->exists('is_active'),
        ]);
        $bank->update($data);
        return redirect()->route('banks.index')->with('success','Bank Updated Successfully');

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
