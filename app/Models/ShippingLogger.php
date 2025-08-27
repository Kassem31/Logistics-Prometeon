<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShippingLogger extends Model
{
    public $display_name = "Shipping Logger";

    protected $dateFields = [
        'ets','eta','ats','ata'
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function UpdatedAt(){
        return optional($this->created_at)->format('d/m/Y h:i:s A');
    }

    public function userName(){
        return optional($this->user)->full_name;
    }

    public function value(){
        if(is_null($this->field_value)) return '';
        if(in_array($this->field_name,$this->dateFields)){
            return Carbon::parse($this->field_value)->format('d/m/Y');
        }
        if(Str::contains($this->field_name,'broker')){
            return optional(\App\Models\Broker::find($this->field_value))->name;
        }
        if(Str::contains($this->field_name,'custom')){
            return optional(\App\Models\CustomSystem::find($this->field_value))->name;
        }
        if(Str::contains($this->field_name,'currency')){
            return optional(\App\Models\Country::find($this->field_value))->currency;
        }
        return $this->field_value;
    }
}
