<?php

namespace App\Http\Controllers;

use App\Models\IncoTerm;
use Illuminate\Http\Request;

class IncoTermsController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,IncoTerm::class);
        $items = IncoTerm::paginate(30);
        return view('inco-terms.index',[
            'items'=>$items
        ]);
    }

    public function create(){
        $this->authorize(__FUNCTION__,IncoTerm::class);
        return view('inco-terms.create');
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,IncoTerm::class);
        $this->validate($request,$this->rules());
        $data = array_merge($request->except('_token'),[
            'is_active'=>$request->exists('is_active'),
        ]);
        IncoTerm::create($data);
        return redirect()->route('inco-terms.index')->with('success','Inco Term Created Successfully');

    }

    public function edit(IncoTerm $inco_term){
        $this->authorize(__FUNCTION__,IncoTerm::class);
        return view('inco-terms.edit',[
            'model'=>$inco_term
        ]);
    }

    public function update(Request $request,IncoTerm $inco_term){
        $this->authorize(__FUNCTION__,IncoTerm::class);
        $this->validate($request,$this->rules());
        $data = array_merge($request->except('_token'),[
            'is_active'=>$request->exists('is_active'),
        ]);
        $inco_term->update($data);
        return redirect()->route('inco-terms.index')->with('success','Inco Term Updated Successfully');

    }

    protected function rules($is_update = false,$model = null){
        $rules =  [
            'name'=>'required',
            'prefix'=>'required'
        ];
        if($is_update){
            $rules = array_merge($rules, [

            ]);
        }
        return $rules;
    }
}
