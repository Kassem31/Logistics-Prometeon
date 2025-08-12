<?php
namespace App\Models;

use App\models\ShippingDelivery;
use App\Models\ShippingBasic;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShippingBasicInfo extends ShippingBasic{

    public $has_permission=false;
    protected $guarded = [];
    // protected $dates = ['order_date','due_date'];

    public function incoTerm(){
        return $this->belongsTo(IncoTerm::class,'inco_term_id','id');
    }
    public function getInsuranceDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setInsuranceDateAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['insurance_date'] = null;
            }else{
                $this->attributes['insurance_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(Exception $ex){
            $this->attributes['insurance_date'] = null;
        }
    }



}
