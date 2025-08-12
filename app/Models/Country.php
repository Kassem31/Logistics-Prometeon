<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $display_name = "countries";

    protected $guarded = [];

    public function ports(){
        return $this->hasMany(Port::class,'country_id','id');
    }

    public function suppliers(){
        return $this->hasMany(Supplier::class,'country_id','id');
    }

    public function incoForwarders(){
        return $this->hasMany(IncoForwarder::class,'country_id','id');
    }

    public function shippingLines(){
        return $this->hasMany(ShippingLine::class,'country_id','id');
    }

    public function brokers(){
        return $this->hasMany(Broker::class,'country_id','id');
    }

    public function getCountryFlagAttribute($value){
        return is_null($value) ? asset('flags/'.$this->prefix.'.png') : asset('storage/'.$value);
    }

}
