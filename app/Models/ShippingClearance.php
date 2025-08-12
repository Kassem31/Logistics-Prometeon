<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Models\ShippingBasic;

class ShippingClearance extends ShippingBasic
{
    public $display_name = "Inbound Bank";
    protected $guarded = [];
    public $has_permission=true;

    public function customSystem(){
        return $this->belongsTo(CustomSystem::class,'custom_system_id','id')->withDefault();
    }

    public function bank(){
        return $this->belongsTo(Bank::class,'bank_id','id');
    }

    public function invoiceCurrency(){
        return $this->belongsTo(Country::class,'invoice_currency_id','id');
    }

    public function inbound(){
        return $this->belongsTo(Inbound::class,'shipping_id','id');
    }

    public function getBankLetterDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setBankLetterDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['bank_letter_date'] = null;
            }else{
                $this->attributes['bank_letter_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['bank_letter_date'] = null;
        }
    }

    public function getDeliveryBankDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setDeliveryBankDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['delivery_bank_date'] = null;
            }else{
                $this->attributes['delivery_bank_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['delivery_bank_date'] = null;
        }
    }

    public function getInvoiceDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setInvoiceDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['invoice_date'] = null;
            }else{
                $this->attributes['invoice_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['invoice_date'] = null;
        }
    }
    //Form 4
    public function getForm4IssueDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setForm4IssueDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['form4_issue_date'] = null;
            }else{
                $this->attributes['form4_issue_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['form4_issue_date'] = null;
        }
    }

    public function getForm4RecDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setForm4RecDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['form4_rec_date'] = null;
            }else{
                $this->attributes['form4_rec_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['form4_rec_date'] = null;
        }
    }


    //Form 6
    public function getForm6IssueDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setForm6IssueDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['form6_issue_date'] = null;
            }else{
                $this->attributes['form6_issue_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['form6_issue_date'] = null;
        }
    }

    public function getForm6RecDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setForm6RecDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['form6_rec_date'] = null;
            }else{
                $this->attributes['form6_rec_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['form6_rec_date'] = null;
        }
    }
    //Transit

    public function getTransitIssueDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setTransitIssueDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['transit_issue_date'] = null;
            }else{
                $this->attributes['transit_issue_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['transit_issue_date'] = null;
        }
    }

    public function getTransitRecDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setTransitRecDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['transit_rec_date'] = null;
            }else{
                $this->attributes['transit_rec_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['transit_rec_date'] = null;
        }
    }

    // Temp
    public function getLgRequestDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setLgRequestDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['lg_request_date'] = null;
            }else{
                $this->attributes['lg_request_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['lg_request_date'] = null;
        }
    }

    public function getLgIssuanceDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setLgIssuanceDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['lg_issuance_date'] = null;
            }else{
                $this->attributes['lg_issuance_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['lg_issuance_date'] = null;
        }
    }
    public function getLgBrokerReceiptDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setLgBrokerReceiptDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['lg_broker_receipt_date'] = null;
            }else{
                $this->attributes['lg_broker_receipt_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['lg_broker_receipt_date'] = null;
        }
    }

    //Bank Dates
    public function getBankInDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setBankInDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['bank_in_date'] = null;
            }else{
                $this->attributes['bank_in_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['bank_in_date'] = null;
        }
    }

    public function getBankOutDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setBankOutDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['bank_out_date'] = null;
            }else{
                $this->attributes['bank_out_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['bank_out_date'] = null;
        }
    }

    public function getBankRecDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setBankRecDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['bank_rec_date'] = null;
            }else{
                $this->attributes['bank_rec_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['bank_rec_date'] = null;
        }
    }

    public function getDoDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setDoDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['do_date'] = null;
            }else{
                $this->attributes['do_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['do_date'] = null;
        }
    }


    public function getRegisterationDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setRegisterationDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['registeration_date'] = null;
            }else{
                $this->attributes['registeration_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['registeration_date'] = null;
        }
    }

    public function getInspectionDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setInspectionDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['inspection_date'] = null;
            }else{
                $this->attributes['inspection_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['inspection_date'] = null;
        }
    }

    public function getWithdrawDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setWithdrawDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['withdraw_date'] = null;
            }else{
                $this->attributes['withdraw_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['withdraw_date'] = null;
        }
    }

    public function getResultDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setResultDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['result_date'] = null;
            }else{
                $this->attributes['result_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['result_date'] = null;
        }
    }



    public function getSentToBankDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setSentToBankDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['sent_to_bank_date'] = null;
            }else{
                $this->attributes['sent_to_bank_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['sent_to_bank_date'] = null;
        }
    }



    public function getFormDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setFormDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['form_date'] = null;
            }else{
                $this->attributes['form_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['form_date'] = null;
        }
    }

    public function getBrokerReceiptDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setBrokerReceiptDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['broker_receipt_date'] = null;
            }else{
                $this->attributes['broker_receipt_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['broker_receipt_date'] = null;
        }
    }

    // Accessors and Mutators for the new accounting date fields
    public function getReceivedAccountingDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    
    public function setReceivedAccountingDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['received_accounting_date'] = null;
            }else{
                $this->attributes['received_accounting_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['received_accounting_date'] = null;
        }
    }

    public function getInvoicingDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    
    public function setInvoicingDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['invoicing_date'] = null;
            }else{
                $this->attributes['invoicing_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['invoicing_date'] = null;
        }
    }
}
