<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingLine extends Model
{
    public $display_name = "shipping lines";

    protected $guarded = [];

    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
}
