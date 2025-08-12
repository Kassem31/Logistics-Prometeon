<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    public $display_name = "brokers";

    protected $guarded = [];

    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
}
