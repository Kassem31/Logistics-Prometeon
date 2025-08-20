<?php

namespace App\Models;

use App\Models\ShippingBasic;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Exception;

class ShippingDocument extends ShippingBasic
{
    protected $guarded = [];
    public $has_permission=false;


    public function getInvoiceCopyAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setInvoiceCopyAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['invoice_copy'] = null;
            }else{
                $this->attributes['invoice_copy'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(Exception $ex){
            $this->attributes['invoice_copy'] = null;
        }
    }

    public function getPurchaseConfirmationAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setPurchaseConfirmationAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['purchase_confirmation'] = null;
            }else{
                $this->attributes['purchase_confirmation'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(Exception $ex){
            $this->attributes['purchase_confirmation'] = null;
        }
    }

    public function getOriginalInvoiceAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setOriginalInvoiceAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['original_invoice'] = null;
            }else{
                $this->attributes['original_invoice'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(Exception $ex){
            $this->attributes['original_invoice'] = null;
        }
    }

    public function getStampedInvoiceAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setStampedInvoiceAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['stamped_invoice'] = null;
            }else{
                $this->attributes['stamped_invoice'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(Exception $ex){
            $this->attributes['stamped_invoice'] = null;
        }
    }

    public function getCopyDocsAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setCopyDocsAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['copy_docs'] = null;
            }else{
                $this->attributes['copy_docs'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(Exception $ex){
            $this->attributes['copy_docs'] = null;
        }
    }

    public function getOriginalDocsAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setOriginalDocsAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['original_docs'] = null;
            }else{
                $this->attributes['original_docs'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(Exception $ex){
            $this->attributes['original_docs'] = null;
        }
    }

    public function getCopyDocsBrokerAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setCopyDocsBrokerAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['copy_docs_broker'] = null;
            }else{
                $this->attributes['copy_docs_broker'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(Exception $ex){
            $this->attributes['copy_docs_broker'] = null;
        }
    }

    public function getOriginalDocsBrokerAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setOriginalDocsBrokerAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['original_docs_broker'] = null;
            }else{
                $this->attributes['original_docs_broker'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(Exception $ex){
            $this->attributes['original_docs_broker'] = null;
        }
    }

    public function getStampedInvoiceBrokerAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setStampedInvoiceBrokerAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['stamped_invoice_broker'] = null;
            }else{
                $this->attributes['stamped_invoice_broker'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(Exception $ex){
            $this->attributes['stamped_invoice_broker'] = null;
        }
    }
}
