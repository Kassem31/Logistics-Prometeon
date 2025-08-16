<?php

namespace App\Models;

use App\Models\ShippingBasic;
use Illuminate\Support\Carbon;
use Exception;

class ShippingDelivery extends ShippingBasic
{
    protected $guarded = [];
    public $has_permission=false;

    public function getAtcoDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setAtcoDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['atco_date'] = null;
            }else{
                $this->attributes['atco_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(Exception $ex){
            $this->attributes['atco_date'] = null;
        }
    }

    public function getSapDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setSapDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['sap_date'] = null;
            }else{
                $this->attributes['sap_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(Exception $ex){
            $this->attributes['sap_date'] = null;
        }
    }

    public function getBwhDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setBwhDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['bwh_date'] = null;
            }else{
                $this->attributes['bwh_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(Exception $ex){
            $this->attributes['bwh_date'] = null;
        }
    }
}
