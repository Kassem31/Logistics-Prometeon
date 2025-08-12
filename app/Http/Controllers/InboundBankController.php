<?php

namespace App\Http\Controllers;

use App\Filters\Inbound\InboundIndexFilter;
use App\Models\Bank;
use App\Models\Country;
use App\Models\Inbound;
use App\Models\ShippingClearance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InboundBankController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,ShippingClearance::class);
        $inbounds = Inbound::filter(new InboundIndexFilter(request()))->with('po_header.pic','booking')->paginate(30);
        // dd($inbounds);
        return view('inbound-bank.index',[
            'inbounds'=>$inbounds
        ]);
    }

    public function edit(Inbound $inbound_bank){
        $this->authorize(__FUNCTION__,ShippingClearance::class);
        $banks = Bank::where('is_active',1)->get();
        $currency = Country::where('currency_is_active',1)->get(['id','currency'])->unique('currency');

        return view('inbound-bank.edit',[
            'inbound'=>$inbound_bank,
            'banks'=>$banks,
            'currency'=>$currency,
            'clear'=>$inbound_bank->clearance,
            'shipping'=>$inbound_bank->shipping,

            ]);
    }
    public function update(Inbound $inbound_bank,Request $request){
        $this->authorize(__FUNCTION__,ShippingClearance::class);
        $this->validate($request,$this->rules(true,$inbound_bank));
        $inbound_bank->updateDetails($request);
        $inbound_bank->updateClearance($request);
        $inbound_bank->updateBank($request);
        return redirect()->route('inbound-banks.edit',['inbound_bank'=>$inbound_bank])->with('success','Inbound Bank Updated Successfully');

    }
    public function show(Inbound $inbound_bank){
        $this->authorize(__FUNCTION__,ShippingClearance::class);
        $banks = Bank::where('is_active',1)->get();
        $currency = Country::where('currency_is_active',1)->get(['id','currency'])->unique('currency');

        return view('inbound-bank.show',[
            'inbound'=>$inbound_bank,
            'clear'=>$inbound_bank->clearance,
            'shipping'=>$inbound_bank->shipping,
            'banks'=>$banks,
            'currency'=>$currency,
            ]);
    }
    protected function rules($is_update = false,$model = null){
        $rules =  [
            'inbound.acid_number'=>'nullable|string|max:50|regex:/^[A-Za-z0-9]*$/',
            // Bank-specific validation rules can be added here
        ];
        if($is_update){
            $rules = array_merge($rules, [
                'inbound.inbound_no'=>'nullable|unique:inbounds,inbound_no,'.$model->id
            ]);
        }
        return $rules;
    }
}
